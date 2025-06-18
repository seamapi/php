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
            acs_system_display_name: $json->acs_system_display_name,
            acs_system_id: $json->acs_system_id,
            bridge_created_at: $json->bridge_created_at,
            bridge_id: $json->bridge_id,
            connected_account_created_at: $json->connected_account_created_at,
            connected_account_id: $json->connected_account_id,
            workspace_display_name: $json->workspace_display_name,
            workspace_id: $json->workspace_id
        );
    }

    public function __construct(
        public string $acs_system_display_name,
        public string $acs_system_id,
        public string $bridge_created_at,
        public string $bridge_id,
        public string $connected_account_created_at,
        public string $connected_account_id,
        public string $workspace_display_name,
        public string $workspace_id
    ) {
    }
}
