<?php

namespace Seam\Objects;

class ActionAttemptVisionlineMetadata
{
    public static function from_json(
        mixed $json,
    ): ActionAttemptVisionlineMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            cancelled: $json->cancelled ?? null,
            card_format: $json->card_format ?? null,
            card_holder: $json->card_holder ?? null,
            card_id: $json->card_id ?? null,
            common_acs_entrance_ids: $json->common_acs_entrance_ids ?? null,
            discarded: $json->discarded ?? null,
            expired: $json->expired ?? null,
            guest_acs_entrance_ids: $json->guest_acs_entrance_ids ?? null,
            number_of_issued_cards: $json->number_of_issued_cards ?? null,
            overridden: $json->overridden ?? null,
            overwritten: $json->overwritten ?? null,
            pending_auto_update: $json->pending_auto_update ?? null,
        );
    }

    public function __construct(
        public bool|null $cancelled,
        public string|null $card_format,
        public string|null $card_holder,
        public string|null $card_id,
        public array|null $common_acs_entrance_ids,
        public bool|null $discarded,
        public bool|null $expired,
        public array|null $guest_acs_entrance_ids,
        public float|null $number_of_issued_cards,
        public bool|null $overridden,
        public bool|null $overwritten,
        public bool|null $pending_auto_update,
    ) {}
}
