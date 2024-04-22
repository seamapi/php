<?php

namespace Seam\Objects;

class ActionAttempt
{
    public static function from_json(mixed $json): ActionAttempt|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            action_attempt_id: $json->action_attempt_id ?? null,
            action_type: $json->action_type ?? null,
            error: isset($json->error)
                ? ActionAttemptError::from_json($json->error)
                : null,
            result: $json->result ?? null,
            status: $json->status ?? null
        );
    }

    public function __construct(
        public string|null $action_attempt_id,
        public string|null $action_type,
        public ActionAttemptError|null $error,
        public mixed $result,
        public string|null $status
    ) {
    }
}
