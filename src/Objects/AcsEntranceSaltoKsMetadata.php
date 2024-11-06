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
            lock_type: $json->lock_type,
            locked_state: $json->locked_state,
            intrusion_alarm: $json->intrusion_alarm ?? null,
            left_open_alarm: $json->left_open_alarm ?? null,
            online: $json->online ?? null,
            privacy_mode: $json->privacy_mode ?? null
        );
    }

    public function __construct(
        public string $battery_level,
        public string $door_name,
        public string $lock_type,
        public string $locked_state,
        public bool|null $intrusion_alarm,
        public bool|null $left_open_alarm,
        public bool|null $online,
        public bool|null $privacy_mode
    ) {
    }
}
