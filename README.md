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

## Installation

Run `composer require seamapi/seam`

## Development Setup

1. Run `yarn install` to get prettier installed for formatting
2. Install [composer](https://getcomposer.org/).
3. Run `composer install` in this directory
4. Run `composer exec phpunit tests`

> To run a specific test file, do `composer exec phpunit tests/MyTest.php`

### Running Tests

You'll need to export `SEAM_API_KEY` to a sandbox workspace API key.
