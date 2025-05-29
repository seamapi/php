<?php

namespace Seam\Objects;

class DeviceThermostatWeeklyProgram
{
    public static function from_json(
        mixed $json
    ): DeviceThermostatWeeklyProgram|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            friday_program_id: $json->friday_program_id ?? null,
            monday_program_id: $json->monday_program_id ?? null,
            saturday_program_id: $json->saturday_program_id ?? null,
            sunday_program_id: $json->sunday_program_id ?? null,
            thursday_program_id: $json->thursday_program_id ?? null,
            tuesday_program_id: $json->tuesday_program_id ?? null,
            wednesday_program_id: $json->wednesday_program_id ?? null
        );
    }

    public function __construct(
        public string $created_at,
        public string|null $friday_program_id,
        public string|null $monday_program_id,
        public string|null $saturday_program_id,
        public string|null $sunday_program_id,
        public string|null $thursday_program_id,
        public string|null $tuesday_program_id,
        public string|null $wednesday_program_id
    ) {
    }
}
