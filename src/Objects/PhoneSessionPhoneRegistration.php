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
            is_being_activated: $json->is_being_activated ?? null,
            phone_registration_id: $json->phone_registration_id ?? null,
            provider_name: $json->provider_name ?? null,
        );
    }

    public function __construct(
        public bool|null $is_being_activated,
        public string|null $phone_registration_id,
        public string|null $provider_name,
    ) {}
}
