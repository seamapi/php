<?php

namespace Seam\Resources;

class Phone
{
    public static function from_json(mixed $json): Phone|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            custom_metadata: $json->custom_metadata ?? null,
            device_id: $json->device_id ?? null,
            device_type: $json->device_type ?? null,
            display_name: $json->display_name ?? null,
            errors: array_map(
                fn($e) => PhoneErrors::from_json($e),
                $json->errors ?? [],
            ),
            nickname: $json->nickname ?? null,
            properties: isset($json->properties)
                ? PhoneProperties::from_json($json->properties)
                : null,
            warnings: array_map(
                fn($w) => PhoneWarnings::from_json($w),
                $json->warnings ?? [],
            ),
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public mixed $custom_metadata,
        public string|null $device_id,
        public string|null $device_type,
        public string|null $display_name,
        public array $errors,
        public string|null $nickname,
        public PhoneProperties|null $properties,
        public array $warnings,
        public string|null $workspace_id,
    ) {}
}

class PhoneAssaAbloyCredentialServiceMetadata
{
    public static function from_json(
        mixed $json,
    ): PhoneAssaAbloyCredentialServiceMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            endpoints: array_map(
                fn($e) => PhoneEndpoints::from_json($e),
                $json->endpoints ?? [],
            ),
            has_active_endpoint: $json->has_active_endpoint ?? null,
        );
    }

    public function __construct(
        public array $endpoints,
        public bool|null $has_active_endpoint,
    ) {}
}

class PhoneEndpoints
{
    public static function from_json(mixed $json): PhoneEndpoints|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            endpoint_id: $json->endpoint_id ?? null,
            is_active: $json->is_active ?? null,
        );
    }

    public function __construct(
        public string|null $endpoint_id,
        public bool|null $is_active,
    ) {}
}

class PhoneErrors
{
    public static function from_json(mixed $json): PhoneErrors|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            error_code: $json->error_code ?? null,
            message: $json->message ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $error_code,
        public string|null $message,
    ) {}
}

class PhoneProperties
{
    public static function from_json(mixed $json): PhoneProperties|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            assa_abloy_credential_service_metadata: isset(
                $json->assa_abloy_credential_service_metadata,
            )
                ? PhoneAssaAbloyCredentialServiceMetadata::from_json(
                    $json->assa_abloy_credential_service_metadata,
                )
                : null,
            salto_space_credential_service_metadata: isset(
                $json->salto_space_credential_service_metadata,
            )
                ? PhoneSaltoSpaceCredentialServiceMetadata::from_json(
                    $json->salto_space_credential_service_metadata,
                )
                : null,
        );
    }

    public function __construct(
        public PhoneAssaAbloyCredentialServiceMetadata|null $assa_abloy_credential_service_metadata,
        public PhoneSaltoSpaceCredentialServiceMetadata|null $salto_space_credential_service_metadata,
    ) {}
}

class PhoneSaltoSpaceCredentialServiceMetadata
{
    public static function from_json(
        mixed $json,
    ): PhoneSaltoSpaceCredentialServiceMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(has_active_phone: $json->has_active_phone ?? null);
    }

    public function __construct(public bool|null $has_active_phone) {}
}

class PhoneWarnings
{
    public static function from_json(mixed $json): PhoneWarnings|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            message: $json->message ?? null,
            warning_code: $json->warning_code ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public string|null $message,
        public string|null $warning_code,
    ) {}
}
