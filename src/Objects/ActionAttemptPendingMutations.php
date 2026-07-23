<?php

namespace Seam\Objects;

class ActionAttemptPendingMutations
{
    public static function from_json(
        mixed $json,
    ): ActionAttemptPendingMutations|null {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            from: isset($json->from)
                ? ActionAttemptFrom::from_json($json->from)
                : null,
            message: $json->message ?? null,
            mutation_code: $json->mutation_code ?? null,
            to: isset($json->to) ? ActionAttemptTo::from_json($json->to) : null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public ActionAttemptFrom|null $from,
        public string|null $message,
        public string|null $mutation_code,
        public ActionAttemptTo|null $to,
    ) {}
}
