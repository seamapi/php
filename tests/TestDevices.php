<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use GuzzleHttp\Client;


final class TestDevices extends TestCase
{
  public function testListDevices(): void
  {
    $seam = Fixture::getTestServer(true);
    $devices = $seam->devices->list();
    print(json_encode($devices));
    $this->assertIsArray($devices);
  }
}
