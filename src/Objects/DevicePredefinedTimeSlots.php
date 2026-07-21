<?php

namespace Seam\Objects;

class DevicePredefinedTimeSlots
{
    public static function from_json(
        mixed $json,
    ): DevicePredefinedTimeSlots|null {
        if (!$json) {
            return null;
        }
        return new self(
            check_in_time: $json->check_in_time ?? null,
            check_out_time: $json->check_out_time ?? null,
            dormakaba_oracode_user_level_id: $json->dormakaba_oracode_user_level_id ??
                null,
            dormakaba_oracode_user_level_prefix: $json->dormakaba_oracode_user_level_prefix ??
                null,
            is_24_hour: $json->is_24_hour ?? null,
            is_biweekly_mode: $json->is_biweekly_mode ?? null,
            is_master: $json->is_master ?? null,
            is_one_shot: $json->is_one_shot ?? null,
            name: $json->name ?? null,
            prefix: $json->prefix ?? null,
        );
    }

    public function __construct(
        public string|null $check_in_time,
        public string|null $check_out_time,
        public string|null $dormakaba_oracode_user_level_id,
        public float|null $dormakaba_oracode_user_level_prefix,
        public bool|null $is_24_hour,
        public bool|null $is_biweekly_mode,
        public bool|null $is_master,
        public bool|null $is_one_shot,
        public string|null $name,
        public float|null $prefix,
    ) {}
}
