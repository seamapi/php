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
            from: $json->from ?? null,
            mutation_code: $json->mutation_code ?? null,
            to: $json->to ?? null,
        );
    }

    public function __construct(
        public mixed $from,
        public string|null $mutation_code,
        public mixed $to,
    ) {}
}
