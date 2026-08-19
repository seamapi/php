<?php

declare(strict_types=1);

namespace Tests;

use Seam\ActionAttemptError;
use Seam\ActionAttemptFailedError;
use Seam\ActionAttemptTimeoutError;
use Seam\InvalidOptionsError;
use Seam\Resources\ActionAttempt;
use Seam\Resources\ActionAttempt\Status;
use Seam\Resources\ActionAttempt\UnlockDoor;
use Seam\Seam;
use Tests\Support\FakeSeamConnectTestCase;
use Tests\Support\RecordingClient;

final class WaitForActionAttemptTest extends FakeSeamConnectTestCase
{
    private function pending_action_attempt(Seam $seam): ActionAttempt
    {
        $action_attempt = $seam->locks->unlock_door(
            $this->seed["august_device_1"],
        );

        $this->assertInstanceOf(UnlockDoor::class, $action_attempt);
        $this->assertSame(Status::PENDING, $action_attempt->status);
        $this->assertNull($action_attempt->error);
        $this->assertNull($action_attempt->result);

        $this->set_status($seam, $action_attempt, "pending");

        return $action_attempt;
    }

    /**
     * A list of action attempts is returned as is: only a single returned
     * attempt is ever resolved, so listing must not poll pending attempts.
     */
    public function testListReturnsActionAttemptsWithoutResolvingThem(): void
    {
        $seam = $this->seam(wait_for_action_attempt: false);
        $pending = $this->pending_action_attempt($seam);

        $attempts = $seam->action_attempts->list(
            action_attempt_ids: [$pending->action_attempt_id],
        );

        $this->assertContainsOnlyInstancesOf(ActionAttempt::class, $attempts);
        $this->assertCount(1, $attempts);
        $this->assertSame(
            $pending->action_attempt_id,
            $attempts[0]->action_attempt_id,
        );
        $this->assertSame(Status::PENDING, $attempts[0]->status);
    }

    private function set_status(
        Seam $seam,
        ActionAttempt $action_attempt,
        string $status,
        ?array $error = null,
    ): void {
        $seam->client->request("POST", "/_fake/update_action_attempt", [
            "json" => (object) array_filter([
                "action_attempt_id" => $action_attempt->action_attempt_id,
                "status" => $status,
                "error" => $error,
            ]),
        ]);
    }

    public function testWaitsByDefault(): void
    {
        $action_attempt = $this->seam()->locks->unlock_door(
            $this->seed["august_device_1"],
        );

        $this->assertSame(Status::SUCCESS, $action_attempt->status);
    }

    public function testClientDefaultCanDisableWaiting(): void
    {
        $seam = $this->seam(wait_for_action_attempt: false);

        $action_attempt = $seam->locks->unlock_door(
            $this->seed["august_device_1"],
        );

        $this->assertSame(Status::PENDING, $action_attempt->status);
    }

    /**
     * The options form of the client default has to wait just like `true`
     * does; treating it as "no waiting" would hand back a pending attempt
     * with no indication anything was skipped.
     */
    public function testClientDefaultCanBeAnOptionsArray(): void
    {
        $seam = $this->seam(
            wait_for_action_attempt: [
                "timeout" => 5.0,
                "polling_interval" => 0.05,
            ],
        );

        $action_attempt = $seam->locks->unlock_door(
            $this->seed["august_device_1"],
        );

        $this->assertSame(Status::SUCCESS, $action_attempt->status);
    }

    public function testPerCallOptionCanDisableWaiting(): void
    {
        $action_attempt = $this->seam()->locks->unlock_door(
            $this->seed["august_device_1"],
            wait_for_action_attempt: false,
        );

        $this->assertSame(Status::PENDING, $action_attempt->status);
    }

    public function testPerCallOptionCanEnableWaiting(): void
    {
        $seam = $this->seam(wait_for_action_attempt: false);

        $action_attempt = $seam->locks->unlock_door(
            $this->seed["august_device_1"],
            wait_for_action_attempt: true,
        );

        $this->assertSame(Status::SUCCESS, $action_attempt->status);
    }

    public function testReturnsAnAlreadySuccessfulActionAttempt(): void
    {
        $seam = $this->seam(wait_for_action_attempt: false);

        $action_attempt = $seam->locks->unlock_door(
            $this->seed["august_device_1"],
        );
        $this->set_status($seam, $action_attempt, "success");

        $resolved = $seam->action_attempts->get(
            $action_attempt->action_attempt_id,
            wait_for_action_attempt: true,
        );

        $this->assertSame(Status::SUCCESS, $resolved->status);
        $this->assertSame(
            $action_attempt->action_attempt_id,
            $resolved->action_attempt_id,
        );
    }

    /**
     * Proves the resolver really re-reads the action attempt: it starts out
     * pending and is moved to success by something outside this process, the
     * way the JavaScript and Ruby suites do it.
     */
    public function testWaitsForAnActionAttemptResolvedOutOfBand(): void
    {
        $seam = $this->seam(wait_for_action_attempt: false);
        $action_attempt = $this->pending_action_attempt($seam);

        $resolver = $this->resolve_after(
            $action_attempt->action_attempt_id,
            0.5,
        );

        try {
            $resolved = $seam->action_attempts->get(
                $action_attempt->action_attempt_id,
                wait_for_action_attempt: [
                    "timeout" => 15.0,
                    "polling_interval" => 0.1,
                ],
            );

            $this->assertSame(Status::SUCCESS, $resolved->status);
        } finally {
            proc_close($resolver);
        }
    }

    /**
     * @return resource
     */
    private function resolve_after(string $action_attempt_id, float $delay)
    {
        $payload = json_encode([
            "action_attempt_id" => $action_attempt_id,
            "status" => "success",
        ]);

        $script = sprintf(
            "usleep(%d); file_get_contents(%s, false, stream_context_create(%s));",
            (int) ($delay * 1000000.0),
            var_export($this->endpoint . "/_fake/update_action_attempt", true),
            var_export(
                [
                    "http" => [
                        "method" => "POST",
                        "header" => "Content-Type: application/json",
                        "content" => $payload,
                        "ignore_errors" => true,
                    ],
                ],
                true,
            ),
        );

        $process = proc_open(
            [PHP_BINARY, "-r", $script],
            [
                1 => ["file", "/dev/null", "w"],
                2 => ["file", "/dev/null", "w"],
            ],
            $pipes,
        );

        if (!is_resource($process)) {
            $this->fail("Could not start the out of band resolver");
        }

        return $process;
    }

    public function testThrowsWhenTheActionAttemptFails(): void
    {
        $seam = $this->seam(wait_for_action_attempt: false);

        $action_attempt = $seam->locks->unlock_door(
            $this->seed["august_device_1"],
        );
        $this->set_status($seam, $action_attempt, "error", [
            "type" => "foo",
            "message" => "Failed",
        ]);

        try {
            $seam->action_attempts->get(
                $action_attempt->action_attempt_id,
                wait_for_action_attempt: true,
            );
            $this->fail("Expected ActionAttemptFailedError");
        } catch (ActionAttemptFailedError $error) {
            $this->assertSame("Failed", $error->getMessage());
            $this->assertSame("foo", $error->getErrorCode());
            $this->assertSame(
                Status::ERROR,
                $error->getActionAttempt()->status,
            );
            $this->assertSame(
                $action_attempt->action_attempt_id,
                $error->getActionAttempt()->action_attempt_id,
            );
            $this->assertInstanceOf(ActionAttemptError::class, $error);
        }
    }

    public function testTimesOutWhileTheActionAttemptIsPending(): void
    {
        $seam = $this->seam(wait_for_action_attempt: false);
        $action_attempt = $this->pending_action_attempt($seam);

        try {
            $seam->action_attempts->get(
                $action_attempt->action_attempt_id,
                wait_for_action_attempt: [
                    "timeout" => 0.2,
                    "polling_interval" => 5.0,
                ],
            );
            $this->fail("Expected ActionAttemptTimeoutError");
        } catch (ActionAttemptTimeoutError $error) {
            $this->assertSame(
                $action_attempt->action_attempt_id,
                $error->getActionAttempt()->action_attempt_id,
            );
            $this->assertStringContainsString(
                "Timed out waiting for action attempt",
                $error->getMessage(),
            );
        }
    }

    public function testPollsOnceWhenTheIntervalOutlastsTheTimeout(): void
    {
        $seam = $this->seam(wait_for_action_attempt: false);
        $action_attempt = $this->pending_action_attempt($seam);

        $started_at = microtime(true);

        try {
            $seam->action_attempts->get(
                $action_attempt->action_attempt_id,
                wait_for_action_attempt: [
                    "timeout" => 0.3,
                    "polling_interval" => 30.0,
                ],
            );
            $this->fail("Expected ActionAttemptTimeoutError");
        } catch (ActionAttemptTimeoutError) {
            $elapsed = microtime(true) - $started_at;

            $this->assertGreaterThanOrEqual(0.3, $elapsed);
            $this->assertLessThan(5.0, $elapsed);
        }
    }

    public function testResolvesWhenTheIntervalOutlastsTheTimeout(): void
    {
        $seam = $this->seam(wait_for_action_attempt: false);
        $action_attempt = $this->pending_action_attempt($seam);
        $this->set_status($seam, $action_attempt, "success");

        $resolved = $seam->action_attempts->get(
            $action_attempt->action_attempt_id,
            wait_for_action_attempt: [
                "timeout" => 0.3,
                "polling_interval" => 30.0,
            ],
        );

        $this->assertSame(Status::SUCCESS, $resolved->status);
    }

    /**
     * @dataProvider invalidWaitOptions
     */
    public function testRejectsInvalidWaitOptions(
        array $wait_for_action_attempt,
        string $expected_message,
    ): void {
        $seam = $this->seam(wait_for_action_attempt: false);
        $action_attempt = $this->pending_action_attempt($seam);

        $this->expectException(InvalidOptionsError::class);
        $this->expectExceptionMessage($expected_message);

        $seam->action_attempts->get(
            $action_attempt->action_attempt_id,
            wait_for_action_attempt: $wait_for_action_attempt,
        );
    }

    public static function invalidWaitOptions(): array
    {
        return [
            "zero polling_interval" => [
                ["polling_interval" => 0],
                "polling_interval option must be greater than zero",
            ],
            "negative polling_interval" => [
                ["polling_interval" => -5],
                "polling_interval option must be greater than zero",
            ],
            "negative timeout" => [
                ["timeout" => -1],
                "timeout option must not be negative",
            ],
        ];
    }

    /**
     * Resolving fetches the action attempt through the HTTP client rather
     * than the route client, so enabling the option on the route that reads
     * action attempts cannot recurse.
     */
    public function testPollSendsTheIdAsAQueryNotABody(): void
    {
        $recorder = new RecordingClient([
            RecordingClient::json(200, [
                "action_attempt" => [
                    "action_attempt_id" => "aa_1",
                    "status" => "pending",
                ],
            ]),
            RecordingClient::json(200, [
                "action_attempt" => [
                    "action_attempt_id" => "aa_1",
                    "status" => "success",
                ],
            ]),
        ]);

        $seam = Seam::from_api_key(
            "seam_apikey_token",
            endpoint: "https://example.com",
            guzzle_options: $recorder->guzzle_options(),
            retries: 0,
        );

        $resolved = $seam->action_attempts->get("aa_1", [
            "timeout" => 5.0,
            "polling_interval" => 0.01,
        ]);

        $this->assertSame(Status::SUCCESS, $resolved->status);

        $poll = $recorder->request(1);

        $this->assertSame("GET", $poll->getMethod());
        $this->assertSame("/action_attempts/get", $poll->getUri()->getPath());
        $this->assertSame(
            "action_attempt_id=aa_1&_strict=true",
            $poll->getUri()->getQuery(),
        );
        $this->assertSame("", (string) $poll->getBody());
    }

    public function testActionAttemptsGetDoesNotRecurse(): void
    {
        $seam = $this->seam(wait_for_action_attempt: false);

        $action_attempt = $seam->locks->unlock_door(
            $this->seed["august_device_1"],
        );
        $this->set_status($seam, $action_attempt, "success");

        $resolved = $seam->action_attempts->get(
            $action_attempt->action_attempt_id,
            wait_for_action_attempt: [
                "timeout" => 1.0,
                "polling_interval" => 0.05,
            ],
        );

        $this->assertSame(Status::SUCCESS, $resolved->status);
    }
}
