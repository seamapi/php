<?php

namespace Seam\Objects;

class ActionAttemptError
{
    
    public static function from_json(mixed $json): ActionAttemptError|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            type: $json->type,
            message: $json->message,
        );
    }
  

    
    public function __construct(
        public string $type,
        public string $message,
    ) {
    }
  
}
