<?php

namespace Seam\Objects;

class AccessCode
{
    public static function from_json(mixed $json): AccessCode|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            access_code_id: $json->access_code_id ?? null,
            name: $json->name ?? null,
            created_at: $json->created_at ?? null,
            type: $json->type ?? null,
            code: $json->code ?? null,
            status: $json->status ?? null,
            starts_at: $json->starts_at ?? null,
            ends_at: $json->starts_at ?? null,
            errors: array_map(
                fn($e) => SeamError::from_json($e),
                $json->access_code_errors ?? []
            )
        );
    }

    public function __construct(
        public string $access_code_id,
        public string | null $name,

        /* "time_bound" or "ongoing" */
        public string $type,

        /*
         * The status of an access code on the device.
         * unset -> setting -> set -> unset OR "unknown" if the account is disconnected
         */
        public string $status,

        /* In ISO8601 timestamp format, only for time_bound codes */
        public string|null $starts_at,

        /* In ISO8601 timestamp format, only for time_bound codes */
        public string|null $ends_at,

        /*
         * The 4-8 digit code assigned to the device, note that this isn't always
         * immediately available after creating the access code.
         */
        public string $code,
        public string $created_at,

        /* @var SeamError[] */
        public array $errors
    ) {
    }
}
