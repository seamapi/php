<?php

namespace Seam\Resources {
    /**
     * Represents a hardware device that encodes [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) data onto physical cards within an [access control system](https://docs.seam.co/low-level-apis/access-systems).
     *
     * Some access control systems require credentials to be encoded onto plastic key cards using a card encoder. This process involves the following two key steps:
     *
     * 1. Credential creation
     *    Configure the access parameters for the credential.
     * 2. Card encoding
     *    Write the credential data onto the card using a compatible card encoder.
     *
     * Separately, the Seam API also supports card scanning, which enables you to scan and read the encoded data on a card. You can use this action to confirm consistency with access control system records or diagnose discrepancies if needed.
     *
     * See [Working with Card Encoders and Scanners](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners).
     *
     * To verify if your access control system requires a card encoder, see the corresponding [system integration guide](https://docs.seam.co/device-and-system-integration-guides#access-control-systems).
     */
    class AcsEncoder
    {
        public static function from_json(mixed $json): AcsEncoder|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_encoder_id: $json->acs_encoder_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                errors: array_map(
                    fn($e) => \Seam\Resources\AcsEncoder\Errors::from_json($e),
                    $json->errors ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners).
             */
            public string|null $acs_encoder_id,
            /**
             * ID of the [access control system](https://docs.seam.co/low-level-apis/access-systems) that contains the [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners).
             */
            public string|null $acs_system_id,
            /**
             * ID of the connected account that contains the [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners).
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners) was created.
             */
            public string|null $created_at,
            /**
             * Display name for the [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners).
             */
            public string|null $display_name,
            /**
             * Errors associated with the [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners).
             *
             * @var list<\Seam\Resources\AcsEncoder\Errors>
             */
            public array $errors,
            /**
             * ID of the workspace that contains the [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners).
             */
            public string|null $workspace_id,
        ) {}
    }
}

namespace Seam\Resources\AcsEncoder {
    /**
     * Errors associated with the [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners).
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
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\AcsEncoder\Errors\ErrorCode::tryFrom(
                            $json->error_code,
                        ) ?? $json->error_code
                    : null,
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
            public \Seam\Resources\AcsEncoder\Errors\ErrorCode|string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }
}

namespace Seam\Resources\AcsEncoder\Errors {
    enum ErrorCode: string
    {
        case ACS_ENCODER_REMOVED = "acs_encoder_removed";
    }
}
