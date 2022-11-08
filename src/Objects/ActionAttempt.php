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
            result: $json->result ?? null,
            action_type: $json->action_type ?? null,
            status: $json->status ?? null,
            error: SeamError::from_json($json->error ?? null)
        );
    }

    public function __construct(
        public string $action_attempt_id,

        /*
         * CREATE_ACCESS_CODE, DELETE_ACCESS_CODE, LOCK_DOOR, UNLOCK_DOOR, etc.
         */
        public string | null $action_type,

        /*
         * Can be "pending", "success", "error"
         */
        public string $status,
        public mixed $result,
        public SeamError|null $error
    ) {
    }
}
