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
            battery_level: $json->battery_level,
            customer_reference: $json->customer_reference,
            lock_id: $json->lock_id,
            lock_type: $json->lock_type,
            locked_state: $json->locked_state,
            has_custom_pin_subscription: $json->has_custom_pin_subscription ??
                null,
            model: $json->model ?? null,
        );
    }

    public function __construct(
        public string $battery_level,
        public string $customer_reference,
        public string $lock_id,
        public string $lock_type,
        public string $locked_state,
        public bool|null $has_custom_pin_subscription,
        public string|null $model,
    ) {}
}
