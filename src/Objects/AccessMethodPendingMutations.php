<?php

namespace Seam\Objects;

class AccessMethodPendingMutations
{
    public static function from_json(
        mixed $json,
    ): AccessMethodPendingMutations|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            from: AccessMethodFrom::from_json($json->from),
            message: $json->message,
            mutation_code: $json->mutation_code,
            to: AccessMethodTo::from_json($json->to),
        );
    }

    public function __construct(
        public string $created_at,
        public AccessMethodFrom $from,
        public string $message,
        public string $mutation_code,
        public AccessMethodTo $to,
    ) {}
}
