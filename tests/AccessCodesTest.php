<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientErrorResponseException as ClientErrorResponseException;

final class AccessCodesTest extends TestCase
{
  public function testAccessCodes(): void
  {
    $seam = Fixture::getTestServer(true);

    $device_id = $seam->devices->list()[0]->device_id;
    $seam->access_codes->create(device_id: $device_id, code: "1234");

    $access_codes = $seam->access_codes->list(device_id: $device_id);
    $this->assertIsArray($access_codes);

    $access_code_id = $access_codes[0]->access_code_id;
    print($access_code_id);
    $access_code = $seam->access_codes->get(access_code_id: $access_code_id);
    $this->assertTrue($access_code->access_code_id === $access_code_id);

    $access_code = $seam->access_codes->get(device_id: $device_id, code: "1234");
    $this->assertTrue($access_code->code === "1234");

    $seam->access_codes->update(access_code_id: $access_code_id, code: "5678");
    $access_code = $seam->access_codes->get(access_code_id: $access_code_id);
    $this->assertTrue($access_code->code === "5678");

    $action_attempt = $seam->access_codes->delete(access_code_id: $access_code_id);
    $access_code = $seam->access_codes->get(access_code_id: $access_code_id);
    $this->assertEquals($access_code->status, "removing");
    $this->assertEquals($action_attempt->status, "success");
  }
}
