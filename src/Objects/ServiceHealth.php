<?php

namespace Seam\Objects;

class ServiceHealth
{
    
    public static function from_json(mixed $json): ServiceHealth|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            service: $json->service,
            status: $json->status,
            description: $json->description,
        );
    }
  

    
    public function __construct(
        public string $service,
        public string $status,
        public string $description,
    ) {
    }
  
}
