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

    $connected_account = $seam->connected_accounts->list()[0];
    $devices = $seam->devices->list(connected_account_id: $connected_account->connected_account_id);
    $this->assertTrue(count($devices) > 0);

    $devices = $seam->devices->list(device_type: 'august_lock');
    $this->assertTrue(count($devices) > 0);

    $device_id = $devices[0]->device_id;
    $device = $seam->devices->get(device_id: $device_id);
    $this->assertTrue($device->device_id === $device_id);

    $device_name = $devices[0]->properties->name;
    $device = $seam->devices->get(name: $device_name);
    $this->assertTrue($device->properties->name === $device_name);
  }
}
