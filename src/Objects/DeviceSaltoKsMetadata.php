<?php

namespace Seam\Objects;

class DeviceSaltoKsMetadata
{
    public static function from_json(mixed $json): DeviceSaltoKsMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            battery_level: $json->battery_level ?? null,
            customer_reference: $json->customer_reference ?? null,
            has_custom_pin_subscription: $json->has_custom_pin_subscription ??
                null,
            lock_id: $json->lock_id ?? null,
            lock_type: $json->lock_type ?? null,
            locked_state: $json->locked_state ?? null,
            model: $json->model ?? null,
            site_id: $json->site_id ?? null,
            site_name: $json->site_name ?? null,
        );
    }

    public function __construct(
        public string|null $battery_level,
        public string|null $customer_reference,
        public bool|null $has_custom_pin_subscription,
        public string|null $lock_id,
        public string|null $lock_type,
        public string|null $locked_state,
        public string|null $model,
        public string|null $site_id,
        public string|null $site_name,
    ) {}
}
