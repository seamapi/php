<?php

namespace Seam\Routes\Clients;

use Seam\SeamClient;
use Seam\Routes\Objects\ActionAttempt;
use Seam\ActionAttemptFailedError;
use Seam\ActionAttemptTimeoutError;

class ActionAttemptsClient
{
  private SeamClient $seam;

  public function __construct(SeamClient $seam)
  {
    $this->seam = $seam;
  }

  public function get(string $action_attempt_id): ActionAttempt
  {
    $request_payload = [];

    if ($action_attempt_id !== null) {
      $request_payload["action_attempt_id"] = $action_attempt_id;
    }

    $res = $this->seam->request(
      "POST",
      "/action_attempts/get",
      json: (object) $request_payload,
      inner_object: "action_attempt"
    );

    return ActionAttempt::from_json($res);
  }

  public function list(array $action_attempt_ids): array
  {
    $request_payload = [];

    if ($action_attempt_ids !== null) {
      $request_payload["action_attempt_ids"] = $action_attempt_ids;
    }

    $res = $this->seam->request(
      "POST",
      "/action_attempts/list",
      json: (object) $request_payload,
      inner_object: "action_attempts"
    );

    return array_map(fn($r) => ActionAttempt::from_json($r), $res);
  }
  public function poll_until_ready(
    string $action_attempt_id,
    float $timeout = 20.0
  ): ActionAttempt {
    $seam = $this->seam;
    $time_waiting = 0.0;
    $polling_interval = 0.4;
    $action_attempt = $seam->action_attempts->get($action_attempt_id);

    while ($action_attempt->status == "pending") {
      $action_attempt = $seam->action_attempts->get(
        $action_attempt->action_attempt_id
      );
      if ($time_waiting > $timeout) {
        throw new ActionAttemptTimeoutError($action_attempt, $timeout);
      }
      $time_waiting += $polling_interval;
      usleep($polling_interval * 1000000);
    }

    if ($action_attempt->status == "error") {
      throw new ActionAttemptFailedError($action_attempt);
    }

    return $action_attempt;
  }
}
