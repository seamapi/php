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
      $seam->request(
        "POST",
        "/devices/list",
        json: [
            "device_ids" => 1234,
        ]
    );
      $this->fail("Expected InvalidInputError");
    } catch (InvalidInputError $e) {
      $this->assertEquals(400, $e->statusCode);
      $this->assertNotEmpty($e->requestId);
      $this->assertEquals('invalid_input', $e->errorCode);
      $this->assertEquals(
        ['Expected array, received number'],
        $e->getValidationErrorMessages('device_ids')
      );
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

  public function testNonSeamError(): void
  {
    $seam = Fixture::getTestServer();

    $seam = new \Seam\SeamClient("seam_apikey1_token", "https://nonexistent.example.com");

    try {
      $seam->devices->list();
      $this->fail("Expected GuzzleHttp ConnectException");
    } catch (\GuzzleHttp\Exception\ConnectException $e) {
      $this->assertInstanceOf(\GuzzleHttp\Exception\ConnectException::class, $e);
      $this->assertStringContainsString("Could not resolve host", $e->getMessage());
    }
  }
}
