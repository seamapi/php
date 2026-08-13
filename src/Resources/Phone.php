<?php

namespace Seam\Resources {
    /**
     * Represents an app user's mobile phone.
     */
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
                    fn($e) => Phone\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                nickname: $json->nickname ?? null,
                properties: isset($json->properties)
                    ? Phone\Properties::from_json($json->properties)
                    : null,
                warnings: array_map(
                    fn($w) => Phone\Warnings::from_json($w),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which the phone was created.
             */
            public string|null $created_at,
            /**
             * Optional [custom metadata](https://docs.seam.co/core-concepts/devices/adding-custom-metadata-to-a-device) for the phone.
             */
            public mixed $custom_metadata,
            /**
             * ID of the phone.
             */
            public string|null $device_id,
            /**
             * Type of the phone device, such as `ios_phone` or `android_phone`.
             */
            public string|null $device_type,
            /**
             * Display name of the phone. Defaults to `nickname` (if it is set) or `properties.appearance.name`, otherwise. Enables administrators and users to identify the phone easily, especially when there are numerous phones.
             */
            public string|null $display_name,
            /**
             * Errors associated with the phone.
             */
            public array $errors,
            /**
             * Optional nickname to describe the phone, settable through Seam.
             */
            public string|null $nickname = null,
            /**
             * Properties of the phone.
             */
            public Phone\Properties|null $properties,
            /**
             * Warnings associated with the phone.
             */
            public array $warnings,
            /**
             * ID of the workspace that contains the phone.
             */
            public string|null $workspace_id,
        ) {}
    }
}

namespace Seam\Resources\Phone {
    /**
     * Errors associated with the phone.
     */
    class Errors
    {
        public static function from_json(mixed $json): Errors|null
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
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at,
            /**
             * Unique identifier of the type of error.
             */
            public string|null $error_code,
            /**
             * Detailed description of the error.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Properties of the phone.
     */
    class Properties
    {
        public static function from_json(mixed $json): Properties|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                assa_abloy_credential_service_metadata: isset(
                    $json->assa_abloy_credential_service_metadata,
                )
                    ? Properties\AssaAbloyCredentialServiceMetadata::from_json(
                        $json->assa_abloy_credential_service_metadata,
                    )
                    : null,
                salto_space_credential_service_metadata: isset(
                    $json->salto_space_credential_service_metadata,
                )
                    ? Properties\SaltoSpaceCredentialServiceMetadata::from_json(
                        $json->salto_space_credential_service_metadata,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ASSA ABLOY Credential Service metadata for the phone.
             */
            public Properties\AssaAbloyCredentialServiceMetadata|null $assa_abloy_credential_service_metadata = null,
            /**
             * Salto Space credential service metadata for the phone.
             */
            public Properties\SaltoSpaceCredentialServiceMetadata|null $salto_space_credential_service_metadata = null,
        ) {}
    }

    /**
     * Warnings associated with the phone.
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
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
            /**
             * Date and time at which Seam created the warning.
             */
            public string|null $created_at,
            /**
             * Detailed description of the warning.
             */
            public string|null $message,
            /**
             * Unique identifier of the type of warning.
             */
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\Phone\Properties {
    /**
     * ASSA ABLOY Credential Service metadata for the phone.
     */
    class AssaAbloyCredentialServiceMetadata
    {
        public static function from_json(
            mixed $json,
        ): AssaAbloyCredentialServiceMetadata|null {
            if (!$json) {
                return null;
            }
            return new self(
                endpoints: array_map(
                    fn(
                        $e,
                    ) => AssaAbloyCredentialServiceMetadata\Endpoints::from_json(
                        $e,
                    ),
                    $json->endpoints ?? [],
                ),
                has_active_endpoint: $json->has_active_endpoint ?? null,
            );
        }

        public function __construct(
            /**
             * Endpoints associated with the phone.
             */
            public array|null $endpoints = null,
            /**
             * Indicates whether the credential service has active endpoints associated with the phone.
             */
            public bool|null $has_active_endpoint = null,
        ) {}
    }

    /**
     * Salto Space credential service metadata for the phone.
     */
    class SaltoSpaceCredentialServiceMetadata
    {
        public static function from_json(
            mixed $json,
        ): SaltoSpaceCredentialServiceMetadata|null {
            if (!$json) {
                return null;
            }
            return new self(has_active_phone: $json->has_active_phone ?? null);
        }

        public function __construct(
            /**
             * Indicates whether the credential service has an active associated phone.
             */
            public bool|null $has_active_phone = null,
        ) {}
    }
}

namespace Seam\Resources\Phone\Properties\AssaAbloyCredentialServiceMetadata {
    /**
     * Endpoints associated with the phone.
     */
    class Endpoints
    {
        public static function from_json(mixed $json): Endpoints|null
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
            /**
             * ID of the associated endpoint.
             */
            public string|null $endpoint_id = null,
            /**
             * Indicated whether the endpoint is active.
             */
            public bool|null $is_active = null,
        ) {}
    }
}
