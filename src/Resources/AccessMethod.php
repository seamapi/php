<?php

namespace Seam\Resources {
    /**
     * Represents an access method for an Access Grant. Access methods describe the modes of access, such as PIN codes, plastic cards, and mobile keys. For a mobile key, the access method also stores the URL for the associated Instant Key.
     */
    class AccessMethod
    {
        public static function from_json(mixed $json): AccessMethod|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_method_id: $json->access_method_id ?? null,
                client_session_token: $json->client_session_token ?? null,
                code: $json->code ?? null,
                created_at: $json->created_at ?? null,
                customization_profile_id: $json->customization_profile_id ??
                    null,
                display_name: $json->display_name ?? null,
                errors: array_map(
                    fn($e) => AccessMethod\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                instant_key_url: $json->instant_key_url ?? null,
                is_assignment_required: $json->is_assignment_required ?? null,
                is_encoding_required: $json->is_encoding_required ?? null,
                is_issued: $json->is_issued ?? null,
                is_ready_for_assignment: $json->is_ready_for_assignment ?? null,
                is_ready_for_encoding: $json->is_ready_for_encoding ?? null,
                issued_at: $json->issued_at ?? null,
                mode: $json->mode ?? null,
                pending_mutations: array_map(
                    fn($p) => AccessMethod\PendingMutations::from_json($p),
                    $json->pending_mutations ?? [],
                ),
                warnings: array_map(
                    fn($w) => AccessMethod\Warnings::from_json($w),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the access method.
             */
            public string|null $access_method_id,
            /**
             * Token of the client session associated with the access method.
             */
            public string|null $client_session_token,
            /**
             * The actual PIN code for code access methods.
             */
            public string|null $code,
            /**
             * Date and time at which the access method was created.
             */
            public string|null $created_at,
            /**
             * ID of the customization profile associated with the access method.
             */
            public string|null $customization_profile_id,
            /**
             * Display name of the access method.
             */
            public string|null $display_name,
            /**
             * Errors associated with the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant).
             */
            public array $errors,
            /**
             * URL of the Instant Key for mobile key access methods.
             */
            public string|null $instant_key_url,
            /**
             * Indicates whether an existing card credential must be assigned to this access method before it can be issued. Only applies to card-mode access methods on systems that support credential assignment.
             */
            public bool|null $is_assignment_required,
            /**
             * Indicates whether encoding with an card encoder is required to issue or reissue the plastic card associated with the access method.
             */
            public bool|null $is_encoding_required,
            /**
             * Indicates whether the access method has been issued.
             */
            public bool|null $is_issued,
            /**
             * Indicates whether the access method is ready for card assignment. This is true when the access method is in card mode, has not yet been issued, and the system supports credential assignment.
             */
            public bool|null $is_ready_for_assignment,
            /**
             * Indicates whether the access method is ready to be encoded. This is true when the credential has been created and the card has not yet been issued.
             */
            public bool|null $is_ready_for_encoding,
            /**
             * Date and time at which the access method was issued.
             */
            public string|null $issued_at,
            /**
             * Access method mode. Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
             */
            public string|null $mode,
            /**
             * Pending mutations for the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant). Indicates operations that are in progress.
             */
            public array $pending_mutations,
            /**
             * Warnings associated with the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant).
             */
            public array $warnings,
            /**
             * ID of the Seam workspace associated with the access method.
             */
            public string|null $workspace_id,
        ) {}
    }
}

namespace Seam\Resources\AccessMethod {
    /**
     * Errors associated with the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant).
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
     * Pending mutations for the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant). Indicates operations that are in progress.
     */
    class PendingMutations
    {
        public static function from_json(mixed $json): PendingMutations|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? PendingMutations\From::from_json($json->from)
                    : null,
                message: $json->message ?? null,
                mutation_code: $json->mutation_code ?? null,
                to: isset($json->to)
                    ? PendingMutations\To::from_json($json->to)
                    : null,
            );
        }

        public function __construct(
            /**
             * Date and time at which the mutation was created.
             */
            public string|null $created_at,
            public PendingMutations\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            public string|null $message,
            public string|null $mutation_code,
            public PendingMutations\To|null $to,
        ) {}
    }

    /**
     * Warnings associated with the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant).
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
                original_access_method_id: $json->original_access_method_id ??
                    null,
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
             * ID of the original access method from which this backup access method was split, if applicable.
             */
            public string|null $original_access_method_id,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             */
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\AccessMethod\PendingMutations {
    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_ids: $json->device_ids ?? null,
                ends_at: $json->ends_at ?? null,
                starts_at: $json->starts_at ?? null,
            );
        }

        public function __construct(
            public array|null $device_ids,
            /**
             * Previous end time for access.
             */
            public string|null $ends_at,
            /**
             * Previous start time for access.
             */
            public string|null $starts_at,
        ) {}
    }

    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_ids: $json->device_ids ?? null,
                ends_at: $json->ends_at ?? null,
                starts_at: $json->starts_at ?? null,
            );
        }

        public function __construct(
            public array|null $device_ids,
            /**
             * New end time for access.
             */
            public string|null $ends_at,
            /**
             * New start time for access.
             */
            public string|null $starts_at,
        ) {}
    }
}
