<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use GuzzleHttp\Client;
use Seam\Seam;


final class TestDevices extends TestCase
{
  public function testListDevices(): void
  {
    $seam = Fixture::getTestServer();
    $devices = $seam->devices->list();
    print(json_encode($devices));
    $this->assertTrue(true);
  }
}
