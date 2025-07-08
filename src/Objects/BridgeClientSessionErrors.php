<?php

namespace Seam\Objects;

class BridgeClientSessionErrors
{
    public static function from_json(
        mixed $json
    ): BridgeClientSessionErrors|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            error_code: $json->error_code,
            message: $json->message,
            can_tailscale_proxy_reach_bridge: $json->can_tailscale_proxy_reach_bridge ??
                null,
            can_tailscale_proxy_reach_tailscale_network: $json->can_tailscale_proxy_reach_tailscale_network ??
                null,
            is_bridge_socks_server_healthy: $json->is_bridge_socks_server_healthy ??
                null,
            is_tailscale_proxy_reachable: $json->is_tailscale_proxy_reachable ??
                null,
            is_tailscale_proxy_socks_server_healthy: $json->is_tailscale_proxy_socks_server_healthy ??
                null
        );
    }

    public function __construct(
        public string $created_at,
        public string $error_code,
        public string $message,
        public bool|null $can_tailscale_proxy_reach_bridge,
        public bool|null $can_tailscale_proxy_reach_tailscale_network,
        public bool|null $is_bridge_socks_server_healthy,
        public bool|null $is_tailscale_proxy_reachable,
        public bool|null $is_tailscale_proxy_socks_server_healthy
    ) {}
}
