<?php

namespace Seam\Objects;

class DeviceHoneywellResideoMetadata
{
    public static function from_json(
        mixed $json,
    ): DeviceHoneywellResideoMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            device_name: $json->device_name ?? null,
            honeywell_resideo_device_id: $json->honeywell_resideo_device_id ??
                null,
        );
    }

    public function __construct(
        public string|null $device_name,
        public string|null $honeywell_resideo_device_id,
    ) {}
}
