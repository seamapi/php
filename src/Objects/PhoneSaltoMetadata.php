<?php

namespace Seam\Objects;

class PhoneSaltoMetadata
{
    
    public static function from_json(mixed $json): PhoneSaltoMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            lock_id: $json->lock_id,
            customer_reference: $json->customer_reference,
            lock_type: $json->lock_type,
            battery_level: $json->battery_level,
            locked_state: $json->locked_state,
            model: $json->model ?? null,
        );
    }
  

    
    public function __construct(
        public string $lock_id,
        public string $customer_reference,
        public string $lock_type,
        public string $battery_level,
        public string $locked_state,
        public string | null $model,
    ) {
    }
  
}
