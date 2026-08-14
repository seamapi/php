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

#### Setting a param to null

The Seam API distinguishes an omitted param from a param explicitly set to
null: in an update request, an omitted param leaves the current value
unchanged, while a null param unsets it. PHP has a single absence value, so
the SDK spells the two states differently:

- `null`, or simply omitting the param, means **omit**: the param is not
  sent at all.
- `Seam\NullValue::NULL` means **send null**: the value is unset.

Since unsetting a value cannot be undone, it is never the default and is
always spelled explicitly.

```php
use Seam\NullValue;

// Unset the device name.
$seam->devices->update(device_id: $device_id, name: NullValue::NULL);

// Leave the device name unchanged.
$seam->devices->update(device_id: $device_id, name: null);
```

Only params the API documents as nullable accept the sentinel: a nullable
param is typed `string|NullValue|null`, while a merely optional one is typed
`?string` and rejects it. The sentinel works on every route, whether the
request is sent as a query string (serialized as `name=`) or as a JSON body
(serialized as `null`).

#### URL search params serialization

Requests with query params follow the [Seam URL search params serialization
standard](https://github.com/seamapi/url-search-params-serializer): nested
objects join keys with dots (`{"page": {"size": 10}}` becomes
`page.size=10`), arrays repeat the name (`ids=a&ids=b`), an empty array
serializes to an empty value (`ids=`), and values are encoded and sorted
exactly as JavaScript's `URLSearchParams` would. Servers can read such
query strings with
[`@seamapi/url-search-params-parser`](https://github.com/seamapi/url-search-params-parser).

The serializer is exported for callers building requests with their own
HTTP client. Use `StrictUrlSearchParamsSerializer` when calling the Seam
API: it adds `_strict=true` to any non-empty query, which tells the API to
use strict, schema-aware parsing, and is what the SDK's own requests use. A
query with no serializable params remains empty.
`UrlSearchParamsSerializer` is the same serialization without the flag — a
pure implementation of the standard.

```php
use Seam\StrictUrlSearchParamsSerializer;

$query = StrictUrlSearchParamsSerializer::serialize([
    "device_ids" => ["device1", "device2"],
    "custom_metadata_has" => ["tag" => "front"],
    "limit" => 20,
]);
// => 'custom_metadata_has.tag=front&device_ids=device1&device_ids=device2&limit=20&_strict=true'
```

A param that cannot be represented in the standard, such as `NAN` or a key
containing a dot, raises `Seam\UnserializableParamError` before any request
is sent.

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

## Upgrading from 3.x

- PHP 8.2 or later is required. PHP 8.1 reached end of life in December 2025.
- The client class is `Seam\Seam`, replacing `Seam\SeamClient`.
- The constructor takes named options, so `$endpoint` is no longer the second
  positional argument, and `$throw_http_errors` is gone: an API error always
  raises.
- The exception classes stay in the `Seam\` namespace and now share a
  `Seam\SeamException` interface.
- `$seam->action_attempts->poll_until_ready()` is gone. Use
  `wait_for_action_attempt`, which also takes a `timeout` and
  `polling_interval`.
- `$seam->client` is the Guzzle client, and `$seam->api_key` and the global
  `LTS_VERSION` constant are gone. Use `Seam\Seam::LTS_VERSION`.
- A response in the 3xx range is no longer treated as successful.
- Requests time out after 30 seconds and are retried twice.
- Pagination metadata is a `Seam\Pagination` rather than a `stdClass`.

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
injects that version into `Seam\Utils\PackageVersion`, creates a signed `v*`
git tag and pushes it.
Pushing the tag triggers the [Publish](.github/workflows/publish.yml)
workflow, and [Packagist](https://packagist.org/packages/seamapi/seam)
picks up the new tag from its GitHub webhook.

> Composer has no canonical place to store a package version, since Packagist
> derives it from the git tag, and it publishes the tag as-is with no build
> step in between.
> This repository therefore keeps the version in `package.json`, which is a
> development manifest that is not published, and injects it into the
> `Seam\Utils\PackageVersion::VERSION` constant used for the
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
