<?php

namespace Seam\Objects;

class AcsUserPendingMutations
{
    public static function from_json(mixed $json): AcsUserPendingMutations|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            message: $json->message,
            mutation_code: $json->mutation_code,
            from: isset($json->from)
                ? AcsUserFrom::from_json($json->from)
                : null,
            to: isset($json->to) ? AcsUserTo::from_json($json->to) : null
        );
    }

    public function __construct(
        public string $created_at,
        public string $message,
        public string $mutation_code,
        public AcsUserFrom|null $from,
        public AcsUserTo|null $to
    ) {
    }
}
