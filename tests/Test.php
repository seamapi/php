<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Seam\Seam;


final class Test extends TestCase
{
  public function testOnePlusOne(): void
  {
    $this->assertEquals(2, 1 + 1);
  }

  public function testListDevices(): void
  {
    // $client = new Client([
    //   'base_uri' => 'https://connect.getseam.com',
    //   'timeout'  => 2.0,
    // ]);

    // $devices_res = $client->request("GET", "https://connect.getseam.com/devices/list", [
    //   'headers'  => ['Authorization' => 'Bearer ' . getenv('SEAM_API_KEY')],
    // ]);

    // print($devices_res->getBody());

    // $this->assertTrue(true);

    $client = new Seam(getenv('SEAM_API_KEY'));
    $devices = $client->devices->list();
    print(json_encode($devices));
    $this->assertTrue(true);
  }
}
