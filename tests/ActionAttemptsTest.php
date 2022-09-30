<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;

final class ActionAttemptsTest extends TestCase
{
  public function testGetAndListActionAttempts(): void
  {
    $seam = Fixture::getTestServer(true);

    $device_id = $seam->devices->list()[0]->device_id;
    $action_attempt = $seam->access_codes->create(device_id: $device_id, wait_for_access_code: false);

    $action_attempt_id = $action_attempt->action_attempt_id;
    $action_attempt = $seam->action_attempts->get(action_attempt_id: $action_attempt_id);
    $this->assertTrue($action_attempt->action_attempt_id === $action_attempt_id);

    $updated_action_attempt = $seam->action_attempts->poll_until_ready($action_attempt_id);
    $this->assertEquals($updated_action_attempt->status, "success");

    $action_attempts = $seam->action_attempts->list([$action_attempt_id]);
    $this->assertTrue(count($action_attempts) > 0);
  }
}
