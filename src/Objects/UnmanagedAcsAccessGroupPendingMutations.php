<?php

namespace Seam\Objects;

class UnmanagedAcsAccessGroupPendingMutations
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAcsAccessGroupPendingMutations|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            message: $json->message,
            mutation_code: $json->mutation_code,
            from: isset($json->from)
                ? UnmanagedAcsAccessGroupFrom::from_json($json->from)
                : null,
            to: isset($json->to)
                ? UnmanagedAcsAccessGroupTo::from_json($json->to)
                : null,
        );
    }

    public function __construct(
        public string $created_at,
        public string $message,
        public string $mutation_code,
        public UnmanagedAcsAccessGroupFrom|null $from,
        public UnmanagedAcsAccessGroupTo|null $to,
    ) {}
}
