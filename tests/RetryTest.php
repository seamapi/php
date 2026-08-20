<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
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
            $this->seam($recorder)->client->request("POST", "/devices/list");
            $this->fail("Expected the 503 to surface");
        } catch (\Throwable) {
            // The error mapping is covered in HttpErrorTest.
        }

        $this->assertSame(1, $recorder->attempt_count());
    }

    /**
     * Non-idempotent methods are never retried, even when the failure is
     * known to have happened before the request reached the server.
     */
    public function testDoesNotRetryPostOnConnectionFailure(): void
    {
        $connect_error = new ConnectException(
            "Could not resolve host",
            new Request("POST", "/devices/list"),
        );

        $recorder = RecordingClient::repeating_throwable($connect_error);

        try {
            $this->seam($recorder)->client->request("POST", "/devices/list");
            $this->fail("Expected the connection failure to surface");
        } catch (ConnectException) {
            // Expected.
        }

        $this->assertSame(1, $recorder->attempt_count());
    }

    /**
     * A timeout may have fired while waiting on a response to a request the
     * server received and is still processing, so repeating the POST could
     * duplicate a write.
     */
    public function testDoesNotRetryPostOnTimeout(): void
    {
        $timeout = new ConnectException(
            "cURL error 28: Operation timed out after 30001 milliseconds with 0 bytes received",
            new Request("POST", "/devices/list"),
            null,
            ["errno" => 28],
        );

        $recorder = RecordingClient::repeating_throwable($timeout);

        try {
            $this->seam($recorder)->client->request("POST", "/devices/list");
            $this->fail("Expected the timeout to surface");
        } catch (ConnectException) {
            // Expected.
        }

        $this->assertSame(1, $recorder->attempt_count());
    }

    /**
     * A handler other than curl reports no errno, so the timeout is
     * recognized by its message.
     */
    public function testDoesNotRetryPostOnTimeoutWithoutAnErrno(): void
    {
        $timeout = new ConnectException(
            "Connection timed out",
            new Request("POST", "/devices/list"),
        );

        $recorder = RecordingClient::repeating_throwable($timeout);

        try {
            $this->seam($recorder)->client->request("POST", "/devices/list");
            $this->fail("Expected the timeout to surface");
        } catch (ConnectException) {
            // Expected.
        }

        $this->assertSame(1, $recorder->attempt_count());
    }

    /**
     * Repeating an idempotent request is safe even when the server may have
     * received it, so a timeout does get retried there.
     */
    public function testRetriesIdempotentRequestsOnTimeout(): void
    {
        $timeout = new ConnectException(
            "cURL error 28: Operation timed out after 30001 milliseconds with 0 bytes received",
            new Request("GET", "/devices/list"),
            null,
            ["errno" => 28],
        );

        $recorder = new RecordingClient([$timeout, $timeout, self::devices()]);

        $devices = $this->seam($recorder)->devices->list();

        $this->assertSame([], $devices);
        $this->assertSame(3, $recorder->attempt_count());
    }

    /**
     * A connection reset is a transport error, but POST is still not safe to
     * repeat.
     */
    public function testDoesNotRetryPostOnConnectionReset(): void
    {
        $reset = new RequestException(
            "Connection reset by peer",
            new Request("POST", "/devices/list"),
            null,
            null,
            ["errno" => 104],
        );

        $recorder = RecordingClient::repeating_throwable($reset);

        try {
            $this->seam($recorder)->client->request("POST", "/devices/list");
            $this->fail("Expected the connection reset to surface");
        } catch (RequestException) {
            // Expected.
        }

        $this->assertSame(1, $recorder->attempt_count());
    }

    public function testStopsRetryingOnceRetriesAreExhausted(): void
    {
        $connect_error = new ConnectException(
            "Could not resolve host",
            new Request("GET", "/devices/list"),
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
            new Request("GET", "/devices/list"),
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
     * Repeating a read is safe, so a 503 on one should be retried.
     */
    public function testSdkReadsAreRetriedOnServiceUnavailable(): void
    {
        $recorder = new RecordingClient([
            self::service_unavailable(),
            self::service_unavailable(),
            self::devices(),
        ]);

        $devices = $this->seam($recorder)->devices->list();

        $this->assertSame([], $devices);
        $this->assertSame(3, $recorder->attempt_count());
    }

    /**
     * Building a client must not mutate a handler stack the caller may
     * reuse: a second client built from the same options would otherwise
     * stack the retry middleware twice and multiply the retries.
     */
    public function testBuildingASecondClientDoesNotStackRetries(): void
    {
        $recorder = RecordingClient::repeating(self::service_unavailable());
        $options = $recorder->guzzle_options();

        $first = new Seam(
            api_key: self::API_KEY,
            endpoint: "https://example.com",
            guzzle_options: $options,
        );
        $second = new Seam(
            api_key: self::API_KEY,
            endpoint: "https://example.com",
            guzzle_options: $options,
        );

        $this->assertNotSame($first->client, $second->client);

        try {
            Body::decode($second->client->request("GET", "/devices/list"));
            $this->fail("Expected the 503 to surface");
        } catch (\Throwable) {
            // The error mapping is covered in HttpErrorTest.
        }

        $this->assertSame(3, $recorder->attempt_count());
    }

    /**
     * A caller reaching for the client directly with an idempotent method
     * gets status based retries.
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
