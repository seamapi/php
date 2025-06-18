<?php

namespace Seam\Objects;

class PartnerResource
{
    public static function from_json(mixed $json): PartnerResource|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            customer_key: $json->customer_key,
            partner_resource_key: $json->partner_resource_key,
            partner_resource_type: $json->partner_resource_type,
            custom_metadata: $json->custom_metadata ?? null,
            description: $json->description ?? null,
            email_address: $json->email_address ?? null,
            ends_at: $json->ends_at ?? null,
            location_keys: $json->location_keys ?? null,
            name: $json->name ?? null,
            phone_number: $json->phone_number ?? null,
            starts_at: $json->starts_at ?? null,
            user_identity_key: $json->user_identity_key ?? null
        );
    }

    public function __construct(
        public string $customer_key,
        public string $partner_resource_key,
        public string $partner_resource_type,
        public mixed $custom_metadata,
        public string|null $description,
        public string|null $email_address,
        public string|null $ends_at,
        public array|null $location_keys,
        public string|null $name,
        public string|null $phone_number,
        public string|null $starts_at,
        public string|null $user_identity_key
    ) {
    }
}
