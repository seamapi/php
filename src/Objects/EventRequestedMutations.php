<?php

namespace Seam\Objects;

class EventRequestedMutations
{
    public static function from_json(mixed $json): EventRequestedMutations|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            mutation_code: $json->mutation_code,
            from: $json->from ?? null,
            to: $json->to ?? null,
        );
    }

    public function __construct(
        public string $mutation_code,
        public mixed $from,
        public mixed $to,
    ) {}
}
