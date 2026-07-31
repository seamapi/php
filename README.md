# Seam PHP SDK

Control locks, lights and other internet of things devices with Seam's simple API.

Check out [the documentation](https://docs.seam.co) or the usage below.

## Usage

```php
$seam = new Seam\SeamClient("YOUR_API_KEY");

# Create a Connect Webview to login to a provider
$connect_webview = $seam->connect_webviews->create(
    accepted_providers: ["august"]
);

print "Please Login at this url: " . $connect_webview->url;

# Poll until connect webview is completed
while (true) {
    $connect_webview = $seam->connect_webviews->get(
        $connect_webview->connect_webview_id
    );
    if ($connect_webview->status == "authorized") {
        break;
    } else {
        sleep(1);
    }
}

$connected_account = $seam->connected_accounts->get(
    $connect_webview->connected_account_id
);

print "Looks like you connected with " .
    json_encode($connected_account->user_identifier);

$devices = $seam->devices->list(
    connected_account_id: $connected_account->connected_account_id
);

print "You have " . count($devices) . " devices";

$device_id = $devices[0]->device_id;

# Lock a Door
$seam->locks->lock_door($device_id);

$updated_device = $seam->devices->get($device_id);
$updated_device->properties->locked; // true

# Unlock a Door
$seam->locks->unlock_door($device_id);
$updated_device->properties->locked; // false

# Create an access code on a device
$access_code = $seam->access_codes->create(
    device_id: $device_id,
    code: "1234",
    name: "Test Code"
);

# Check the status of an access code
$access_code->status; // 'setting' (it will go to 'set' when active on the device)

$seam->access_codes->delete($access_code->access_code_id);
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

## Installation

To install the latest version of the automatically generated SDK, run:

`composer require seamapi/seam`

If you want to install our previous handwritten version, run:

`composer require seamapi/seam:1.1`

## Development and Testing

### Quickstart

Install [PHP](https://www.php.net/) 8.0 or later,
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

| Task              | Command            |
| ----------------- | ------------------ |
| Run the tests     | `composer test`    |
| Lint              | `composer lint`    |
| Format            | `npm run format`   |
| Build the package | `composer build`   |
| Generate the SDK  | `npm run generate` |

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

### Requirements

This package supports PHP 8.0 and later.
Continuous integration exercises both ends of that range, PHP 8.0 and 8.5.

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
