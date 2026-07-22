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
            access_method_ids: $json->access_method_ids ?? null,
            created_at: $json->created_at ?? null,
            from: isset($json->from)
                ? AccessGrantFrom::from_json($json->from)
                : null,
            message: $json->message ?? null,
            mutation_code: $json->mutation_code ?? null,
            to: isset($json->to) ? AccessGrantTo::from_json($json->to) : null,
        );
    }

    public function __construct(
        public array|null $access_method_ids,
        public string|null $created_at,
        public AccessGrantFrom|null $from,
        public string|null $message,
        public string|null $mutation_code,
        public AccessGrantTo|null $to,
    ) {}
}
