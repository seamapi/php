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
            incomplete_keyboard_passcode: $json->incomplete_keyboard_passcode,
            lock_command: $json->lock_command,
            passcode: $json->passcode,
            passcode_management: $json->passcode_management,
            unlock_via_gateway: $json->unlock_via_gateway,
            wifi: $json->wifi
        );
    }

    public function __construct(
        public bool $incomplete_keyboard_passcode,
        public bool $lock_command,
        public bool $passcode,
        public bool $passcode_management,
        public bool $unlock_via_gateway,
        public bool $wifi
    ) {
    }
}
