<?php

namespace Seam\Objects;

class DeviceTimePairs
{
    public static function from_json(mixed $json): DeviceTimePairs|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            display_name: $json->display_name ?? null,
            end_time: $json->end_time ?? null,
            start_time: $json->start_time ?? null,
        );
    }

    public function __construct(
        public string|null $display_name,
        public string|null $end_time,
        public string|null $start_time,
    ) {}
}
