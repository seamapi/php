<?php

namespace Seam\Resources {
    /**
     * Represents a [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) associated with an application user account.
     */
    class UserIdentity
    {
        public static function from_json(mixed $json): UserIdentity|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_user_ids: $json->acs_user_ids ?? null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                email_address: $json->email_address ?? null,
                errors: array_map(
                    fn($e) => \Seam\Resources\UserIdentity\Errors::from_json(
                        $e,
                    ),
                    $json->errors ?? [],
                ),
                full_name: $json->full_name ?? null,
                phone_number: $json->phone_number ?? null,
                user_identity_id: $json->user_identity_id ?? null,
                user_identity_key: $json->user_identity_key ?? null,
                warnings: array_map(
                    fn($w) => \Seam\Resources\UserIdentity\Warnings::from_json(
                        $w,
                    ),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
            );
        }

        public function __construct(
            /**
             * Array of access system user IDs associated with the user identity.
             *
             * @var list<string>|null
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
             *
             * @var list<\Seam\Resources\UserIdentity\Errors>
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
             * Unique key for the user identity.
             */
            public string|null $user_identity_key,
            /**
             * Array of warnings associated with the user identity. Each warning object within the array contains two fields: "warning_code" and "message." "warning_code" is a string that uniquely identifies the type of warning, enabling quick recognition and categorization of the issue. "message" provides a more detailed description of the warning, offering insights into the issue and potentially how to rectify it.
             *
             * @var list<\Seam\Resources\UserIdentity\Warnings>
             */
            public array $warnings,
            /**
             * ID of the workspace that contains the user identity.
             */
            public string|null $workspace_id,
        ) {}
    }
}

namespace Seam\Resources\UserIdentity {
    /**
     * Array of errors associated with the user identity. Each error object within the array contains fields like "error_code" and "message." "error_code" is a string that uniquely identifies the type of error, enabling quick recognition and categorization of the issue. "message" provides a more detailed description of the error, offering insights into the issue and potentially how to rectify it. Known error_code values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->error_code ?? null)
                ? \Seam\Resources\UserIdentity\Errors\ErrorCode::tryFrom(
                    $json->error_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\UserIdentity\Errors\ErrorCode::ISSUE_WITH_ACS_USER
                    => \Seam\Resources\UserIdentity\Errors\IssueWithAcsUser::from_json(
                    $json,
                ),
                default => new self(
                    acs_system_id: $json->acs_system_id ?? null,
                    acs_user_id: $json->acs_user_id ?? null,
                    created_at: $json->created_at ?? null,
                    error_code: $json->error_code ?? null,
                    message: $json->message ?? null,
                ),
            };
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
             *
             * @var value-of<\Seam\Resources\UserIdentity\Errors\ErrorCode>|string|null
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Array of warnings associated with the user identity. Each warning object within the array contains two fields: "warning_code" and "message." "warning_code" is a string that uniquely identifies the type of warning, enabling quick recognition and categorization of the issue. "message" provides a more detailed description of the warning, offering insights into the issue and potentially how to rectify it. Known warning_code values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->warning_code ?? null)
                ? \Seam\Resources\UserIdentity\Warnings\WarningCode::tryFrom(
                    $json->warning_code,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\UserIdentity\Warnings\WarningCode::BEING_DELETED
                    => \Seam\Resources\UserIdentity\Warnings\BeingDeleted::from_json(
                    $json,
                ),
                \Seam\Resources\UserIdentity\Warnings\WarningCode::ACS_USER_PROFILE_DOES_NOT_MATCH_USER_IDENTITY
                    => \Seam\Resources\UserIdentity\Warnings\AcsUserProfileDoesNotMatchUserIdentity::from_json(
                    $json,
                ),
                default => new self(
                    created_at: $json->created_at ?? null,
                    message: $json->message ?? null,
                    warning_code: $json->warning_code ?? null,
                ),
            };
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
             *
             * @var value-of<\Seam\Resources\UserIdentity\Warnings\WarningCode>|string|null
             */
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\UserIdentity\Errors {
    /**
     * Indicates that there is an issue with an access system user associated with this user identity.
     */
    final class IssueWithAcsUser extends \Seam\Resources\UserIdentity\Errors
    {
        public static function from_json(mixed $json): IssueWithAcsUser|null
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
            string|null $acs_system_id,
            /**
             * ID of the access system user that has an issue.
             */
            string|null $acs_user_id,
            /**
             * Date and time at which Seam created the error.
             */
            string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UserIdentity\Errors\ErrorCode>|string|null
             */
            string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
        ) {
            parent::__construct(
                acs_system_id: $acs_system_id,
                acs_user_id: $acs_user_id,
                created_at: $created_at,
                error_code: $error_code,
                message: $message,
            );
        }
    }

    enum ErrorCode: string
    {
        case ISSUE_WITH_ACS_USER = "issue_with_acs_user";
    }
}

namespace Seam\Resources\UserIdentity\Warnings {
    /**
     * Indicates that the user identity is currently being deleted.
     */
    final class BeingDeleted extends \Seam\Resources\UserIdentity\Warnings
    {
        public static function from_json(mixed $json): BeingDeleted|null
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
            string|null $created_at,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UserIdentity\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    /**
     * Indicates that the ACS user's profile does not match the user identity's profile
     */
    final class AcsUserProfileDoesNotMatchUserIdentity extends
        \Seam\Resources\UserIdentity\Warnings
    {
        public static function from_json(
            mixed $json,
        ): AcsUserProfileDoesNotMatchUserIdentity|null {
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
            string|null $created_at,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             *
             * @var value-of<\Seam\Resources\UserIdentity\Warnings\WarningCode>|string|null
             */
            string|null $warning_code,
        ) {
            parent::__construct(
                created_at: $created_at,
                message: $message,
                warning_code: $warning_code,
            );
        }
    }

    enum WarningCode: string
    {
        case BEING_DELETED = "being_deleted";
        case ACS_USER_PROFILE_DOES_NOT_MATCH_USER_IDENTITY = "acs_user_profile_does_not_match_user_identity";
    }
}
