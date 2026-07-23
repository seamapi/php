<?php

namespace Seam\Objects;

class AccessCodePendingMutations
{
    public static function from_json(
        mixed $json,
    ): AccessCodePendingMutations|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            from: isset($json->from)
                ? AccessCodeFrom::from_json($json->from)
                : null,
            message: $json->message ?? null,
            mutation_code: $json->mutation_code ?? null,
            scheduled_at: $json->scheduled_at ?? null,
            to: isset($json->to) ? AccessCodeTo::from_json($json->to) : null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public AccessCodeFrom|null $from,
        public string|null $message,
        public string|null $mutation_code,
        public string|null $scheduled_at,
        public AccessCodeTo|null $to,
    ) {}
}
