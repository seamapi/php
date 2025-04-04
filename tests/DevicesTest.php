<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;

final class DevicesTest extends TestCase
{
    public function testGetAndListDevices(): void
    {
        $seam = Fixture::getTestServer();
        $devices = $seam->devices->list();
        $this->assertTrue(count($devices) > 0);

        $device_ids = [$devices[0]->device_id];
        $devices = $seam->devices->list(device_ids: $device_ids);
        $this->assertTrue(count($devices) == 1);

        $connected_account = $seam->connected_accounts->list()[0];
        $devices = $seam->devices->list(
            connected_account_id: $connected_account->connected_account_id
        );
        $this->assertTrue(count($devices) > 0);

        $devices = $seam->devices->list(device_type: "august_lock");
        $this->assertTrue(count($devices) > 0);

        $device_id = $devices[0]->device_id;
        $device = $seam->devices->get(device_id: $device_id);
        $this->assertTrue($device->device_id === $device_id);

        $device_name = $devices[0]->properties->name;
        $device = $seam->devices->get(name: $device_name);
        $this->assertTrue($device->properties->name === $device_name);

        $devices = $seam->devices->list(manufacturer: "august");
        $this->assertTrue(count($devices) > 0);

        $manufacturer = $devices[0]->properties->manufacturer;
        $device = $seam->devices->get(name: $device_name);
        $this->assertTrue($device->properties->manufacturer === $manufacturer);

        $stable_device_providers = $seam->devices->list_device_providers(
            provider_category: "stable"
        );
        $this->assertTrue(count($stable_device_providers) > 0);
    }

    public function testUnmanagedDevices(): void
    {
        $seam = Fixture::getTestServer();

        $devices = $seam->devices->list();
        $this->assertTrue(count($devices) > 0);

        $unmanaged_devices = $seam->devices->unmanaged->list();
        $this->assertTrue(count($unmanaged_devices) == 0);

        $device_id = $devices[0]->device_id;
        $device_name = $devices[0]->properties->name;

        $seam->devices->update(device_id: $device_id, is_managed: false);
        $unmanaged_devices = $seam->devices->unmanaged->list();
        $this->assertTrue(count($unmanaged_devices) == 1);

        $unmanaged_device = $seam->devices->unmanaged->get(
            device_id: $device_id
        );
        $this->assertTrue($unmanaged_device->device_id === $device_id);
        $unmanaged_device = $seam->devices->unmanaged->get(name: $device_name);
        $this->assertTrue($unmanaged_device->properties->name === $device_name);

        $connected_account = $seam->connected_accounts->list()[0];
        $devices = $seam->devices->unmanaged->list(
            connected_account_id: $connected_account->connected_account_id
        );
        $this->assertTrue(count($devices) > 0);
        $devices = $seam->devices->unmanaged->list(
            connected_account_ids: [$connected_account->connected_account_id]
        );
        $this->assertTrue(count($devices) > 0);

        $devices = $seam->devices->unmanaged->list(device_type: "august_lock");
        $this->assertTrue(count($devices) > 0);
        $devices = $seam->devices->unmanaged->list(
            device_types: ["august_lock"]
        );
        $this->assertTrue(count($devices) > 0);

        $devices = $seam->devices->unmanaged->list(manufacturer: "august");
        $this->assertTrue(count($devices) > 0);

        $seam->devices->unmanaged->update(
            device_id: $device_id,
            is_managed: true
        );
        $unmanaged_devices = $seam->devices->unmanaged->list();
        $this->assertTrue(count($unmanaged_devices) == 0);
    }
}
