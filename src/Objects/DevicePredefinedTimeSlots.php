<?php

namespace Seam\Objects;

class DevicePredefinedTimeSlots
{
    public static function from_json(
        mixed $json
    ): DevicePredefinedTimeSlots|null {
        if (!$json) {
            return null;
        }
        return new self(
            check_in_time: $json->check_in_time,
            check_out_time: $json->check_out_time,
            dormakaba_oracode_user_level_id: $json->dormakaba_oracode_user_level_id,
            ext_dormakaba_oracode_user_level_prefix: $json->ext_dormakaba_oracode_user_level_prefix,
            is_24_hour: $json->is_24_hour,
            is_biweekly_mode: $json->is_biweekly_mode,
            is_master: $json->is_master,
            is_one_shot: $json->is_one_shot,
            name: $json->name,
            prefix: $json->prefix
        );
    }

    public function __construct(
        public string $check_in_time,
        public string $check_out_time,
        public string $dormakaba_oracode_user_level_id,
        public float $ext_dormakaba_oracode_user_level_prefix,
        public bool $is_24_hour,
        public bool $is_biweekly_mode,
        public bool $is_master,
        public bool $is_one_shot,
        public string $name,
        public float $prefix
    ) {
    }
}
