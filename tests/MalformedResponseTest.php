<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Seam\HttpApiError;
use Seam\Seam;
use Tests\Support\RecordingClient;

/**
 * The fake cannot produce malformed error responses, so the recording client
 * drives the bodies that must fall through the Seam error check and surface
 * as a transport error rather than being parsed into a Seam exception.
 */
final class MalformedResponseTest extends TestCase
{
    private function seam(RecordingClient $recorder): Seam
    {
        return Seam::from_api_key(
            "seam_apikey_token",
            endpoint: "https://example.com",
            guzzle_options: $recorder->guzzle_options(),
            retries: 0,
        );
    }

    /**
     * @dataProvider nonSeamErrorResponses
     */
    public function testNonSeamErrorResponsesSurfaceTheTransportError(
        Response $response,
    ): void {
        $seam = $this->seam(new RecordingClient([$response]));

        try {
            $seam->devices->list();
            $this->fail("Expected a Guzzle BadResponseException");
        } catch (HttpApiError $error) {
            $this->fail(
                "Expected a transport error, got " .
                    $error::class .
                    ": " .
                    $error->getMessage(),
            );
        } catch (BadResponseException $error) {
            $this->assertSame(500, $error->getResponse()->getStatusCode());
        }
    }

    public static function nonSeamErrorResponses(): array
    {
        return [
            "plain text body" => [
                RecordingClient::raw(500, "Internal Server Error"),
            ],
            "html body" => [
                RecordingClient::raw(
                    500,
                    "<html><body>Gateway</body></html>",
                    "text/html",
                ),
            ],
            "malformed json" => [
                RecordingClient::raw(500, "{invalid json", "application/json"),
            ],
            "json without an error object" => [
                RecordingClient::json(500, ["message" => "Some error"]),
            ],
            "error without a type and message" => [
                RecordingClient::json(500, ["error" => ["code" => 500]]),
            ],
            "json that is not an object" => [
                RecordingClient::json(500, [1, 2]),
            ],
            "error that is not an object" => [
                RecordingClient::json(500, ["error" => "boom"]),
            ],
            "empty body" => [RecordingClient::raw(500, "", "application/json")],
        ];
    }

    /**
     * A redirect is not a success, so it must not be handed back to the
     * caller as though it were a resource.
     */
    public function testRedirectIsNotTreatedAsSuccess(): void
    {
        $recorder = new RecordingClient([
            new Response(302, ["location" => "https://example.com/elsewhere"]),
        ]);

        $seam = Seam::from_api_key(
            "seam_apikey_token",
            endpoint: "https://example.com",
            guzzle_options: array_merge($recorder->guzzle_options(), [
                "allow_redirects" => false,
            ]),
            retries: 0,
        );

        // A 3xx is not a Seam error envelope either, so it surfaces as the
        // transport error rather than being handed back as a resource.
        $this->expectException(RequestException::class);

        $seam->devices->list();
    }
}
