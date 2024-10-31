<?php

namespace Seam\Objects;

class AcsEntranceAssaAbloyVostioMetadata
{
    public static function from_json(
        mixed $json
    ): AcsEntranceAssaAbloyVostioMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            door_name: $json->door_name,
            door_type: $json->door_type,
            door_number: $json->door_number ?? null,
            pms_id: $json->pms_id ?? null,
            stand_open: $json->stand_open ?? null
        );
    }

    public function __construct(
        public string $door_name,
        public string $door_type,
        public float|null $door_number,
        public string|null $pms_id,
        public bool|null $stand_open
    ) {
    }
}
