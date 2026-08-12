<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Seam\Http\Body;
use Seam\Seam;
use Tests\Support\RecordingClient;

/**
 * The recording client is used here because counting attempts is the point,
 * and the fake keeps a simulated outage in place for every request.
 */
final class RetryTest extends TestCase
{
    private const API_KEY = "seam_apikey_token";

    private static function service_unavailable(): \GuzzleHttp\Psr7\Response
    {
        return RecordingClient::json(503, [
            "error" => ["type" => "service_unavailable", "message" => "Down"],
        ]);
    }

    private static function devices(): \GuzzleHttp\Psr7\Response
    {
        return RecordingClient::json(200, ["devices" => []]);
    }

    private function seam(RecordingClient $recorder, ?int $retries = null): Seam
    {
        return new Seam(
            api_key: self::API_KEY,
            endpoint: "https://example.com",
            guzzle_options: $recorder->guzzle_options(),
            retries: $retries,
        );
    }

    /**
     * A POST the server may already have processed could duplicate a write if
     * repeated, so a status code never triggers a retry for one.
     */
    public function testDoesNotRetryPostOnServiceUnavailable(): void
    {
        $recorder = RecordingClient::repeating(self::service_unavailable());

        try {
            $this->seam($recorder)->devices->list();
            $this->fail("Expected the 503 to surface");
        } catch (\Throwable) {
            // The error mapping is covered in HttpErrorTest.
        }

        $this->assertSame(1, $recorder->attempt_count());
    }

    /**
     * A connection failure never reached the server, so retrying it cannot
     * duplicate anything.
     */
    public function testRetriesPostOnConnectionFailure(): void
    {
        $connect_error = new ConnectException(
            "Could not resolve host",
            new Request("POST", "/devices/list"),
        );

        $recorder = new RecordingClient([
            $connect_error,
            $connect_error,
            self::devices(),
        ]);

        $devices = $this->seam($recorder)->devices->list();

        $this->assertSame([], $devices);
        $this->assertSame(3, $recorder->attempt_count());
    }

    public function testStopsRetryingOnceRetriesAreExhausted(): void
    {
        $connect_error = new ConnectException(
            "Could not resolve host",
            new Request("POST", "/devices/list"),
        );

        $recorder = RecordingClient::repeating_throwable($connect_error);

        $this->expectException(ConnectException::class);

        try {
            $this->seam($recorder, retries: 1)->devices->list();
        } finally {
            $this->assertSame(2, $recorder->attempt_count());
        }
    }

    public function testDoesNotRetryWhenRetriesAreDisabled(): void
    {
        $connect_error = new ConnectException(
            "Could not resolve host",
            new Request("POST", "/devices/list"),
        );

        $recorder = RecordingClient::repeating_throwable($connect_error);

        try {
            $this->seam($recorder, retries: 0)->devices->list();
            $this->fail("Expected the connection failure to surface");
        } catch (ConnectException) {
            // Expected.
        }

        $this->assertSame(1, $recorder->attempt_count());
    }

    /**
     * The SDK itself only issues POSTs, but a caller reaching for the client
     * directly with an idempotent method does get status based retries.
     */
    public function testRetriesIdempotentRequestsOnServiceUnavailable(): void
    {
        $recorder = new RecordingClient([
            self::service_unavailable(),
            self::service_unavailable(),
            self::devices(),
        ]);

        $res = Body::decode(
            $this->seam($recorder)->client->request("GET", "/devices/list"),
        );

        $this->assertSame([], $res->devices);
        $this->assertSame(3, $recorder->attempt_count());
    }
}
