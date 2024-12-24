<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use Seam\Errors\Http\ApiError;
use Seam\Errors\Http\UnauthorizedError;
use Seam\Errors\Http\InvalidInputError;

final class HttpErrorTest extends TestCase
{
  public function testInvalidInputError(): void
  {
    $seam = Fixture::getTestServer();

    try {
      $device = $seam->devices->get();
      $seam->access_codes->create(device_id: $device->device_id, code: "123");
      $this->fail("Expected InvalidInputError");
    } catch (InvalidInputError $e) {
      $this->assertEquals(400, $e->statusCode);
      $this->assertNotEmpty($e->requestId);
      $this->assertEquals('invalid_input', $e->errorCode);
    }
  }

  public function testUnauthorizedError(): void
  {
    $test_server = Fixture::getTestServer();
    $seam = new \Seam\SeamClient(
      "invalid_api_key",
      $test_server->client->getConfig('base_uri')->__toString()
    );

    try {
      $seam->devices->list();
      $this->fail("Expected UnauthorizedError");
    } catch (UnauthorizedError $e) {
      $this->assertNotEmpty($e->requestId);
    }
  }

  public function testApiError(): void
  {
    $seam = Fixture::getTestServer();

    try {
      $seam->devices->get(device_id: "nonexistent_device_id");
      $this->fail("Expected ApiError");
    } catch (ApiError $e) {
      $this->assertEquals(404, $e->statusCode);
      $this->assertNotEmpty($e->requestId);
      $this->assertEquals('device_not_found', $e->errorCode);
    }
  }
}
