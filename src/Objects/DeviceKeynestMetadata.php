<?php

namespace Seam\Objects;

class DeviceKeynestMetadata
{
    public static function from_json(mixed $json): DeviceKeynestMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            current_or_last_store_id: $json->current_or_last_store_id ?? null,
            default_office_id: $json->default_office_id ?? null,
            device_name: $json->device_name ?? null,
            fob_id: $json->fob_id ?? null,
            has_photo: $json->has_photo ?? null,
            is_quadient_locker: $json->is_quadient_locker ?? null,
            key_id: $json->key_id ?? null,
            last_movement: $json->last_movement ?? null,
            status_type: $json->status_type ?? null,
            subscription_plan: $json->subscription_plan ?? null,
            address: $json->address ?? null,
            current_status: $json->current_status ?? null,
            current_user_company: $json->current_user_company ?? null,
            current_user_email: $json->current_user_email ?? null,
            current_user_name: $json->current_user_name ?? null,
            current_user_phone_number: $json->current_user_phone_number ?? null,
            handover_method: $json->handover_method ?? null,
            key_notes: $json->key_notes ?? null,
            keynest_app_user: $json->keynest_app_user ?? null,
            property_id: $json->property_id ?? null,
            property_postcode: $json->property_postcode ?? null,
        );
    }

    public function __construct(
        public float|null $current_or_last_store_id,
        public float|null $default_office_id,
        public string|null $device_name,
        public float|null $fob_id,
        public bool|null $has_photo,
        public bool|null $is_quadient_locker,
        public string|null $key_id,
        public string|null $last_movement,
        public string|null $status_type,
        public string|null $subscription_plan,
        public string|null $address,
        public string|null $current_status,
        public string|null $current_user_company,
        public string|null $current_user_email,
        public string|null $current_user_name,
        public string|null $current_user_phone_number,
        public string|null $handover_method,
        public string|null $key_notes,
        public string|null $keynest_app_user,
        public string|null $property_id,
        public string|null $property_postcode,
    ) {}
}
