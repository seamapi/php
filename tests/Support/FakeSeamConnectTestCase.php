<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use Seam\Seam;

/**
 * Base class for tests that run against a fake Seam Connect server.
 */
abstract class FakeSeamConnectTestCase extends TestCase
{
    protected FakeSeamConnect $fake;
    protected string $endpoint;
    protected array $seed;

    protected function setUp(): void
    {
        $this->fake = FakeSeamConnect::start();
        $this->endpoint = $this->fake->endpoint();
        $this->seed = $this->fake->seed();
    }

    protected function tearDown(): void
    {
        $this->fake->stop();
    }

    /**
     * A client authorized against the fake with the seeded API key.
     */
    protected function seam(
        bool|array|null $wait_for_action_attempt = null,
        array $guzzle_options = [],
        ?int $retries = null,
    ): Seam {
        return new Seam(
            api_key: $this->seed["seam_apikey1_token"],
            endpoint: $this->endpoint,
            wait_for_action_attempt: $wait_for_action_attempt,
            guzzle_options: $guzzle_options,
            retries: $retries,
        );
    }
}
