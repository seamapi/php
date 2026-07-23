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
            created_at: $json->created_at ?? null,
            from: isset($json->from)
                ? AccessMethodFrom::from_json($json->from)
                : null,
            message: $json->message ?? null,
            mutation_code: $json->mutation_code ?? null,
            to: isset($json->to) ? AccessMethodTo::from_json($json->to) : null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public AccessMethodFrom|null $from,
        public string|null $message,
        public string|null $mutation_code,
        public AccessMethodTo|null $to,
    ) {}
}
