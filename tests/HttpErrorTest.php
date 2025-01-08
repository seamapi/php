<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;

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
        } catch (\Seam\HttpInvalidInputError $e) {
            $this->assertEquals(400, $e->getStatusCode());
            $this->assertNotEmpty($e->getRequestId());
            $this->assertEquals("invalid_input", $e->getErrorCode());
            $this->assertEquals(
                ["Expected array, received number"],
                $e->getValidationErrorMessages("device_ids")
            );
        }
    }

    public function testUnauthorizedError(): void
    {
        $test_server = Fixture::getTestServer();
        $seam = new \Seam\SeamClient(
            "invalid_api_key",
            $test_server->client->getConfig("base_uri")->__toString()
        );

        try {
            $seam->devices->list();
            $this->fail("Expected UnauthorizedError");
        } catch (\Seam\HttpUnauthorizedError $e) {
            $this->assertNotEmpty($e->getRequestId());
        }
    }

    public function testApiError(): void
    {
        $seam = Fixture::getTestServer();

        try {
            $seam->devices->get(device_id: "nonexistent_device_id");
            $this->fail("Expected ApiError");
        } catch (\Seam\HttpApiError $e) {
            $this->assertEquals(404, $e->getStatusCode());
            $this->assertNotEmpty($e->getRequestId());
            $this->assertEquals("device_not_found", $e->getErrorCode());
        }
    }

    public function testNonSeamError(): void
    {
        $seam = Fixture::getTestServer();

        $seam = new \Seam\SeamClient(
            "seam_apikey1_token",
            "https://nonexistent.example.com"
        );

        try {
            $seam->devices->list();
            $this->fail("Expected GuzzleHttp ConnectException");
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $this->assertInstanceOf(
                \GuzzleHttp\Exception\ConnectException::class,
                $e
            );
            $this->assertStringContainsString(
                "Could not resolve host",
                $e->getMessage()
            );
        }
    }
}
