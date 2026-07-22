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
            acs_encoder_id: $json->acs_encoder_id ?? null,
            acs_system_id: $json->acs_system_id ?? null,
            connected_account_id: $json->connected_account_id ?? null,
            created_at: $json->created_at ?? null,
            display_name: $json->display_name ?? null,
            errors: array_map(
                fn($e) => AcsEncoderErrors::from_json($e),
                $json->errors ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $acs_encoder_id,
        public string|null $acs_system_id,
        public string|null $connected_account_id,
        public string|null $created_at,
        public string|null $display_name,
        public array $errors,
        public string|null $workspace_id,
    ) {}
}
