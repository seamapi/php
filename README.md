# Seam PHP SDK

Control locks, lights and other internet of things devices with Seam's simple API.

Check out [the documentation](https://docs.seam.co) or some examples:

* (No Examples Yet)

## Usage

```php
$seam = new Seam\SeamClient('YOUR_API_KEY');

$seam->devices->list();
// returns array of devices

```

## Installation

## Development Setup

1. Install [composer](https://getcomposer.org/).
2. Run `composer install` in this directory
3. Run `composer exec phpunit tests`

> To run a specific test file, do `composer exec phpunit tests/MyTest.php`


### Running Tests

You'll need to export `SEAM_API_KEY` to a sandbox workspace API key.