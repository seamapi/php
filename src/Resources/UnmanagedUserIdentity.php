<?php

namespace Seam\Resources {
    /**
     * Represents an unmanaged user identity. Unmanaged user identities do not have keys.
     */
    class UnmanagedUserIdentity
    {
        public static function from_json(
            mixed $json,
        ): UnmanagedUserIdentity|null {
            if (!$json) {
                return null;
            }
            return new self(
                acs_user_ids: $json->acs_user_ids ?? null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                email_address: $json->email_address ?? null,
                errors: array_map(
                    fn($e) => UnmanagedUserIdentity\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                full_name: $json->full_name ?? null,
                phone_number: $json->phone_number ?? null,
                user_identity_id: $json->user_identity_id ?? null,
                warnings: array_map(
                    fn($w) => UnmanagedUserIdentity\Warnings::from_json($w),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
            );
        }

        public function __construct(
            /**
             * Array of access system user IDs associated with the user identity.
             */
            public array|null $acs_user_ids,
            /**
             * Date and time at which the user identity was created.
             */
            public string|null $created_at,
            /**
             * Display name for the user identity.
             */
            public string|null $display_name,
            /**
             * Unique email address for the user identity.
             */
            public string|null $email_address,
            /**
             * Array of errors associated with the user identity. Each error object within the array contains fields like "error_code" and "message." "error_code" is a string that uniquely identifies the type of error, enabling quick recognition and categorization of the issue. "message" provides a more detailed description of the error, offering insights into the issue and potentially how to rectify it.
             */
            public array $errors,
            /**
             * Full name of the user associated with the user identity.
             */
            public string|null $full_name,
            /**
             * Unique phone number for the user identity in [E.164 format](https://www.itu.int/rec/T-REC-E.164/en) (for example, +15555550100).
             */
            public string|null $phone_number,
            /**
             * ID of the user identity.
             */
            public string|null $user_identity_id,
            /**
             * Array of warnings associated with the user identity. Each warning object within the array contains two fields: "warning_code" and "message." "warning_code" is a string that uniquely identifies the type of warning, enabling quick recognition and categorization of the issue. "message" provides a more detailed description of the warning, offering insights into the issue and potentially how to rectify it.
             */
            public array $warnings,
            /**
             * ID of the workspace that contains the user identity.
             */
            public string|null $workspace_id,
        ) {}
    }
}

namespace Seam\Resources\UnmanagedUserIdentity {
    /**
     * Array of errors associated with the user identity. Each error object within the array contains fields like "error_code" and "message." "error_code" is a string that uniquely identifies the type of error, enabling quick recognition and categorization of the issue. "message" provides a more detailed description of the error, offering insights into the issue and potentially how to rectify it.
     */
    class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_system_id: $json->acs_system_id ?? null,
                acs_user_id: $json->acs_user_id ?? null,
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the access system that the user identity is associated with.
             */
            public string|null $acs_system_id,
            /**
             * ID of the access system user that has an issue.
             */
            public string|null $acs_user_id,
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Array of warnings associated with the user identity. Each warning object within the array contains two fields: "warning_code" and "message." "warning_code" is a string that uniquely identifies the type of warning, enabling quick recognition and categorization of the issue. "message" provides a more detailed description of the warning, offering insights into the issue and potentially how to rectify it.
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
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             */
            public string|null $warning_code,
        ) {}
    }
}
