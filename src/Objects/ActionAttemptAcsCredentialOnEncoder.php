<?php

namespace Seam\Objects;

class ActionAttemptAcsCredentialOnEncoder
{
    public static function from_json(
        mixed $json,
    ): ActionAttemptAcsCredentialOnEncoder|null {
        if (!$json) {
            return null;
        }
        return new self(
            card_number: $json->card_number ?? null,
            created_at: $json->created_at ?? null,
            ends_at: $json->ends_at ?? null,
            is_issued: $json->is_issued ?? null,
            starts_at: $json->starts_at ?? null,
            visionline_metadata: isset($json->visionline_metadata)
                ? ActionAttemptVisionlineMetadata::from_json(
                    $json->visionline_metadata,
                )
                : null,
        );
    }

    public function __construct(
        public string|null $card_number,
        public string|null $created_at,
        public string|null $ends_at,
        public bool|null $is_issued,
        public string|null $starts_at,
        public ActionAttemptVisionlineMetadata|null $visionline_metadata,
    ) {}
}
