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

    $access_codes = $seam->access_codes->list(device_id: $device_id);
    $this->assertEmpty($access_codes);
    $this->assertIsArray($access_codes);

    $seam->access_codes->create(device_id: $device_id, code: "1235");

    $access_codes = $seam->access_codes->list(device_id: $device_id);
    $this->assertIsArray($access_codes);

    $access_code_id = $access_codes[0]->access_code_id;
    $access_code = $seam->access_codes->get(access_code_id: $access_code_id);
    $this->assertTrue($access_code->access_code_id === $access_code_id);

    $access_code = $seam->access_codes->get(device_id: $device_id, code: "1235");
    $this->assertTrue($access_code->code === "1235");

    $seam->access_codes->update(access_code_id: $access_code_id, code: "5678");
    $access_code = $seam->access_codes->get(access_code_id: $access_code_id);
    $this->assertTrue($access_code->code === "5678");

    $action_attempt = $seam->access_codes->delete(access_code_id: $access_code_id);
    $access_code = $seam->access_codes->get(access_code_id: $access_code_id);
    $this->assertEquals($access_code->status, "removing");
    $this->assertEquals($action_attempt->status, "success");

    $start_date = new DateTime("+2 months");
    $end_date = new DateTime("+3 months");

    $seam->access_codes->create(
      device_id: $device_id,
      code: "5678",
      starts_at: $start_date->format(DateTime::ATOM),
      ends_at: $end_date->format(DateTime::ATOM)
    );

    $access_code = $seam->access_codes->list(device_id: $device_id)[0];

    $formatted_starts_at = (new DateTime($access_code->starts_at))->format("Y-m-d");
    $formatted_ends_at = (new DateTime($access_code->ends_at))->format("Y-m-d");

    $this->assertTrue($formatted_starts_at === $start_date->format("Y-m-d"));
    $this->assertTrue($formatted_ends_at === $end_date->format("Y-m-d"));
  }
}
