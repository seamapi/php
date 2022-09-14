<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use GuzzleHttp\Client;


final class TestLocks extends TestCase
{
  public function testGetAndListLocksUnlockAndLock(): void
  {
    $seam = Fixture::getTestServer(true);
    $locks = $seam->locks->list();
    $this->assertIsArray($locks);

    $device_id = $locks[0]->device_id;
    $lock = $seam->locks->get(device_id: $device_id);
    $this->assertTrue($lock->device_id === $device_id);

    // Unlock door and check properties.locked
    $seam->locks->lock_door($lock->device_id);
    $lock = $seam->locks->get(device_id: $device_id);
    $this->assertTrue($lock->properties->locked);


    $seam->locks->unlock_door($lock->device_id);
    sleep(10); // wait for server to update
    $lock = $seam->locks->get(device_id: $device_id);
    $this->assertFalse($lock->properties->locked);
  }
}
