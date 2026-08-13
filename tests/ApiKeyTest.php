<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Seam\HttpUnauthorizedError;
use Seam\InvalidTokenError;
use Seam\Seam;
use Tests\Support\FakeSeamConnectTestCase;

final class ApiKeyTest extends FakeSeamConnectTestCase
{
    public function testFromApiKeyReturnsAnAuthorizedClient(): void
    {
        $seam = Seam::from_api_key(
            $this->seed["seam_apikey1_token"],
            endpoint: $this->endpoint,
        );

        $device = $seam->devices->get($this->seed["august_device_1"]);

        $this->assertSame($this->seed["august_device_1"], $device->device_id);
        $this->assertSame(
            $this->seed["seed_workspace_1"],
            $device->workspace_id,
        );
    }

    public function testConstructorReturnsAnAuthorizedClient(): void
    {
        $seam = new Seam(
            api_key: $this->seed["seam_apikey1_token"],
            endpoint: $this->endpoint,
        );

        $device = $seam->devices->get($this->seed["august_device_1"]);

        $this->assertSame($this->seed["august_device_1"], $device->device_id);
    }

    public function testInvalidApiKeyIsRejectedByTheServer(): void
    {
        $seam = new Seam(
            api_key: "seam_invalid_api_key",
            endpoint: $this->endpoint,
        );

        $this->expectException(HttpUnauthorizedError::class);

        $seam->devices->list();
    }

    #[DataProvider("unusableTokens")]
    public function testApiKeyFormatIsChecked(
        string $token,
        string $expected_message,
    ): void {
        $this->expectException(InvalidTokenError::class);
        $this->expectExceptionMessage($expected_message);

        new Seam(api_key: $token, endpoint: $this->endpoint);
    }

    public static function unusableTokens(): array
    {
        return [
            "client session token" => [
                "seam_cst_1234",
                "A Client Session Token cannot be used as an api_key",
            ],
            "jwt" => ["ey_some_jwt", "A JWT cannot be used as an api_key"],
            "access token" => [
                "seam_at_1234",
                "An Access Token cannot be used as an api_key",
            ],
            "publishable key" => [
                "seam_pk_1234",
                "A Publishable Key cannot be used as an api_key",
            ],
            "unknown format" => [
                "some-random-token",
                "Unknown or invalid api_key format",
            ],
        ];
    }
}
