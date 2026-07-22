<?php

namespace Seam\Objects;

class PhoneSession
{
    public static function from_json(mixed $json): PhoneSession|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            is_sandbox_workspace: $json->is_sandbox_workspace ?? null,
            provider_sessions: array_map(
                fn($p) => PhoneSessionProviderSessions::from_json($p),
                $json->provider_sessions ?? [],
            ),
            user_identity: isset($json->user_identity)
                ? PhoneSessionUserIdentity::from_json($json->user_identity)
                : null,
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public bool|null $is_sandbox_workspace,
        public array $provider_sessions,
        public PhoneSessionUserIdentity|null $user_identity,
        public string|null $workspace_id,
    ) {}
}
