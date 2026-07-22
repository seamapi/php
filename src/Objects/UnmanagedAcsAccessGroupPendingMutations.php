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
            acs_user_id: $json->acs_user_id ?? null,
            created_at: $json->created_at ?? null,
            from: isset($json->from)
                ? UnmanagedAcsAccessGroupFrom::from_json($json->from)
                : null,
            message: $json->message ?? null,
            mutation_code: $json->mutation_code ?? null,
            to: isset($json->to)
                ? UnmanagedAcsAccessGroupTo::from_json($json->to)
                : null,
            variant: $json->variant ?? null,
        );
    }

    public function __construct(
        public string|null $acs_user_id,
        public string|null $created_at,
        public UnmanagedAcsAccessGroupFrom|null $from,
        public string|null $message,
        public string|null $mutation_code,
        public UnmanagedAcsAccessGroupTo|null $to,
        public string|null $variant,
    ) {}
}
