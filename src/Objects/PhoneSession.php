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
            provider_sessions: array_map(
                fn($p) => PhoneSessionProviderSessions::from_json($p),
                $json->provider_sessions ?? []
            )
        );
    }

    public function __construct(public array $provider_sessions) {}
}
