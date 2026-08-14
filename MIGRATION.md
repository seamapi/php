# Migrating from seamapi/seam v3 to v4

This guide covers upgrading from `seamapi/seam` v3.x to v4 of the [Seam PHP SDK](https://github.com/seamapi/php).

Version 4 unifies this SDK with the Seam SDKs for Python, Ruby, and JavaScript: it adds personal access token authentication, webhook verification, explicit retries, client-side validation, and an explicit null sentinel. Endpoint methods keep their names, arguments, and return types — code that calls endpoints with named arguments is mostly unaffected. The breaking changes are concentrated in client construction, HTTP behavior, and the layout of the generated classes.

If you are upgrading from v2.x, apply the [v2 to v3 guide](#migrating-from-seamapiseam-v2-to-v3) first (or both guides together).

## Installation

```sh
composer require "seamapi/seam:^4"
```

## Summary of breaking changes

| Change                                                                                      | Affects you if...                                                                      |
| ------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------- |
| [PHP 8.2+ required](#php-82-or-later-is-required)                                           | You run PHP 8.0 or 8.1                                                                 |
| [`Seam\Seam` replaces `Seam\SeamClient`](#seamseam-replaces-seamseamclient)                 | You construct the client (everyone)                                                    |
| [`$seam->client` is the Guzzle client](#seam-client-is-the-guzzle-client)                   | You use `$seam->client`, `$seam->request()`, or the removed public properties          |
| [Requests are retried and time out sooner](#requests-are-retried-and-time-out-sooner)       | You depend on requests never being retried, or on the 60-second timeout                |
| [`poll_until_ready` is replaced](#poll_until_ready-is-replaced-by-wait_for_action_attempt)  | You call `$seam->action_attempts->poll_until_ready()` or rely on its 20 s/0.4 s timing |
| [Nested resource classes are namespaced](#nested-resource-classes-are-namespaced)           | You type-hint nested classes such as `Seam\Resources\DeviceProperties`                 |
| [Missing required parameters fail locally](#missing-required-parameters-fail-locally)       | You call endpoints with missing parameters and rely on the server's 400 response       |
| [Preferred HTTP methods and URL search params](#endpoints-use-preferred-http-methods)       | You inspect traffic in a proxy, mock server, or firewall rules                         |
| [Error handling refinements](#error-handling-refinements)                                   | You compare `getRequestId()` to `""`, or depend on 3xx responses passing through       |
| [Pagination metadata is a `Seam\Pagination`](#pagination-metadata-is-a-typed-object)        | You treat the paginator's metadata as a `stdClass`                                     |
| [`Seam\Version` replaces `Seam\Utils\PackageVersion`](#seamversion-replaces-packageversion) | You read the package version programmatically                                          |

## PHP 8.2 or later is required

Version 3 declared support for PHP 8.0. Version 4 requires PHP >= 8.2, since PHP 8.1 reached end of life in December 2025. The SDK also gains two dependencies: `caseyamcl/guzzle_retry_middleware` and `svix/svix`.

## `Seam\Seam` replaces `Seam\SeamClient`

The client class is renamed from `Seam\SeamClient` to `Seam\Seam`; there is no compatibility alias. The constructor takes named options, so `$endpoint` is no longer the second positional argument, and `$throw_http_errors` is removed — an API error always raises a Seam exception:

```php
// v3
$seam = new Seam\SeamClient("your-api-key", "https://example.com");

// v4
$seam = new Seam\Seam(api_key: "your-api-key", endpoint: "https://example.com");
```

Static factories are available as an alternative to the constructor: `Seam::from_api_key()`, `Seam::from_personal_access_token()`, and `Seam::from_client()`.

In v3, `$throw_http_errors = true` made Guzzle throw its own `RequestException` before the SDK could map the error. If you passed it, catch the Seam error classes instead — they are unchanged (see [Error handling refinements](#error-handling-refinements)).

## `$seam->client` is the Guzzle client

In v3, `$seam->client` was a bare `GuzzleHttp\Client`, and the SDK's error mapping lived in the separate `$seam->request()` helper. In v4, `$seam->client` is the fully configured Guzzle client the SDK itself uses — error mapping and retries are Guzzle middleware, and query params and JSON bodies are serialized per the Seam standards on the way out. Anything Guzzle can do is available on it directly, and it throws the same Seam exceptions the endpoint methods do.

`Seam::request()` is removed. Call the client instead and decode the PSR-7 response yourself:

```php
// v3: returns decoded JSON
$res = $seam->request("POST", "/devices/list", json: (object) []);

// v4: returns a PSR-7 response
$res = json_decode(
    $seam->client->request("GET", "/devices/list")->getBody()
);
```

The property is typed `GuzzleHttp\ClientInterface` rather than `GuzzleHttp\Client`, so update any type hints. Two other public members are removed with no replacement on the instance:

- `$seam->api_key` is gone.
- `$seam->ltsVersion` and the global `LTS_VERSION` constant are gone. Use the `Seam\Seam::LTS_VERSION` class constant.

To configure the underlying client, pass `guzzle_options` (merged into the Guzzle client's config), or pass a preconfigured client via the `client` option. A preconfigured client carries its own endpoint and authorization, so combining it with `api_key`, `endpoint`, or any other option that would configure one raises `Seam\InvalidOptionsError` instead of being silently ignored:

```php
$seam = new Seam\Seam(
    api_key: "your-api-key",
    guzzle_options: ["proxy" => "http://localhost:8125"],
);
```

## Requests are retried and time out sooner

Version 3 never retried a request and timed out after 60 seconds. Version 4 makes up to three attempts by default: the initial request and two retries. Retries are limited to `GET`, `HEAD`, `OPTIONS`, `PUT`, and `DELETE` requests that fail because of a transport error, timeout, HTTP 429 response, or HTTP 5xx response. `POST` and `PATCH` requests are never retried. Retries use exponential backoff with jitter, and a `Retry-After` header is honored when it is longer than the calculated backoff.

The timeout drops from 60 to 30 seconds, covers connecting as well as reading, and applies to each attempt rather than the whole sequence. Both behaviors are options:

```php
$seam = new Seam\Seam(
    retries: 0,     // Disable retries.
    timeout: 60.0,  // Restore the v3 timeout, in seconds.
);
```

Note the interaction with the new HTTP methods: because reads are now `GET`, they are retried by default, which they were not in v3 (as `POST`).

## `poll_until_ready` is replaced by `wait_for_action_attempt`

`$seam->action_attempts->poll_until_ready()` is removed. Endpoints that return an [action attempt](https://docs.seam.co/latest/core-concepts/action-attempts) still wait for it by default, but the waiting is configured through `wait_for_action_attempt`, which now also accepts a `timeout` and `polling_interval` (in seconds), per request or as a client-wide default:

```php
// v3
$seam->locks->unlock_door(device_id: $device_id, wait_for_action_attempt: true);
$seam->action_attempts->poll_until_ready($action_attempt_id, timeout: 30.0);

// v4
$seam->locks->unlock_door(
    device_id: $device_id,
    wait_for_action_attempt: ["timeout" => 30.0, "polling_interval" => 2.0],
);

$seam = new Seam\Seam(wait_for_action_attempt: false); // Client-wide default.
```

The default timing changes from a 20-second timeout with a 0.4-second polling interval to a 10-second timeout with a 1-second polling interval. Pass an explicit `timeout` if 10 seconds is too short for your devices. `Seam\ActionAttemptFailedError` and `Seam\ActionAttemptTimeoutError` are raised exactly as in v3.

## Nested resource classes are namespaced

Top-level resource classes are unchanged: `$seam->devices->get()` still returns a `Seam\Resources\Device`. The generated classes that type _nested_ properties move from resource-prefixed names in `Seam\Resources` to sub-namespaces mirroring the property path:

```php
// v3
use Seam\Resources\DeviceProperties;
use Seam\Resources\DeviceBattery;

// v4
use Seam\Resources\Device\Properties;
use Seam\Resources\Device\Properties\Battery;
```

This rename also fixes a class of bugs where two nested shapes competed for one name and the loser was silently dropped: for example, `$device->properties->battery->status` did not exist in v3 because the keypad's battery class won the name `DeviceBattery`. In v4 every nested shape has its own class, so fields that were missing on `device.properties.battery`, the climate preset ecobee metadata, and the phone session credential and entrance metadata are now present.

Property reads are unaffected — only explicit references to the nested class names need updating.

## Missing required parameters fail locally

In v3, every endpoint parameter defaulted to `null`, so a call missing a required parameter was sent to the server and failed with `Seam\HttpInvalidInputError` after a round trip. In v4, required parameters have no default, so PHP itself rejects the call with an `ArgumentCountError` (or an `Error` for a missing named argument). Endpoints that require _at least one_ of their parameters throw `InvalidArgumentException` when called with none:

```php
// v3: raises Seam\HttpInvalidInputError after a round trip to the server
// v4: raises ArgumentCountError locally
$seam->devices->get();

// v4: raises InvalidArgumentException("At least one parameter is required for /locks/get")
$seam->locks->get();
```

If you catch `HttpInvalidInputError` around calls that could be sent incomplete, also handle these local errors (or fix the call site).

Relatedly, call endpoint methods with [named arguments](https://www.php.net/manual/en/functions.arguments.php#functions.named-arguments). Parameter order is derived from the API definition and can change as endpoints gain parameters, so a positional call can silently start binding a value to the wrong parameter after an upgrade. Named arguments are stable.

## Endpoints use preferred HTTP methods

In v3, every endpoint was called with `POST` and a JSON body. In v4, endpoints use the HTTP method the Seam API prefers:

- Read endpoints (`get`, `list`, and friends) use `GET`, with parameters sent as URL search params serialized per [Seam's URL search params standard](https://github.com/seamapi/url-search-params-serializer) (with `_strict=true` appended).
- Update endpoints use `PATCH` or `PUT`.
- Delete endpoints use `DELETE`.
- Create and action endpoints (`create`, `lock_door`, etc.) remain `POST`.

Method signatures, arguments, and return values are unchanged — this only matters if something outside your code observes the HTTP traffic: proxy or firewall rules that allowlist methods, request logging, or test mocks registered against `POST` routes. The SDK also no longer sets a `User-Agent` of its own (v3 sent `Seam PHP Client <version>`): it identifies itself with the `seam-sdk-name` and `seam-sdk-version` headers, and a `User-Agent` you set through `guzzle_options` is sent unchanged.

If you call the Seam API with your own HTTP client, the serializer is available as `Seam\UrlSearchParamsSerializer::serialize()`.

## Error handling refinements

The exception classes keep their v3 names and stay in the `Seam` namespace, so existing catch blocks keep working: `Seam\HttpApiError`, `Seam\HttpUnauthorizedError`, `Seam\HttpInvalidInputError`, `Seam\ActionAttemptError`, `Seam\ActionAttemptFailedError`, and `Seam\ActionAttemptTimeoutError`. What changes:

- Every SDK exception now implements the `Seam\SeamException` interface, so they can be caught as a group.
- `getRequestId()` returns `null` instead of `""` when the response carries no request id, and is typed `?string`.
- A response in the 3xx range is no longer treated as successful: redirects are followed by Guzzle, and an unfollowed redirect is an error instead of an empty result.
- An error response not shaped like a Seam error — a gateway returning HTML, for example — raises the underlying Guzzle exception with the real request attached, instead of a fabricated one.
- Malformed JSON in a response raises instead of silently decoding to `null`.

## Pagination metadata is a typed object

`firstPage()` and `nextPage()` still return a `[$items, $pagination]` pair, but the metadata is a readonly `Seam\Pagination` object rather than a raw `stdClass`. Property reads (`has_next_page`, `next_page_cursor`, `next_page_url`) are unchanged, so typical pagination loops, `flatten()`, and `flattenToArray()` work as before. What breaks is treating it as a `stdClass`: casting, mutation, or `json_encode` round-trips of the raw envelope.

The paginator also validates its input now: using it with an endpoint that returns no pagination metadata throws `InvalidArgumentException`, and an `on_response` callback you pass in the params is chained rather than silently replaced.

## `Seam\Version` replaces `PackageVersion`

`Seam\Utils\PackageVersion::get()` is renamed to `Seam\Version::get()`, and the version string is also available as the `Seam\Version::VERSION` constant.

## New in v4

These are additions, not breaking changes, but they are worth adopting while you migrate.

### Personal access tokens and `SeamWithoutWorkspace`

The client can now authenticate with a personal access token scoped to a workspace, and the new `Seam\SeamWithoutWorkspace` client reaches the endpoints that take no workspace in scope, such as listing your workspaces:

```php
$seam = Seam\Seam::from_personal_access_token(
    "your-personal-access-token",
    "your-workspace-id",
);

$seam = new Seam\SeamWithoutWorkspace(
    personal_access_token: "your-personal-access-token",
);
$workspaces = $seam->workspaces->list();
```

Tokens are validated on construction: a client session token, JWT, or publishable key passed as an API key raises `Seam\InvalidTokenError` with a message naming the mistake.

### Authentication from the environment

`SEAM_API_KEY` was already read in v3. Version 4 also reads `SEAM_PERSONAL_ACCESS_TOKEN` and `SEAM_WORKSPACE_ID` when no explicit credentials are passed, so `new Seam\Seam()` works under either authentication method. Setting both `SEAM_API_KEY` and `SEAM_PERSONAL_ACCESS_TOKEN` is an error. The endpoint may be set with `SEAM_ENDPOINT`.

### Webhook verification

`Seam\SeamWebhook` verifies incoming webhooks and returns a typed `Seam\Resources\Event`:

```php
$webhook = new Seam\SeamWebhook($_ENV["SEAM_WEBHOOK_SECRET"]);
$event = $webhook->verify($payload, $headers);
```

### Explicit null with `NullValue::NULL`

The Seam API distinguishes an omitted parameter from one explicitly set to null: in an update request, an omitted parameter leaves the current value unchanged, while a null parameter unsets it. PHP's `null` keeps the safe v3 meaning of "omit"; to send an explicit null, pass the `Seam\NullValue::NULL` sentinel:

```php
use Seam\NullValue;

// Leaves the name unchanged (same as v3).
$seam->devices->update(device_id: $device_id, name: null);

// Unsets the name (new in v4).
$seam->devices->update(device_id: $device_id, name: NullValue::NULL);
```

The other Seam SDKs spell the sentinel `NULL` and its type `Null`, but both names are reserved in PHP, so the type and the value live on one enum. Only parameters the Seam API documents as nullable are typed to accept the sentinel (e.g. `string|NullValue|null`), so passing it anywhere else fails with a `TypeError`.

## Migration checklist

1. Upgrade your runtime to PHP 8.2 or later.
2. Update the dependency: `composer require "seamapi/seam:^4"`.
3. Rename `Seam\SeamClient` to `Seam\Seam` and pass constructor options by name; drop `$throw_http_errors`.
4. Replace `$seam->request()` with `$seam->client->request()`, and `$seam->api_key` / `LTS_VERSION` / `$seam->ltsVersion` with your own configuration or `Seam\Seam::LTS_VERSION`.
5. Replace `poll_until_ready()` with `wait_for_action_attempt`, and review the new 10 s/1 s defaults.
6. Review the new retry policy and 30-second timeout; pass `retries: 0` or `timeout: 60.0` to keep v3 behavior.
7. Update type hints on nested resource classes (`Seam\Resources\DeviceProperties` → `Seam\Resources\Device\Properties`) and on `$seam->client` (`ClientInterface`).
8. Switch endpoint calls to named arguments, and handle `ArgumentCountError`/`InvalidArgumentException` where calls might be missing parameters.
9. If proxies, firewalls, or test mocks assume all requests are `POST`, update them for `GET`/`PATCH`/`PUT`/`DELETE`.
10. Rename `Seam\Utils\PackageVersion` to `Seam\Version`.
11. Optionally, adopt personal access tokens, `SeamWebhook`, and `NullValue::NULL`.

# Migrating from seamapi/seam v2 to v3

This guide covers upgrading from `seamapi/seam` v2.x to v3 of the [Seam PHP SDK](https://github.com/seamapi/php).

Version 3 regenerates the SDK from Seam's API blueprint, the same source of truth used by the SDKs for other languages. It is a much smaller upgrade than v4: client construction, authentication, endpoint methods, error handling, and pagination are all unchanged. The breaking changes are in the generated classes — their namespaces, constructors, and two method signatures.

## Installation

```sh
composer require "seamapi/seam:^3"
```

## Summary of breaking changes

| Change                                                                                                      | Affects you if...                                                               |
| ----------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------- |
| [Two `get` methods reorder their parameters](#two-get-methods-reorder-their-parameters)                     | You call `$seam->events->get()` or `$seam->acs->users->get()` positionally      |
| [Resource classes move to `Seam\Resources`](#resource-classes-move-to-seamresources)                        | You type-hint or import `Seam\Objects\...` classes                              |
| [Route clients move to `Seam\Routes`](#route-clients-move-to-seamroutes)                                    | You type-hint route clients such as `Seam\DevicesClient`                        |
| [`ActionAttempt->result` is typed](#actionattempt-result-is-typed)                                          | You read fields off an action attempt's raw `result`                            |
| [Resource constructors are alphabetical and nullable](#resource-constructors-are-alphabetical-and-nullable) | You construct resource objects yourself, or rely on non-nullable property types |
| [Undocumented resource classes are removed](#undocumented-resource-classes-are-removed)                     | You reference classes such as `Seam\Objects\PhoneSession`                       |

## Two `get` methods reorder their parameters

The primary resource ID is now the first parameter of every `get` method. Two endpoints change as a result, and a positional call silently sends the old first argument as the wrong parameter:

- `$seam->events->get()`: `$event_id` is now first (was `$device_id`).
- `$seam->acs->users->get()`: `$acs_user_id` is now first (was `$acs_system_id`).

```php
// v2
$event = $seam->events->get($device_id);

// v3
$event = $seam->events->get(device_id: $device_id);
```

Since parameter order follows the API definition and can change again, prefer named arguments for every endpoint call, not just these two.

## Resource classes move to `Seam\Resources`

As of v3.3.0, the generated resource classes live in the `Seam\Resources` namespace instead of `Seam\Objects`. The class names and shapes are unchanged:

```php
// v2
use Seam\Objects\Device;

// v3
use Seam\Resources\Device;
```

## Route clients move to `Seam\Routes`

As of v3.5.0, the route clients live in the `Seam\Routes` namespace instead of `Seam`:

```php
// v2
function listLocks(Seam\LocksClient $locks): array { ... }

// v3
function listLocks(Seam\Routes\LocksClient $locks): array { ... }
```

`$seam->devices`, `$seam->locks`, and the rest are unaffected — only explicit references to the client class names need updating. `Seam\SeamClient` itself, the exception classes, and `Seam\Paginator` stay where they were.

## `ActionAttempt->result` is typed

`ActionAttempt->result` is now a typed `ActionAttemptResult|null` instead of raw decoded JSON. Fields the API spec types — for example `acs_credential_on_encoder` — are still available as properties. Fields the spec defines no type for, such as `result->access_code` and `result->noise_threshold`, are no longer present; fetch the resource from its own endpoint instead.

## Resource constructors are alphabetical and nullable

Resource object constructor parameters are now ordered alphabetically and uniformly nullable, and the matching properties are uniformly nullable too. Objects returned by the SDK are unaffected. What breaks:

- Code constructing resource objects positionally binds values to the wrong parameters. Construct with named arguments or `from_json()`.
- Static analysis that relied on non-nullable property types (for example `$device->device_id` being `string`) now sees `string|null`.

## Undocumented resource classes are removed

Classes generated for resources absent from Seam's public API documentation — for example `Seam\Objects\PhoneSession`, `Seam\Objects\Customer`, and the `Seam\Objects\UnmanagedAcs...` nested classes — are no longer generated. The endpoint methods themselves are unchanged. If you referenced one of these classes, the data is still in the API response; the SDK just no longer ships a class for it.

## Migration checklist

1. Update the dependency: `composer require "seamapi/seam:^3"`.
2. Switch positional endpoint calls to named arguments — at minimum, fix `$seam->events->get()` and `$seam->acs->users->get()`.
3. Rename `Seam\Objects\...` imports and type hints to `Seam\Resources\...`.
4. Rename route client type hints from `Seam\...Client` to `Seam\Routes\...Client`.
5. Update code reading untyped `ActionAttempt->result` fields.
6. Construct resource objects with named arguments, and treat resource properties as nullable.
