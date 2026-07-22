<?php

namespace Seam\Objects;

class BridgeConnectedSystems
{
    public static function from_json(mixed $json): BridgeConnectedSystems|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            acs_system_display_name: $json->acs_system_display_name ?? null,
            acs_system_id: $json->acs_system_id ?? null,
            bridge_created_at: $json->bridge_created_at ?? null,
            bridge_id: $json->bridge_id ?? null,
            connected_account_created_at: $json->connected_account_created_at ??
                null,
            connected_account_id: $json->connected_account_id ?? null,
            workspace_display_name: $json->workspace_display_name ?? null,
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $acs_system_display_name,
        public string|null $acs_system_id,
        public string|null $bridge_created_at,
        public string|null $bridge_id,
        public string|null $connected_account_created_at,
        public string|null $connected_account_id,
        public string|null $workspace_display_name,
        public string|null $workspace_id,
    ) {}
}
