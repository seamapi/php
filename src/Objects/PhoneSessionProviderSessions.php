<?php

namespace Seam\Objects;

class PhoneSessionProviderSessions
{
    public static function from_json(
        mixed $json,
    ): PhoneSessionProviderSessions|null {
        if (!$json) {
            return null;
        }
        return new self(
            acs_credentials: array_map(
                fn($a) => PhoneSessionAcsCredentials::from_json($a),
                $json->acs_credentials ?? [],
            ),
            phone_registration: PhoneSessionPhoneRegistration::from_json(
                $json->phone_registration,
            ),
        );
    }

    public function __construct(
        public array $acs_credentials,
        public PhoneSessionPhoneRegistration $phone_registration,
    ) {}
}
