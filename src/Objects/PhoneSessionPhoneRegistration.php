<?php

namespace Seam\Objects;

class PhoneSessionPhoneRegistration
{
    public static function from_json(
        mixed $json,
    ): PhoneSessionPhoneRegistration|null {
        if (!$json) {
            return null;
        }
        return new self(
            is_being_activated: $json->is_being_activated,
            phone_registration_id: $json->phone_registration_id,
            provider_state: $json->provider_state ?? null,
            provider_name: $json->provider_name ?? null,
        );
    }

    public function __construct(
        public bool $is_being_activated,
        public string $phone_registration_id,
        public mixed $provider_state,
        public string|null $provider_name,
    ) {}
}
