<?php

declare(strict_types=1);

namespace Tests;

use Seam\Exceptions\ActionAttemptError;
use Seam\Exceptions\ActionAttemptFailedError;
use Seam\Exceptions\ActionAttemptTimeoutError;
use Seam\Resources\ActionAttempt;
use Seam\Seam;
use Tests\Support\FakeSeamConnectTestCase;

final class WaitForActionAttemptTest extends FakeSeamConnectTestCase
{
    private function pending_action_attempt(Seam $seam): ActionAttempt
    {
        $action_attempt = $seam->locks->unlock_door(
            $this->seed["august_device_1"],
        );

        $this->assertSame("pending", $action_attempt->status);

        $this->set_status($seam, $action_attempt, "pending");

        return $action_attempt;
    }

    private function set_status(
        Seam $seam,
        ActionAttempt $action_attempt,
        string $status,
        ?array $error = null,
    ): void {
        $seam->client->request(
            "POST",
            "/_fake/update_action_attempt",
            (object) array_filter([
                "action_attempt_id" => $action_attempt->action_attempt_id,
                "status" => $status,
                "error" => $error,
            ]),
        );
    }

    public function testWaitsByDefault(): void
    {
        $action_attempt = $this->seam()->locks->unlock_door(
            $this->seed["august_device_1"],
        );

        $this->assertSame("success", $action_attempt->status);
    }

    public function testClientDefaultCanDisableWaiting(): void
    {
        $seam = $this->seam(wait_for_action_attempt: false);

        $action_attempt = $seam->locks->unlock_door(
            $this->seed["august_device_1"],
        );

        $this->assertSame("pending", $action_attempt->status);
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

        $this->assertSame("success", $action_attempt->status);
    }

    public function testPerCallOptionCanDisableWaiting(): void
    {
        $action_attempt = $this->seam()->locks->unlock_door(
            $this->seed["august_device_1"],
            wait_for_action_attempt: false,
        );

        $this->assertSame("pending", $action_attempt->status);
    }

    public function testPerCallOptionCanEnableWaiting(): void
    {
        $seam = $this->seam(wait_for_action_attempt: false);

        $action_attempt = $seam->locks->unlock_door(
            $this->seed["august_device_1"],
            wait_for_action_attempt: true,
        );

        $this->assertSame("success", $action_attempt->status);
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

        $this->assertSame("success", $resolved->status);
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

            $this->assertSame("success", $resolved->status);
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
            $this->assertSame("error", $error->getActionAttempt()->status);
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

    /**
     * Resolving fetches the action attempt through the HTTP client rather
     * than the route client, so enabling the option on the route that reads
     * action attempts cannot recurse.
     */
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

        $this->assertSame("success", $resolved->status);
    }
}
