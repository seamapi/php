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
            accepted_capabilities: $json->accepted_capabilities,
            account_type_display_name: $json->account_type_display_name,
            automatically_manage_new_devices: $json->automatically_manage_new_devices,
            connected_account_id: $json->connected_account_id,
            custom_metadata: $json->custom_metadata,
            errors: array_map(
                fn($e) => ConnectedAccountErrors::from_json($e),
                $json->errors ?? [],
            ),
            warnings: array_map(
                fn($w) => ConnectedAccountWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            account_type: $json->account_type ?? null,
            created_at: $json->created_at ?? null,
            customer_key: $json->customer_key ?? null,
            user_identifier: isset($json->user_identifier)
                ? ConnectedAccountUserIdentifier::from_json(
                    $json->user_identifier,
                )
                : null,
        );
    }

    public function __construct(
        public array $accepted_capabilities,
        public string $account_type_display_name,
        public bool $automatically_manage_new_devices,
        public string $connected_account_id,
        public mixed $custom_metadata,
        public array $errors,
        public array $warnings,
        public string|null $account_type,
        public string|null $created_at,
        public string|null $customer_key,
        public ConnectedAccountUserIdentifier|null $user_identifier,
    ) {}
}
