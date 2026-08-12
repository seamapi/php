<?php

declare(strict_types=1);

namespace Tests;

use Seam\Http\Body;
use Seam\Http\ClientFactory;
use Seam\Seam;
use Tests\Support\FakeSeamConnectTestCase;

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

    public function testClientOptionReusesAnotherInstancesClient(): void
    {
        $seam = new Seam(client: $this->seam()->client);

        $device = $seam->devices->get($this->seed["august_device_1"]);

        $this->assertSame($this->seed["august_device_1"], $device->device_id);
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

    public function testLtsVersionIsExposed(): void
    {
        $this->assertSame("1.0.0", Seam::LTS_VERSION);
        $this->assertSame("1.0.0", $this->seam()->lts_version());
    }
}
