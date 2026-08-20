<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Seam\Seam;
use Tests\Support\RecordingClient;

/**
 * The recording client is used here because the fake never redirects.
 */
final class RedirectTest extends TestCase
{
    private const API_KEY = "seam_apikey_token";

    private function seam(
        RecordingClient $recorder,
        array $guzzle_options = [],
    ): Seam {
        return new Seam(
            api_key: self::API_KEY,
            endpoint: "https://example.com",
            guzzle_options: array_merge(
                $recorder->guzzle_options(),
                $guzzle_options,
            ),
        );
    }

    public function testFollowsARedirectByDefault(): void
    {
        $recorder = new RecordingClient([
            new Response(302, [
                "location" => "https://example.com/devices/list_moved",
            ]),
            RecordingClient::json(200, ["devices" => []]),
        ]);

        $devices = $this->seam($recorder)->devices->list();

        $this->assertSame([], $devices);
        $this->assertSame(2, $recorder->attempt_count());
    }

    /**
     * With following disabled, a redirect is a response outside the success
     * range like any other, and must not be silently decoded as a success.
     */
    public function testARedirectIsAnErrorWhenFollowingIsDisabled(): void
    {
        $recorder = new RecordingClient([
            new Response(302, [
                "location" => "https://example.com/devices/list_moved",
            ]),
        ]);

        $seam = $this->seam($recorder, ["allow_redirects" => false]);

        try {
            $seam->devices->list();
            $this->fail("Expected the redirect to surface as an error");
        } catch (RequestException $error) {
            $this->assertSame(302, $error->getResponse()?->getStatusCode());
        }

        $this->assertSame(1, $recorder->attempt_count());
    }
}
