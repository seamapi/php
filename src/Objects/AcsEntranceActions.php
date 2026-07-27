<?php

namespace Seam\Objects;

class AcsEntranceActions
{
    public static function from_json(mixed $json): AcsEntranceActions|null
    {
        if (!$json) {
            return null;
        }
        return new self(id: $json->id ?? null, name: $json->name ?? null);
    }

    public function __construct(
        public string|null $id,
        public string|null $name,
    ) {}
}
