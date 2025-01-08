<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;

final class ActionAttemptsTest extends TestCase
{
    public function testGetAndListActionAttempts(): void
    {
        $seam = Fixture::getTestServer();

        $device_id = $seam->devices->list()[0]->device_id;
        $action_attempt = $seam->locks->lock_door(device_id: $device_id);

        $action_attempt_id = $action_attempt->action_attempt_id;
        $action_attempt = $seam->action_attempts->get(
            action_attempt_id: $action_attempt_id
        );
        $this->assertTrue(
            $action_attempt->action_attempt_id === $action_attempt_id
        );

        $updated_action_attempt = $seam->action_attempts->poll_until_ready(
            $action_attempt_id
        );
        $this->assertEquals($updated_action_attempt->status, "success");

        $action_attempts = $seam->action_attempts->list([$action_attempt_id]);
        $this->assertTrue(count($action_attempts) > 0);
    }

    public function testFailedActionAttempt(): void
    {
        $seam = Fixture::getTestServer();

        $device_id = $seam->devices->list()[0]->device_id;
        $action_attempt = $seam->locks->unlock_door(
            device_id: $device_id,
            wait_for_action_attempt: false
        );

        $this->assertEquals("pending", $action_attempt->status);

        $seam->request(
            "POST",
            "/_fake/update_action_attempt",
            json: [
                "action_attempt_id" => $action_attempt->action_attempt_id,
                "status" => "error",
                "error" => [
                    "message" => "Failed",
                    "type" => "failed_attempt",
                ],
            ]
        );

        try {
            $seam->action_attempts->poll_until_ready(
                $action_attempt->action_attempt_id
            );
            $this->fail("Expected ActionAttemptFailedError");
        } catch (\Seam\ActionAttemptFailedError $e) {
            $this->assertEquals(
                $action_attempt->action_attempt_id,
                $e->getActionAttempt()->action_attempt_id
            );
            $this->assertEquals("error", $e->getActionAttempt()->status);
            $this->assertEquals("failed_attempt", $e->getErrorCode());
            $this->assertEquals("Failed", $e->getMessage());
        }
    }

    public function testTimeoutActionAttempt(): void
    {
        $seam = Fixture::getTestServer();

        $device_id = $seam->devices->list()[0]->device_id;
        $action_attempt = $seam->locks->unlock_door(
            device_id: $device_id,
            wait_for_action_attempt: false
        );

        $this->assertEquals("pending", $action_attempt->status);

        $seam->request(
            "POST",
            "/_fake/update_action_attempt",
            json: [
                "action_attempt_id" => $action_attempt->action_attempt_id,
                "status" => "pending",
            ]
        );

        try {
            $seam->action_attempts->poll_until_ready(
                $action_attempt->action_attempt_id,
                0.1
            );
            $this->fail("Expected TimeoutError");
        } catch (\Seam\ActionAttemptTimeoutError $e) {
            $this->assertEquals(
                $action_attempt->action_attempt_id,
                $e->getActionAttempt()->action_attempt_id
            );
            $this->assertEquals("pending", $e->getActionAttempt()->status);
            $this->assertEquals(
                "Timed out waiting for action attempt after 0.1s",
                $e->getMessage()
            );
        }
    }
}
