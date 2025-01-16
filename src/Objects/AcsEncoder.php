<?php

namespace Seam\Objects;

class AcsEncoder
{
    public static function from_json(mixed $json): AcsEncoder|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_encoder_id: $json->acs_encoder_id,
            acs_system_id: $json->acs_system_id,
            created_at: $json->created_at,
            display_name: $json->display_name,
            errors: array_map(
                fn($e) => AcsEncoderErrors::from_json($e),
                $json->errors ?? []
            ),
            workspace_id: $json->workspace_id
        );
    }

    public function __construct(
        public string $acs_encoder_id,
        public string $acs_system_id,
        public string $created_at,
        public string $display_name,
        public array $errors,
        public string $workspace_id
    ) {
    }
}
