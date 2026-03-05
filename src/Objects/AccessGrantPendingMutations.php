<?php

namespace Seam\Objects;

class AccessGrantPendingMutations
{
    public static function from_json(
        mixed $json,
    ): AccessGrantPendingMutations|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            from: AccessGrantFrom::from_json($json->from),
            message: $json->message,
            mutation_code: $json->mutation_code,
            to: AccessGrantTo::from_json($json->to),
            access_method_ids: $json->access_method_ids ?? null,
        );
    }

    public function __construct(
        public string $created_at,
        public AccessGrantFrom $from,
        public string $message,
        public string $mutation_code,
        public AccessGrantTo $to,
        public array|null $access_method_ids,
    ) {}
}
