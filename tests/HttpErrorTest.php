<?php

declare(strict_types=1);

namespace Tests;

use Seam\HttpApiError;
use Seam\HttpInvalidInputError;
use Seam\HttpUnauthorizedError;
use Seam\Seam;
use Tests\Support\FakeSeamConnectTestCase;

final class HttpErrorTest extends FakeSeamConnectTestCase
{
    public function testThrowsUnauthorizedError(): void
    {
        $seam = new Seam(
            api_key: "seam_invalid_api_key",
            endpoint: $this->endpoint,
        );

        try {
            $seam->devices->list();
            $this->fail("Expected HttpUnauthorizedError");
        } catch (HttpUnauthorizedError $error) {
            $this->assertSame(401, $error->getStatusCode());
            $this->assertSame("unauthorized", $error->getErrorCode());
            $this->assertStringStartsWith(
                "request",
                (string) $error->getRequestId(),
            );
        }
    }

    public function testThrowsApiErrorOnStandardErrorResponse(): void
    {
        try {
            $this->seam()->devices->get("unknown-device");
            $this->fail("Expected HttpApiError");
        } catch (HttpApiError $error) {
            $this->assertSame(404, $error->getStatusCode());
            $this->assertSame("device_not_found", $error->getErrorCode());
            $this->assertStringStartsWith(
                "request",
                (string) $error->getRequestId(),
            );
        }
    }

    public function testThrowsInvalidInputErrorWithValidationMessages(): void
    {
        try {
            $this->seam()->client->request("POST", "/devices/list", [
                "json" => (object) ["device_ids" => 4242],
            ]);
            $this->fail("Expected HttpInvalidInputError");
        } catch (HttpInvalidInputError $error) {
            $this->assertSame(400, $error->getStatusCode());
            $this->assertSame("invalid_input", $error->getErrorCode());
            $this->assertStringStartsWith(
                "request",
                (string) $error->getRequestId(),
            );
            $this->assertSame(
                ["Expected array, received number"],
                $error->getValidationErrorMessages("device_ids"),
            );
        }
    }

    public function testValidationMessagesAreEmptyForAnUnknownParam(): void
    {
        try {
            $this->seam()->client->request("POST", "/devices/list", [
                "json" => (object) ["device_ids" => 4242],
            ]);
            $this->fail("Expected HttpInvalidInputError");
        } catch (HttpInvalidInputError $error) {
            $this->assertSame(
                [],
                $error->getValidationErrorMessages("non_existent_param"),
            );
        }
    }

    /**
     * A workspace outage answers with a 503 that is not a Seam error
     * envelope, so it surfaces as the underlying transport error rather than
     * a Seam exception.
     */
    public function testWorkspaceOutageSurfacesTheTransportError(): void
    {
        $seam = $this->seam(retries: 0);

        $seam->client->request("POST", "/_fake/simulate_workspace_outage", [
            "json" => (object) [
                "workspace_id" => $this->seed["seed_workspace_1"],
                "routes" => ["/devices/list"],
            ],
        ]);

        try {
            $seam->devices->list();
            $this->fail("Expected a Guzzle BadResponseException");
        } catch (\GuzzleHttp\Exception\BadResponseException $error) {
            $this->assertSame(503, $error->getResponse()->getStatusCode());
        }
    }
}
