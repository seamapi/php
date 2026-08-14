<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Seam\Http\ClientFactory;
use Seam\Seam;
use Seam\Version;
use Tests\Support\RecordingClient;

/**
 * The fake does not echo the request back, so the recording client is used
 * here to assert on what the SDK actually sends.
 */
final class HeadersTest extends TestCase
{
    public function testSendsDefaultHeaders(): void
    {
        $recorder = new RecordingClient([
            RecordingClient::json(200, ["device" => ["device_id" => "d1"]]),
        ]);

        $seam = Seam::from_api_key(
            "seam_apikey_token",
            endpoint: "https://example.com",
            guzzle_options: $recorder->guzzle_options(),
        );

        $device = $seam->devices->get("d1");

        $this->assertSame("d1", $device->device_id);

        $request = $recorder->request();

        $this->assertSame("/devices/get", $request->getUri()->getPath());
        $this->assertSame(
            "device_id=d1&_strict=true",
            $request->getUri()->getQuery(),
        );

        $this->assertSame(
            "Bearer seam_apikey_token",
            $request->getHeaderLine("authorization"),
        );
        $this->assertSame(
            "seamapi/php",
            $request->getHeaderLine("seam-sdk-name"),
        );
        $this->assertSame(
            Version::get(),
            $request->getHeaderLine("seam-sdk-version"),
        );
        $this->assertSame(
            ClientFactory::LTS_VERSION,
            $request->getHeaderLine("seam-lts-version"),
        );
        $this->assertSame(
            "seam-php/" . Version::get(),
            $request->getHeaderLine("User-Agent"),
        );
    }

    public function testSendsWorkspaceHeaderWithAPersonalAccessToken(): void
    {
        $recorder = new RecordingClient([
            RecordingClient::json(200, ["device" => ["device_id" => "d1"]]),
        ]);

        $seam = Seam::from_personal_access_token(
            "seam_at_token",
            "workspace-1",
            endpoint: "https://example.com",
            guzzle_options: $recorder->guzzle_options(),
        );

        $seam->devices->get("d1");

        $request = $recorder->request();

        $this->assertSame(
            "Bearer seam_at_token",
            $request->getHeaderLine("authorization"),
        );
        $this->assertSame(
            "workspace-1",
            $request->getHeaderLine("seam-workspace"),
        );
    }

    public function testCustomHeadersAreSentAlongsideTheSdkHeaders(): void
    {
        $recorder = new RecordingClient([
            RecordingClient::json(200, ["device" => ["device_id" => "d1"]]),
        ]);

        $seam = Seam::from_api_key(
            "seam_apikey_token",
            endpoint: "https://example.com",
            guzzle_options: array_merge($recorder->guzzle_options(), [
                "headers" => ["Custom-Header" => "Test-Value"],
            ]),
        );

        $seam->devices->get("d1");

        $request = $recorder->request();

        $this->assertSame(
            "Test-Value",
            $request->getHeaderLine("Custom-Header"),
        );
        $this->assertSame(
            "seamapi/php",
            $request->getHeaderLine("seam-sdk-name"),
        );
    }

    /**
     * The SDK headers identify the SDK, so a caller cannot displace them.
     */
    public function testSdkHeadersCannotBeOverridden(): void
    {
        $recorder = new RecordingClient([
            RecordingClient::json(200, ["device" => ["device_id" => "d1"]]),
        ]);

        $seam = Seam::from_api_key(
            "seam_apikey_token",
            endpoint: "https://example.com",
            guzzle_options: array_merge($recorder->guzzle_options(), [
                "headers" => ["seam-sdk-name" => "not-the-sdk"],
            ]),
        );

        $seam->devices->get("d1");

        $this->assertSame(
            "seamapi/php",
            $recorder->request()->getHeaderLine("seam-sdk-name"),
        );
    }
}
