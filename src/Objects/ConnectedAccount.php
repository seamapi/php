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
            account_type: $json->account_type ?? null,
            account_type_display_name: $json->account_type_display_name,
            automatically_manage_new_devices: $json->automatically_manage_new_devices,
            connected_account_id: $json->connected_account_id ?? null,
            created_at: $json->created_at ?? null,
            custom_metadata: $json->custom_metadata,
            errors: array_map(
                fn($e) => ConnectedAccountErrors::from_json($e),
                $json->errors ?? []
            ),
            user_identifier: isset($json->user_identifier)
                ? ConnectedAccountUserIdentifier::from_json(
                    $json->user_identifier
                )
                : null,
            warnings: array_map(
                fn($w) => ConnectedAccountWarnings::from_json($w),
                $json->warnings ?? []
            )
        );
    }

    public function __construct(
        public string|null $account_type,
        public string $account_type_display_name,
        public bool $automatically_manage_new_devices,
        public string|null $connected_account_id,
        public string|null $created_at,
        public mixed $custom_metadata,
        public array $errors,
        public ConnectedAccountUserIdentifier|null $user_identifier,
        public array $warnings
    ) {
    }
}
