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
            display_name: $json->display_name,
            end_time: $json->end_time,
            start_time: $json->start_time,
        );
    }

    public function __construct(
        public string $display_name,
        public string $end_time,
        public string $start_time,
    ) {}
}
