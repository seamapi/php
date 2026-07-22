<?php

namespace Seam\Objects;

class AcsAccessGroupPendingMutations
{
    public static function from_json(
        mixed $json,
    ): AcsAccessGroupPendingMutations|null {
        if (!$json) {
            return null;
        }
        return new self(
            acs_user_id: $json->acs_user_id ?? null,
            created_at: $json->created_at ?? null,
            from: isset($json->from)
                ? AcsAccessGroupFrom::from_json($json->from)
                : null,
            message: $json->message ?? null,
            mutation_code: $json->mutation_code ?? null,
            to: isset($json->to)
                ? AcsAccessGroupTo::from_json($json->to)
                : null,
            variant: $json->variant ?? null,
        );
    }

    public function __construct(
        public string|null $acs_user_id,
        public string|null $created_at,
        public AcsAccessGroupFrom|null $from,
        public string|null $message,
        public string|null $mutation_code,
        public AcsAccessGroupTo|null $to,
        public string|null $variant,
    ) {}
}
