<?php

namespace Seam\Objects;

class DeviceFeatures
{
    public static function from_json(mixed $json): DeviceFeatures|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            auto_lock_time_config: $json->auto_lock_time_config ?? null,
            incomplete_keyboard_passcode: $json->incomplete_keyboard_passcode ??
                null,
            lock_command: $json->lock_command ?? null,
            passcode: $json->passcode ?? null,
            passcode_management: $json->passcode_management ?? null,
            unlock_via_gateway: $json->unlock_via_gateway ?? null,
            wifi: $json->wifi ?? null,
        );
    }

    public function __construct(
        public bool|null $auto_lock_time_config,
        public bool|null $incomplete_keyboard_passcode,
        public bool|null $lock_command,
        public bool|null $passcode,
        public bool|null $passcode_management,
        public bool|null $unlock_via_gateway,
        public bool|null $wifi,
    ) {}
}
