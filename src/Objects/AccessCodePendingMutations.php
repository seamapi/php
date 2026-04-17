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
            created_at: $json->created_at,
            message: $json->message,
            mutation_code: $json->mutation_code,
            from: isset($json->from)
                ? AccessCodeFrom::from_json($json->from)
                : null,
            scheduled_at: $json->scheduled_at ?? null,
            to: isset($json->to) ? AccessCodeTo::from_json($json->to) : null,
        );
    }

    public function __construct(
        public string $created_at,
        public string $message,
        public string $mutation_code,
        public AccessCodeFrom|null $from,
        public string|null $scheduled_at,
        public AccessCodeTo|null $to,
    ) {}
}
