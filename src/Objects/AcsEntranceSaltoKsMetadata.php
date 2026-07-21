<?php

namespace Seam\Objects;

class AcsEntranceSaltoKsMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsEntranceSaltoKsMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            battery_level: $json->battery_level ?? null,
            door_name: $json->door_name ?? null,
            intrusion_alarm: $json->intrusion_alarm ?? null,
            left_open_alarm: $json->left_open_alarm ?? null,
            lock_type: $json->lock_type ?? null,
            locked_state: $json->locked_state ?? null,
            online: $json->online ?? null,
            privacy_mode: $json->privacy_mode ?? null,
        );
    }

    public function __construct(
        public string|null $battery_level,
        public string|null $door_name,
        public bool|null $intrusion_alarm,
        public bool|null $left_open_alarm,
        public string|null $lock_type,
        public string|null $locked_state,
        public bool|null $online,
        public bool|null $privacy_mode,
    ) {}
}
