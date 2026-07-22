<?php

namespace Seam\Objects;

class UnmanagedAcsUserPendingMutations
{
    public static function from_json(
        mixed $json,
    ): UnmanagedAcsUserPendingMutations|null {
        if (!$json) {
            return null;
        }
        return new self(
            acs_access_group_id: $json->acs_access_group_id ?? null,
            created_at: $json->created_at ?? null,
            from: isset($json->from)
                ? UnmanagedAcsUserFrom::from_json($json->from)
                : null,
            message: $json->message ?? null,
            mutation_code: $json->mutation_code ?? null,
            scheduled_at: $json->scheduled_at ?? null,
            to: isset($json->to)
                ? UnmanagedAcsUserTo::from_json($json->to)
                : null,
            variant: $json->variant ?? null,
        );
    }

    public function __construct(
        public string|null $acs_access_group_id,
        public string|null $created_at,
        public UnmanagedAcsUserFrom|null $from,
        public string|null $message,
        public string|null $mutation_code,
        public string|null $scheduled_at,
        public UnmanagedAcsUserTo|null $to,
        public string|null $variant,
    ) {}
}
