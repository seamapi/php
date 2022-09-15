<?php

namespace Seam\Objects;

class ConnectedAccount
{
    public static function from_json(mixed $json): ConnectedAccount|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            connected_account_id: $json->connected_account_id,
            account_type: $json->account_type,
            user_identifier: UserIdentifier::from_json(
                $json->user_identifier ?? null
            ),
            created_at: $json->created_at,
            errors: array_map(
                fn($e) => SeamError::from_json($e),
                $json->connected_account_errors ?? []
            )
        );
    }

    public function __construct(
        public string $connected_account_id,
        public string $account_type,
        public UserIdentifier $user_identifier,
        public array $errors,
        public string $created_at
    ) {
    }
}
