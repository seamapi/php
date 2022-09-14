<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use GuzzleHttp\Client;

final class TestActionAttempts extends TestCase
{
  public function testGetAndListActionAttempts(): void
  {
    $seam = Fixture::getTestServer(true);
    $action_attempts = $seam->action_attempts->list();
    $this->assertIsArray($action_attempts);

    // TODO use seam->locks->unlock_door or something to create action attempt

    // $action_attempt_id = $action_attempts[0]->action_attempt_id;
    // $action_attempt = $seam->action_attempts->get(action_attempt_id: $action_attempt_id);
    // $this->assertTrue($action_attempt->action_attempt_id === $action_attempt_id);
  }
}
