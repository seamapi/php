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
$seam->locks->unlock_door($lock->device_id);
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
    $seam->locks->unlock_door($device_id);
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

$action_attempt = $seam->locks->unlock_door($device_id);
$action_attempt->status; // "pending"
```

or for a single request:

```php
$action_attempt = $seam->locks->unlock_door(
    $device_id,
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
    $device_id,
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

### Interacting with Multiple Workspaces

Some endpoints are not scoped to a workspace. Use `SeamMultiWorkspace` with a
personal access token to reach them.

```php
$seam = Seam\SeamMultiWorkspace::from_personal_access_token(
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
        "timeout" => 30,
        "headers" => ["X-Custom-Header" => "value"],
        "proxy" => "http://localhost:8125",
    ]
);
```

> [!NOTE]
> Unlike the other Seam SDKs, this one sets a default request timeout of 60
> seconds so a hung connection eventually fails. Pass `timeout` to change it,
> or `0` to disable it.

#### Retries

Failed requests are retried twice by default, with exponential backoff.

A request that never reached the server, e.g. a connection failure, is always
retried. A request that did reach the server is only retried on a retryable
status when the HTTP method is idempotent. Every Seam endpoint is a `POST`, so
retrying one that the server may already have processed could duplicate a
write. The other Seam SDKs make the same trade.

```php
// Retry more times
$seam = new Seam\Seam(retries: 5);

// Turn retries off
$seam = new Seam\Seam(retries: 0);
```

#### Overriding the client

Pass an already configured Guzzle client. It carries its own endpoint and
authorization, so no other authentication option may be given alongside it.

```php
$client = new GuzzleHttp\Client([
    "base_uri" => "https://connect.getseam.com",
    "headers" => ["authorization" => "Bearer " . $api_key],
]);

$seam = Seam\Seam::from_client($client);
```

#### Errors

Every exception the SDK raises implements `Seam\SeamException`.

| Error                       | Raised when                                        |
| --------------------------- | -------------------------------------------------- |
| `HttpApiError`              | The API returned an error response.                |
| `HttpUnauthorizedError`     | The credentials were rejected.                     |
| `HttpInvalidInputError`     | The request parameters were rejected.              |
| `InvalidOptionsError`       | The client options are incomplete or incompatible. |
| `InvalidTokenError`         | The token is of the wrong kind or format.          |
| `ActionAttemptFailedError`  | An action attempt finished in the error state.     |
| `ActionAttemptTimeoutError` | An action attempt did not finish in time.          |

An error response that is not shaped like a Seam error, e.g. a gateway
returning HTML, surfaces as the underlying `GuzzleHttp\Exception\
BadResponseException` instead. Transport failures surface as the
corresponding Guzzle exception.

```php
use Seam\HttpApiError;
use Seam\HttpInvalidInputError;

try {
    $seam->devices->get($device_id);
} catch (HttpInvalidInputError $error) {
    print_r($error->getValidationErrorMessages("device_id"));
} catch (HttpApiError $error) {
    print $error->getErrorCode();
    print $error->getStatusCode();
    print $error->getRequestId();
}
```

## Upgrading from 3.x

Version 4 brings this SDK in line with the Seam SDKs for other languages.

- PHP 8.2 or later is now required. PHP 8.1 reached end of life in
  December 2025.
- The client class is `Seam\Seam`. `Seam\SeamClient` still works as a
  deprecated alias.
- The constructor takes named options. `$endpoint` is no longer the second
  positional argument, and `$throw_http_errors` is gone; API errors always
  raise a Seam exception.
- The exception classes keep their `Seam\` namespace. They now all implement
  a shared `Seam\SeamException` interface, and `Seam\InvalidOptionsError` and
  `Seam\InvalidTokenError` are new.
- `$seam->action_attempts->poll_until_ready()` was removed. Use
  `wait_for_action_attempt`, which now also accepts a `timeout` and
  `polling_interval`. The defaults changed from 20s/0.4s to 10s/1s.
- `$seam->client` is a `Seam\Http\SeamHttpClient`. The Guzzle client is
  available with `$seam->client->get_client()`.
- The `$seam->api_key` property and the global `LTS_VERSION` constant were
  removed. Use `Seam\Seam::LTS_VERSION`.
- Responses in the 3xx range are no longer treated as successful.
- Requests are now retried; see [Retries](#retries).
- Pagination metadata is a `Seam\Pagination` object rather than a
  `stdClass`.

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
