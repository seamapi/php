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
            status: $json->status ?? null,
            action_type: $json->action_type ?? null,
            action_attempt_id: $json->action_attempt_id ?? null,
            result: $json->result ?? null,
            error: isset($json->error) ? ActionAttemptError::from_json($json->error) : null,
        );
    }
  

    
    public function __construct(
        public string | null $status,
        public string | null $action_type,
        public string | null $action_attempt_id,
        public string | null $result,
        public ActionAttemptError | null $error,
    ) {
    }
  
}
