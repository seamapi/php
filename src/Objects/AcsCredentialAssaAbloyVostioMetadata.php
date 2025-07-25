<?php

namespace Seam\Objects;

class AcsCredentialAssaAbloyVostioMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsCredentialAssaAbloyVostioMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            auto_join: $json->auto_join ?? null,
            door_names: $json->door_names ?? null,
            endpoint_id: $json->endpoint_id ?? null,
            key_id: $json->key_id ?? null,
            key_issuing_request_id: $json->key_issuing_request_id ?? null,
            override_guest_acs_entrance_ids: $json->override_guest_acs_entrance_ids ??
                null,
        );
    }

    public function __construct(
        public bool|null $auto_join,
        public array|null $door_names,
        public string|null $endpoint_id,
        public string|null $key_id,
        public string|null $key_issuing_request_id,
        public array|null $override_guest_acs_entrance_ids,
    ) {}
}
