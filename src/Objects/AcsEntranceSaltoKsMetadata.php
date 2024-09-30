<?php

namespace Seam\Objects;

class AcsEntranceSaltoKsMetadata
{
    public static function from_json(
        mixed $json
    ): AcsEntranceSaltoKsMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            battery_level: $json->battery_level,
            door_name: $json->door_name,
            intrusion_alarm: $json->intrusion_alarm,
            left_open_alarm: $json->left_open_alarm,
            lock_type: $json->lock_type,
            locked_state: $json->locked_state,
            online: $json->online,
            privacy_mode: $json->privacy_mode
        );
    }

    public function __construct(
        public string $battery_level,
        public string $door_name,
        public bool $intrusion_alarm,
        public bool $left_open_alarm,
        public string $lock_type,
        public string $locked_state,
        public bool $online,
        public bool $privacy_mode
    ) {
    }
}
