<?php

namespace Seam\Objects;

class BridgeClientSession
{
    public static function from_json(mixed $json): BridgeClientSession|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            bridge_client_machine_identifier_key: $json->bridge_client_machine_identifier_key ??
                null,
            bridge_client_name: $json->bridge_client_name ?? null,
            bridge_client_session_id: $json->bridge_client_session_id ?? null,
            bridge_client_session_token: $json->bridge_client_session_token ??
                null,
            bridge_client_time_zone: $json->bridge_client_time_zone ?? null,
            created_at: $json->created_at ?? null,
            errors: array_map(
                fn($e) => BridgeClientSessionErrors::from_json($e),
                $json->errors ?? [],
            ),
            pairing_code: $json->pairing_code ?? null,
            pairing_code_expires_at: $json->pairing_code_expires_at ?? null,
            tailscale_auth_key: $json->tailscale_auth_key ?? null,
            tailscale_hostname: $json->tailscale_hostname ?? null,
            telemetry_token: $json->telemetry_token ?? null,
            telemetry_token_expires_at: $json->telemetry_token_expires_at ??
                null,
            telemetry_url: $json->telemetry_url ?? null,
        );
    }

    public function __construct(
        public string|null $bridge_client_machine_identifier_key,
        public string|null $bridge_client_name,
        public string|null $bridge_client_session_id,
        public string|null $bridge_client_session_token,
        public string|null $bridge_client_time_zone,
        public string|null $created_at,
        public array $errors,
        public string|null $pairing_code,
        public string|null $pairing_code_expires_at,
        public string|null $tailscale_auth_key,
        public string|null $tailscale_hostname,
        public string|null $telemetry_token,
        public string|null $telemetry_token_expires_at,
        public string|null $telemetry_url,
    ) {}
}
