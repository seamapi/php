<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use GuzzleHttp\Client;

final class DevicesTest extends TestCase
{
  public function testGetAndListDevices(): void
  {
    $seam = Fixture::getTestServer(true);
    $devices = $seam->devices->list();
    $this->assertTrue(count($devices) > 0);

    $device_ids = [$devices[0]->device_id];
    $devices = $seam->devices->list(device_ids: $device_ids);
    $this->assertTrue(count($devices) == 1);

    $connected_account = $seam->connected_accounts->list()[0];
    $devices = $seam->devices->list(connected_account_id: $connected_account->connected_account_id);
    $this->assertTrue(count($devices) > 0);

    $devices = $seam->devices->list(device_type: 'schlage_lock');
    $this->assertTrue(count($devices) > 0);

    $device_id = $devices[0]->device_id;
    $device = $seam->devices->get(device_id: $device_id);
    $this->assertTrue($device->device_id === $device_id);

    $device_name = $devices[0]->properties->name;
    $device = $seam->devices->get(name: $device_name);
    $this->assertTrue($device->properties->name === $device_name);

    $devices = $seam->devices->list(manufacturer: 'schlage');
    $this->assertTrue(count($devices) > 0);

    $manufacturer = $devices[0]->properties->manufacturer;
    $device = $seam->devices->get(name: $device_name);
    $this->assertTrue($device->properties->manufacturer === $manufacturer);

    $seam->devices->delete(device_id: $device_id);
    try {
      $seam->devices->get(device_id: $device_id);

      $this->fail('Expected the device to be deleted');
    } catch (Exception $exception) {
      $this->assertTrue(str_contains($exception->getMessage(), "device_not_found"));
    }
  }
}
