<?php

namespace Seam\Objects;

class DeviceOnlineTimeFrameOptions
{
    public static function from_json(
        mixed $json,
    ): DeviceOnlineTimeFrameOptions|null {
        if (!$json) {
            return null;
        }
        return new self(
            display_name: $json->display_name,
            end_date_recurrence_rule: $json->end_date_recurrence_rule ?? null,
            matching_start_end_time: $json->matching_start_end_time ?? null,
            max_duration: $json->max_duration ?? null,
            min_duration: $json->min_duration ?? null,
            start_date_recurrence_rule: $json->start_date_recurrence_rule ??
                null,
            time_pairs: array_map(
                fn($t) => DeviceTimePairs::from_json($t),
                $json->time_pairs ?? [],
            ),
            time_zone: $json->time_zone ?? null,
        );
    }

    public function __construct(
        public string $display_name,
        public string|null $end_date_recurrence_rule,
        public bool|null $matching_start_end_time,
        public string|null $max_duration,
        public string|null $min_duration,
        public string|null $start_date_recurrence_rule,
        public array|null $time_pairs,
        public string|null $time_zone,
    ) {}
}
