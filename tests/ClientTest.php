<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Handler\MockHandler;
use Seam\Http\Body;
use Seam\Http\ClientFactory;
use Seam\HttpApiError;
use Seam\InvalidOptionsError;
use Seam\Seam;
use Seam\SeamWithoutWorkspace;
use Tests\Support\FakeSeamConnectTestCase;
use Tests\Support\RecordingClient;

final class ClientTest extends FakeSeamConnectTestCase
{
    public function testClientCanMakeRequests(): void
    {
        $seam = $this->seam();

        $res = Body::decode(
            $seam->client->request("POST", "/devices/get", [
                "json" => (object) [
                    "device_id" => $this->seed["august_device_1"],
                ],
            ]),
        );

        $this->assertSame(
            $this->seed["august_device_1"],
            $res->device->device_id,
        );
        $this->assertSame(
            $this->seed["seed_workspace_1"],
            $res->device->workspace_id,
        );
    }

    public function testClientIsTheGuzzleClient(): void
    {
        $this->assertInstanceOf(
            \GuzzleHttp\ClientInterface::class,
            $this->seam()->client,
        );
    }

    /**
     * A client carries its own endpoint and authorization, so it has to work
     * on its own without any authentication option beside it.
     */
    public function testFromClientNeedsNoCredentials(): void
    {
        $authorized = $this->seam();

        $seam = Seam::from_client($authorized->client);

        $device = $seam->devices->get($this->seed["august_device_1"]);

        $this->assertSame($this->seed["august_device_1"], $device->device_id);
        $this->assertSame(
            $this->seed["seed_workspace_1"],
            $device->workspace_id,
        );
    }

    private function foreign_client(array $options = []): \GuzzleHttp\Client
    {
        return new \GuzzleHttp\Client(
            array_merge(
                [
                    "base_uri" => $this->endpoint,
                    "headers" => [
                        "authorization" =>
                            "Bearer " . $this->seed["seam_apikey1_token"],
                    ],
                ],
                $options,
            ),
        );
    }

    public function testAnInjectedClientIsUsedAsGiven(): void
    {
        $seam = Seam::from_client($this->foreign_client());

        $device = $seam->devices->get($this->seed["august_device_1"]);
        $this->assertSame($this->seed["august_device_1"], $device->device_id);

        $this->expectException(\GuzzleHttp\Exception\ClientException::class);

        $seam->devices->get("nonexistent-device-id");
    }

    public function testAddMiddlewareGivesAnInjectedClientSeamErrors(): void
    {
        $handler = \GuzzleHttp\HandlerStack::create();
        ClientFactory::add_middleware($handler);

        $seam = Seam::from_client(
            $this->foreign_client([
                "handler" => $handler,
                "http_errors" => false,
            ]),
        );

        $this->expectException(HttpApiError::class);

        $seam->devices->get("nonexistent-device-id");
    }

    public function testAddMiddlewareGivesAnInjectedClientRetries(): void
    {
        $recorder = RecordingClient::repeating(
            RecordingClient::json(503, [
                "error" => [
                    "type" => "unavailable",
                    "message" => "Service Unavailable",
                ],
            ]),
            times: 5,
        );

        $handler = \GuzzleHttp\HandlerStack::create(
            $recorder->guzzle_options()["handler"],
        );
        ClientFactory::add_middleware($handler);

        $seam = Seam::from_client(
            $this->foreign_client([
                "handler" => $handler,
                "http_errors" => false,
            ]),
        );

        try {
            $seam->devices->get("d1");
            $this->fail("Expected an HttpApiError");
        } catch (HttpApiError) {
            $this->assertSame(3, $recorder->attempt_count());
        }
    }

    public function testAddMiddlewareHonoursARetryCount(): void
    {
        $recorder = RecordingClient::repeating(
            RecordingClient::json(503, [
                "error" => [
                    "type" => "unavailable",
                    "message" => "Service Unavailable",
                ],
            ]),
            times: 5,
        );

        $handler = \GuzzleHttp\HandlerStack::create(
            $recorder->guzzle_options()["handler"],
        );
        ClientFactory::add_middleware($handler, retries: 0);

        $seam = Seam::from_client(
            $this->foreign_client([
                "handler" => $handler,
                "http_errors" => false,
            ]),
        );

        try {
            $seam->devices->get("d1");
            $this->fail("Expected an HttpApiError");
        } catch (HttpApiError) {
            $this->assertSame(1, $recorder->attempt_count());
        }
    }

    public function testClientOptionReusesAnotherInstancesClient(): void
    {
        $seam = new Seam(client: $this->seam()->client);

        $device = $seam->devices->get($this->seed["august_device_1"]);

        $this->assertSame($this->seed["august_device_1"], $device->device_id);
    }

    /**
     * A credential passed beside a client would be silently discarded, since
     * the client carries its own authorization, so the mix-up is rejected.
     */
    public function testClientOptionRejectsAnyOtherOption(): void
    {
        $client = $this->seam()->client;

        $this->expectException(InvalidOptionsError::class);
        $this->expectExceptionMessage(
            "The api_key option cannot be used with the client option",
        );

        new Seam(api_key: $this->seed["seam_apikey1_token"], client: $client);
    }

    /**
     * wait_for_action_attempt does not configure the client, so it is the
     * one option a client can be combined with.
     */
    public function testClientOptionStillTakesAWaitForActionAttemptDefault(): void
    {
        $seam = new Seam(
            client: $this->seam()->client,
            wait_for_action_attempt: false,
        );

        $this->assertFalse($seam->defaults["wait_for_action_attempt"]);
    }

    public function testWithoutWorkspaceClientOptionRejectsAnyOtherOption(): void
    {
        $client = $this->seam()->client;

        $this->expectException(InvalidOptionsError::class);
        $this->expectExceptionMessage(
            "The endpoint option cannot be used with the client option",
        );

        new SeamWithoutWorkspace(endpoint: $this->endpoint, client: $client);
    }

    /**
     * A bare handler, such as a MockHandler, is wrapped in a handler stack
     * so the error mapping and retries still apply to it.
     */
    public function testABareHandlerStillGetsTheErrorMapping(): void
    {
        $mock = new MockHandler([
            RecordingClient::json(404, [
                "error" => [
                    "type" => "device_not_found",
                    "message" => "Device not found",
                ],
            ]),
        ]);

        $seam = new Seam(
            api_key: $this->seed["seam_apikey1_token"],
            endpoint: "https://example.com",
            guzzle_options: ["handler" => $mock],
        );

        try {
            $seam->devices->list();
            $this->fail("Expected the error to be mapped");
        } catch (HttpApiError $error) {
            $this->assertSame("device_not_found", $error->getErrorCode());
        }
    }

    public function testGuzzleOptionsAreMergedIntoTheClient(): void
    {
        $seam = $this->seam(
            guzzle_options: [
                "headers" => ["Custom-Header" => "Test-Value"],
                "timeout" => 30,
            ],
        );

        $config = $seam->client->getConfig();

        $this->assertSame(30, $config["timeout"]);
        $this->assertSame("Test-Value", $config["headers"]["Custom-Header"]);
        // The custom headers must not displace the authorization or the SDK
        // headers.
        $this->assertSame(
            "Bearer " . $this->seed["seam_apikey1_token"],
            $config["headers"]["authorization"],
        );
        $this->assertSame("seamapi/php", $config["headers"]["seam-sdk-name"]);
    }

    public function testGuzzleOptionsStillAuthorizeRequests(): void
    {
        $seam = $this->seam(
            guzzle_options: ["headers" => ["Custom-Header" => "Test-Value"]],
        );

        $device = $seam->devices->get($this->seed["august_device_1"]);

        $this->assertSame($this->seed["august_device_1"], $device->device_id);
    }

    public function testTimeoutDefaultsToThirtySeconds(): void
    {
        $config = $this->seam()->client->getConfig();

        $this->assertSame(30.0, ClientFactory::DEFAULT_TIMEOUT);
        $this->assertSame(30.0, $config["timeout"]);
        $this->assertSame(30.0, $config["connect_timeout"]);
    }

    public function testTimeoutCanBeSet(): void
    {
        $seam = Seam::from_api_key(
            $this->seed["seam_apikey1_token"],
            endpoint: $this->endpoint,
            timeout: 5.0,
        );

        $config = $seam->client->getConfig();

        $this->assertSame(5.0, $config["timeout"]);
        $this->assertSame(5.0, $config["connect_timeout"]);
    }

    /**
     * Every constructor and factory has to agree on the default, otherwise
     * the same call waits or does not depending on how the client was built.
     */
    public function testWaitForActionAttemptDefaultsToTrueEverywhere(): void
    {
        $api_key = $this->seed["seam_apikey1_token"];

        $clients = [
            "constructor" => new Seam(
                api_key: $api_key,
                endpoint: $this->endpoint,
            ),
            "from_api_key" => Seam::from_api_key(
                $api_key,
                endpoint: $this->endpoint,
            ),
            "from_personal_access_token" => Seam::from_personal_access_token(
                $this->seed["seam_at1_token"],
                $this->seed["seed_workspace_1"],
                endpoint: $this->endpoint,
            ),
            "from_client" => Seam::from_client($this->seam()->client),
        ];

        foreach ($clients as $name => $seam) {
            $this->assertTrue(
                $seam->defaults["wait_for_action_attempt"],
                "{$name} should wait for action attempts by default",
            );
        }
    }

    public function testWaitForActionAttemptDefaultCanBeDisabled(): void
    {
        $seam = $this->seam(wait_for_action_attempt: false);

        $this->assertFalse($seam->defaults["wait_for_action_attempt"]);
    }
}
