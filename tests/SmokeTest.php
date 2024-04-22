<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;

final class SmokeTest extends TestCase
{
    public function testGetDevices(): void
    {
        $seam = Fixture::getTestServer();

        $devices_response = $seam->devices->list();
        $this->assertIsString($devices_response[0]->device_id);
    }
    public function testCreateAccessCode(): void
    {
        $seam = Fixture::getTestServer();

        $access_code = $seam->access_codes->create(
            device_id: "august_device_1",
            code: "1234"
        );

        $this->assertTrue($access_code->status === "setting");
        $this->assertTrue($access_code->code === "1234");
    }
}
