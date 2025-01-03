<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;

final class LocksTest extends TestCase
{
    public function testGetAndListLocksUnlockAndLock(): void
    {
        $seam = Fixture::getTestServer();
        $locks = $seam->locks->list();
        $this->assertIsArray($locks);

        $device_id = $locks[0]->device_id;
        $lock = $seam->locks->get(device_id: $device_id);
        $this->assertTrue($lock->device_id === $device_id);

        $lock_name = $lock->properties->name;
        $lock = $seam->locks->get(name: $lock_name);
        $this->assertTrue($lock->properties->name === $lock_name);

        $connect_webview = $seam->connect_webviews->list()[0];
        $locks = $seam->locks->list(
            connect_webview_id: $connect_webview->connect_webview_id
        );
        $this->assertTrue(count($locks) > 0);

        // Unlock door and check properties.locked
        $seam->locks->lock_door($lock->device_id);
        $lock = $seam->locks->get(device_id: $device_id);
        $this->assertTrue($lock->properties->locked);

        $seam->locks->unlock_door($lock->device_id);

        $lock = $seam->locks->get(device_id: $device_id);
        $this->assertFalse($lock->properties->locked);
    }
}
