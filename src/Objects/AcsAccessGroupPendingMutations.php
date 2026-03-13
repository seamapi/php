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
            created_at: $json->created_at,
            message: $json->message,
            mutation_code: $json->mutation_code,
            acs_user_id: $json->acs_user_id ?? null,
            from: isset($json->from)
                ? AcsAccessGroupFrom::from_json($json->from)
                : null,
            to: isset($json->to)
                ? AcsAccessGroupTo::from_json($json->to)
                : null,
            variant: $json->variant ?? null,
        );
    }

    public function __construct(
        public string $created_at,
        public string $message,
        public string $mutation_code,
        public string|null $acs_user_id,
        public AcsAccessGroupFrom|null $from,
        public AcsAccessGroupTo|null $to,
        public string|null $variant,
    ) {}
}
