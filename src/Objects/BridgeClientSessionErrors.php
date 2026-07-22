<?php

namespace Seam\Objects;

class BridgeClientSessionErrors
{
    public static function from_json(
        mixed $json,
    ): BridgeClientSessionErrors|null {
        if (!$json) {
            return null;
        }
        return new self(
            can_tailscale_proxy_reach_bridge: $json->can_tailscale_proxy_reach_bridge ??
                null,
            can_tailscale_proxy_reach_tailscale_network: $json->can_tailscale_proxy_reach_tailscale_network ??
                null,
            created_at: $json->created_at ?? null,
            error_code: $json->error_code ?? null,
            is_bridge_socks_server_healthy: $json->is_bridge_socks_server_healthy ??
                null,
            is_tailscale_proxy_reachable: $json->is_tailscale_proxy_reachable ??
                null,
            is_tailscale_proxy_socks_server_healthy: $json->is_tailscale_proxy_socks_server_healthy ??
                null,
            message: $json->message ?? null,
        );
    }

    public function __construct(
        public bool|null $can_tailscale_proxy_reach_bridge,
        public bool|null $can_tailscale_proxy_reach_tailscale_network,
        public string|null $created_at,
        public string|null $error_code,
        public bool|null $is_bridge_socks_server_healthy,
        public bool|null $is_tailscale_proxy_reachable,
        public bool|null $is_tailscale_proxy_socks_server_healthy,
        public string|null $message,
    ) {}
}
