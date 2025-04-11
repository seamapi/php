<?php

namespace Seam\Objects;

class UnmanagedAcsUserPendingMutations
{
    public static function from_json(
        mixed $json
    ): UnmanagedAcsUserPendingMutations|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at,
            message: $json->message,
            mutation_code: $json->mutation_code,
            from: isset($json->from)
                ? UnmanagedAcsUserFrom::from_json($json->from)
                : null,
            to: isset($json->to)
                ? UnmanagedAcsUserTo::from_json($json->to)
                : null
        );
    }

    public function __construct(
        public string $created_at,
        public string $message,
        public string $mutation_code,
        public UnmanagedAcsUserFrom|null $from,
        public UnmanagedAcsUserTo|null $to
    ) {
    }
}
