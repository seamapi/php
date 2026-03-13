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
            acs_access_group_id: $json->acs_access_group_id ?? null,
            from: isset($json->from)
                ? AcsUserFrom::from_json($json->from)
                : null,
            to: isset($json->to) ? AcsUserTo::from_json($json->to) : null,
            variant: $json->variant ?? null,
            scheduled_at: $json->scheduled_at ?? null,
        );
    }

    public function __construct(
        public string $created_at,
        public string $message,
        public string $mutation_code,
        public string|null $acs_access_group_id,
        public AcsUserFrom|null $from,
        public AcsUserTo|null $to,
        public string|null $variant,
        public string|null $scheduled_at,
    ) {}
}
