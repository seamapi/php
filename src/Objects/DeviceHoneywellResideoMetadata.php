<?php

namespace Seam\Objects;

class DeviceHoneywellResideoMetadata
{
    public static function from_json(
        mixed $json
    ): DeviceHoneywellResideoMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            device_name: $json->device_name,
            honeywell_resideo_device_id: $json->honeywell_resideo_device_id
        );
    }

    public function __construct(
        public string $device_name,
        public string $honeywell_resideo_device_id
    ) {
    }
}
