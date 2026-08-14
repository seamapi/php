# Seam PHP SDK

[![Packagist](https://img.shields.io/packagist/v/seamapi/seam.svg)](https://packagist.org/packages/seamapi/seam)
[![Seam LTS Version](https://img.shields.io/badge/Seam_LTS-1.0.0-blue)](https://docs.seam.co/lts)
[![GitHub Actions](https://github.com/seamapi/php/actions/workflows/check.yml/badge.svg)](https://github.com/seamapi/php/actions/workflows/check.yml)

PHP SDK for the Seam API.

## Description

[Seam] makes it easy to integrate IoT devices with your applications.
This is an official SDK for the Seam API.
Please refer to the official [Seam Docs] to get started.

Parts of this SDK are generated from always up-to-date type information
provided by [@seamapi/types].
This ensures all API methods, request shapes, and response shapes are
accurate and fully typed.

The underlying HTTP client is [Guzzle].

[Seam]: https://www.seam.co/
[Seam Docs]: https://docs.seam.co/latest/
[@seamapi/types]: https://github.com/seamapi/types/
[Guzzle]: https://docs.guzzlephp.org/

## Installation

Add this as a dependency to your project using [Composer] with

```
$ composer require seamapi/seam
```

[Composer]: https://getcomposer.org/

## Usage

> [!NOTE]
> These examples assume `SEAM_API_KEY` is set in your environment.

Endpoint methods take [named arguments], which is the supported way to call
them. PHP allows the parameters to be passed positionally too, but their order
is derived from the API definition and can change as endpoints gain parameters,
so a positional call can start binding a value to the wrong parameter after an
upgrade. Passing them by name is stable.

[named arguments]: https://www.php.net/manual/en/functions.arguments.php#functions.named-arguments

### Examples

#### List devices

```php
$seam = new Seam\Seam();

$devices = $seam->devices->list();
```

#### Unlock a door

```php
$seam = new Seam\Seam();

$lock = $seam->locks->get(name: "Front Door");
$seam->locks->unlock_door(device_id: $lock->device_id);
```

### Authentication Method

The SDK supports two authentication mechanisms.
Configure either by passing the corresponding options to the `Seam`
constructor, or with the more ergonomic static factory methods.

#### API Key

An API key is scoped to a single workspace and should only be used on the
server. Obtain one from the Seam Console.

```php
// Set the SEAM_API_KEY environment variable
$seam = new Seam\Seam();

// Pass as an option to the constructor
$seam = new Seam\Seam(api_key: "your-api-key");

// Use the factory method
$seam = Seam\Seam::from_api_key("your-api-key");
```

#### Personal Access Token

A Personal Access Token is scoped to a Seam Console user.
It must be used with a workspace id.

```php
// Set the SEAM_PERSONAL_ACCESS_TOKEN and SEAM_WORKSPACE_ID environment variables
$seam = new Seam\Seam();

// Pass as options to the constructor
$seam = new Seam\Seam(
    personal_access_token: "your-personal-access-token",
    workspace_id: "your-workspace-id"
);

// Use the factory method
$seam = Seam\Seam::from_personal_access_token(
    "your-personal-access-token",
    "your-workspace-id"
);
```

### Action Attempts

Some operations tell a device to do something, and the device may take time
to report back. Those endpoints return an [action attempt].

By default the SDK waits for the action attempt to finish:

- It polls up to a timeout at a polling interval.
- It returns a fresh copy of the successful action attempt.
- It throws `Seam\ActionAttemptFailedError` if the action failed.
- It throws `Seam\ActionAttemptTimeoutError` if the timeout
  elapses first.

Both errors extend `Seam\ActionAttemptError` and expose the action
attempt with `getActionAttempt()`.

[action attempt]: https://docs.seam.co/latest/core-concepts/action-attempts

```php
use Seam\ActionAttemptFailedError;
use Seam\ActionAttemptTimeoutError;

try {
    $seam->locks->unlock_door(device_id: $device_id);
} catch (ActionAttemptFailedError $error) {
    print "Could not unlock the door: " . $error->getMessage();
    print "Error code: " . $error->getErrorCode();
} catch (ActionAttemptTimeoutError $error) {
    print "The door did not unlock in time";
    print "Action attempt: " . $error->getActionAttempt()->action_attempt_id;
}
```

Waiting may be disabled for the whole client:

```php
$seam = new Seam\Seam(wait_for_action_attempt: false);

$action_attempt = $seam->locks->unlock_door(device_id: $device_id);
$action_attempt->status; // "pending"
```

or for a single request:

```php
$action_attempt = $seam->locks->unlock_door(
    device_id: $device_id,
    wait_for_action_attempt: false
);
```

The timeout and polling interval, both in seconds, may be configured either
on the client or per request:

```php
$seam = new Seam\Seam(
    wait_for_action_attempt: ["timeout" => 30.0, "polling_interval" => 2.0]
);

$seam->locks->unlock_door(
    device_id: $device_id,
    wait_for_action_attempt: ["timeout" => 5.0]
);
```

### Setting a param to null

The Seam API distinguishes three states for an updatable param:
omitted (leave the stored value unchanged), null (unset the stored value),
and a value (set it).

PHP's `null` means omitted.
The SDK removes `null` params from the request entirely,
so passing `null` never unsets a value.
To unset a value, pass the `Seam\NullValue::NULL` sentinel,
which the SDK sends as JSON `null` in request bodies
and as an empty value in query strings:

```php
use Seam\NullValue;

// Leaves the name unchanged.
$seam->devices->update(device_id: $device_id, name: null);

// Unsets the name.
$seam->devices->update(device_id: $device_id, name: NullValue::NULL);
```

The other Seam SDKs spell the sentinel `NULL` and its type `Null`,
but those names are reserved in PHP, so both live on one enum:
`Seam\NullValue` is the type, and its single case `NullValue::NULL`
is the value to pass.

Only pass `NullValue::NULL` for params the API documents as nullable.
Generated methods type nullable params as a union with the sentinel,
e.g. `string|NullValue|null`, so passing it anywhere else fails with a
`TypeError`.

### Pagination

Some Seam API endpoints that return lists of resources support pagination.
Use the `Paginator` class to fetch and process resources across multiple pages.

#### Manually fetch pages with the next_page_cursor

```php
$pages = $seam->createPaginator(
    fn($params) => $seam->connected_accounts->list(...$params),
    ["limit" => 2]
);

[$connectedAccounts, $pagination] = $pages->firstPage();

if ($pagination->has_next_page) {
    [$moreConnectedAccounts] = $pages->nextPage($pagination->next_page_cursor);
}
```

#### Resume pagination

Get the first page on initial load:

```php
$params = ["limit" => 20];

$pages = $seam->createPaginator(
    fn($p) => $seam->connected_accounts->list(...$p),
    $params
);

[$connectedAccounts, $pagination] = $pages->firstPage();

// Store pagination state for later use
file_put_contents(
    "/tmp/seam_connected_accounts_list.json",
    json_encode([$params, $pagination])
);
```

Get the next page at a later time:

```php
$stored_data = json_decode(
    file_get_contents("/tmp/seam_connected_accounts_list.json") ?: "[]",
    false
);

$params = $stored_data[0] ?? [];
$pagination =
    $stored_data[1] ??
    (object) ["has_next_page" => false, "next_page_cursor" => null];

if ($pagination->has_next_page) {
    $pages = $seam->createPaginator(
        fn($p) => $seam->connected_accounts->list(...$p),
        $params
    );
    [$moreConnectedAccounts] = $pages->nextPage($pagination->next_page_cursor);
}
```

#### Iterate over all resources

```php
$pages = $seam->createPaginator(
    fn($p) => $seam->connected_accounts->list(...$p),
    ["limit" => 20]
);

foreach ($pages->flatten() as $connectedAccount) {
    print $connectedAccount->account_type_display_name . "\n";
}
```

#### Return all resources across all pages as an array

```php
$pages = $seam->createPaginator(
    fn($p) => $seam->connected_accounts->list(...$p),
    ["limit" => 20]
);

$connectedAccounts = $pages->flattenToArray();
```

### Requests without a Workspace in scope

Some endpoints are not scoped to a workspace. Use `SeamWithoutWorkspace` with a
personal access token to reach them.

```php
// Set the SEAM_PERSONAL_ACCESS_TOKEN environment variable
$seam = new Seam\SeamWithoutWorkspace();

// Use the factory method
$seam = Seam\SeamWithoutWorkspace::from_personal_access_token(
    "your-personal-access-token"
);

// List workspaces authorized for this Personal Access Token
$workspaces = $seam->workspaces->list();

$workspace = $seam->workspaces->create(
    name: "New Workspace",
    connect_partner_name: "Your Company"
);
```

### Webhooks

Seam delivers webhooks with [Svix]. Verify and parse an incoming request with
`SeamWebhook`, which returns the typed event.

[Svix]: https://www.svix.com/

```php
$webhook = new Seam\SeamWebhook($webhook_secret);

try {
    $event = $webhook->verify($request_body, $request_headers);
    print $event->event_type;
} catch (Svix\Exception\WebhookVerificationException $error) {
    http_response_code(400);
}
```

### Advanced Usage

#### Setting the endpoint

The endpoint may be set with the `SEAM_ENDPOINT` environment variable, or
passed directly.

```php
$seam = new Seam\Seam(endpoint: "https://example.com");
```

#### Configuring the Guzzle client

Pass any [Guzzle request option] with `guzzle_options`. They are merged into
the client the SDK builds, so the authorization and SDK headers are kept.

[Guzzle request option]: https://docs.guzzlephp.org/en/stable/request-options.html

```php
$seam = new Seam\Seam(
    guzzle_options: [
        "headers" => ["X-Custom-Header" => "value"],
        "proxy" => "http://localhost:8125",
    ]
);
```

#### Setting the timeout

Requests time out after 30 seconds by default, covering both connecting and
reading. Pass `timeout` in seconds to change it, or `0` to disable it.

```php
$seam = new Seam\Seam(timeout: 60.0);
```

#### Retries

By default, the SDK makes up to three attempts: the initial request and two
retries. Retries are limited to `GET`, `HEAD`, `OPTIONS`, `PUT`, and `DELETE`
requests that fail because of a transport error, timeout, HTTP 429 response, or
HTTP 5xx response. `POST` and `PATCH` requests are not retried.

Retries use exponential backoff with jitter: approximately 200–240 ms before
the first retry and 400–480 ms before the second. A longer `Retry-After` header
is honored. The request timeout is reset for each attempt.

```php
// Retry more times
$seam = new Seam\Seam(retries: 5);

// Turn retries off
$seam = new Seam\Seam(retries: 0);
```

#### Using the Guzzle client

`$seam->client` is the [Guzzle] client, already carrying the endpoint and
authorization, so it can be used to reach an endpoint the SDK does not expose.

[Guzzle]: https://docs.guzzlephp.org/

```php
$response = $seam->client->request("POST", "/devices/list", [
    "json" => (object) ["limit" => 10],
]);

$devices = Seam\Http\Body::decode($response)->devices;
```

#### Overriding the client

Pass an already configured Guzzle client. It carries its own endpoint and
authorization, so it cannot be combined with any option other than
`wait_for_action_attempt`.

```php
$client = new GuzzleHttp\Client([
    "base_uri" => "https://connect.getseam.com",
    "headers" => ["authorization" => "Bearer " . $api_key],
]);

$seam = Seam\Seam::from_client($client);
```

#### Serializing URL search params

The Seam API parses URL search params as complex types.
If you call it with your own HTTP client,
`Seam\StrictUrlSearchParamsSerializer` is exported for that purpose.
The `_strict=true` param is added to any non-empty query
so the Seam API uses strict, schema-aware parsing.
A query with no serializable params remains empty.

```php
use Seam\StrictUrlSearchParamsSerializer;

$query = StrictUrlSearchParamsSerializer::serialize([
    "device_ids" => ["device1", "device2"],
]);

$response = file_get_contents(
    "https://connect.getseam.com/devices/list?{$query}",
    context: stream_context_create([
        "http" => ["header" => "Authorization: Bearer your-api-key"],
    ]),
);
```

The serialization defines the name and value of each search param,
where every value is a string.
`Seam\UrlSearchParams` holds those pairs and renders the query string,
as [URLSearchParams] does for the [reference implementation]:

```php
use Seam\StrictUrlSearchParamsSerializer;
use Seam\UrlSearchParams;

$search_params = new UrlSearchParams();

StrictUrlSearchParamsSerializer::update($search_params, [
    "device_ids" => ["device1", "device2"],
]);

iterator_to_array($search_params);
// => [["device_ids", "device1"], ["device_ids", "device2"], ["_strict", "true"]]

(string) $search_params;
// => 'device_ids=device1&device_ids=device2&_strict=true'
```

Pass either the query string or the pairs to your HTTP client.
A client may percent-encode a few characters differently than
`URLSearchParams` does, e.g. Guzzle escapes `*` and leaves `~` unescaped,
which the Seam API reads as the same params either way.

A param set to `null` is omitted,
while a param set to `NullValue::NULL` is serialized to an empty value,
which the Seam API reads as null,
as described in [Setting a param to null](#setting-a-param-to-null).
A param that cannot be represented raises a `Seam\UnserializableParamError`.

The Seam API parses these params with the corresponding [parser].

[URLSearchParams]: https://developer.mozilla.org/en-US/docs/Web/API/URLSearchParams
[reference implementation]: https://github.com/seamapi/url-search-params-serializer
[parser]: https://github.com/seamapi/url-search-params-parser

#### Errors

Every exception the SDK raises implements `Seam\SeamException`, so it can be
caught as a group. An API error is a `Seam\HttpApiError` carrying
`getErrorCode()`, `getStatusCode()` and `getRequestId()`, with
`Seam\HttpUnauthorizedError` and `Seam\HttpInvalidInputError` as the two
specific cases worth catching on their own.

```php
use Seam\HttpApiError;
use Seam\HttpInvalidInputError;

try {
    $seam->devices->get(device_id: $device_id);
} catch (HttpInvalidInputError $error) {
    print_r($error->getValidationErrorMessages("device_id"));
} catch (HttpApiError $error) {
    print $error->getErrorCode();
}
```

A response that is not shaped like a Seam error, such as a gateway returning
HTML, raises the underlying Guzzle exception instead.

## Development and Testing

### Quickstart

Install [PHP](https://www.php.net/) 8.2 or later,
[Composer](https://getcomposer.org/) and [Node.js](https://nodejs.org/),
then run

```
$ git clone git@github.com:seamapi/php.git
$ cd php
$ composer install
$ npm install
```

Primary development tasks are defined as [Composer scripts](https://getcomposer.org/doc/articles/scripts.md)
in `composer.json` and available via `composer`.
View them with

```
$ composer run-script --list
```

| Task                   | Command            |
| ---------------------- | ------------------ |
| Run the tests          | `composer test`    |
| Lint and analyze types | `composer lint`    |
| Format                 | `npm run format`   |
| Build the package      | `composer build`   |
| Generate the SDK       | `npm run generate` |

Formatting is handled by [Prettier](https://prettier.io/) via
[@prettier/plugin-php](https://github.com/prettier/plugin-php),
so PHP, TypeScript, JSON, YAML and Markdown are all formatted by
`npm run format`.

### Source code

The [source code] is hosted on GitHub.
Clone the project with

```
$ git clone git@github.com:seamapi/php.git
```

[source code]: https://github.com/seamapi/php

### Running Tests

Run the full suite with

```
$ composer test
```

To run a specific test file, pass it as an argument

```
$ composer test -- tests/MyTest.php
```

PHPUnit is configured in `phpunit.xml.dist`.

Static analysis is handled by [Psalm](https://psalm.dev/), configured in
`psalm.xml` and run as part of `composer lint`. The generated sources under
`src/Resources` and `src/Routes` are excluded, since analyzing them would only
create pressure to change the generator.

### Requirements

This package supports PHP 8.2 and later.
Continuous integration exercises every supported version, PHP 8.2 through 8.5.

The test suite runs against [@seamapi/fake-seam-connect], which is started
automatically for each test, so `npm install` must have been run first.

[@seamapi/fake-seam-connect]: https://github.com/seamapi/fake-seam-connect

### Publishing

#### Automatic

New versions are released automatically from `main` by the
[Semantic Release](.github/workflows/semantic-release.yml) workflow,
which reads [Conventional Commits](https://www.conventionalcommits.org/)
and dispatches the [Version](.github/workflows/version.yml) workflow.

#### Manual

Run the [Version](.github/workflows/version.yml) workflow with the
version to cut.
It runs `npm version`, which bumps the `version` field in `package.json`,
injects that version into `Seam\Version`, creates a signed `v*`
git tag and pushes it.
Pushing the tag triggers the [Publish](.github/workflows/publish.yml)
workflow, and [Packagist](https://packagist.org/packages/seamapi/seam)
picks up the new tag from its GitHub webhook.

> Composer has no canonical place to store a package version, since Packagist
> derives it from the git tag, and it publishes the tag as-is with no build
> step in between.
> This repository therefore keeps the version in `package.json`, which is a
> development manifest that is not published, and injects it into the
> `Seam\Version::VERSION` constant used for the
> `seam-sdk-version` header.
>
> The injection runs from `version.ts`, wired to the `version` lifecycle
> script, which npm runs after the bump but before the commit, so the
> updated constant is part of the tagged commit.
> Never edit that constant by hand; a test asserts it matches `package.json`.

Development files are kept out of the published package with `export-ignore`
rules in `.gitattributes`, which `git archive` honours when GitHub builds the
archives Composer downloads as `dist`.

## GitHub Actions

_GitHub Actions should already be configured: this section is for reference only._

Publishing is handled by [Packagist], which reads new versions from the git
tags this repository pushes, so no registry token is needed.

[Packagist]: https://packagist.org/packages/seamapi/seam

### Secrets for Optional GitHub Actions

The version, format, generate, and semantic-release GitHub actions
require a user with write access to the repository.
Set these additional secrets to enable the action:

- `GH_TOKEN`: A personal access token for the user.
- `GIT_USER_NAME`: The GitHub user's real name.
- `GIT_USER_EMAIL`: The GitHub user's email.
- `GPG_PRIVATE_KEY`: The GitHub user's [GPG private key].
- `GPG_PASSPHRASE`: The GitHub user's GPG passphrase.

[GPG private key]: https://github.com/marketplace/actions/import-gpg#prerequisites

## Contributing

Please submit and comment on bug reports and feature requests.

To submit a patch:

1. Fork it (https://github.com/seamapi/php/fork).
2. Create your feature branch (`git checkout -b my-new-feature`).
3. Make changes.
4. Commit your changes (`git commit -am 'Add some feature'`).
5. Push to the branch (`git push origin my-new-feature`).
6. Create a new Pull Request.

## License

This PHP package is licensed under the MIT license.

## Warranty

This software is provided by the copyright holders and contributors "as is" and
any express or implied warranties, including, but not limited to, the implied
warranties of merchantability and fitness for a particular purpose are
disclaimed. In no event shall the copyright holder or contributors be liable for
any direct, indirect, incidental, special, exemplary, or consequential damages
(including, but not limited to, procurement of substitute goods or services;
loss of use, data, or profits; or business interruption) however caused and on
any theory of liability, whether in contract, strict liability, or tort
(including negligence or otherwise) arising in any way out of the use of this
software, even if advised of the possibility of such damage.
