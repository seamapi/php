<?php

namespace Seam\Objects;

class AcsEntranceAkilesMetadata
{
    public static function from_json(
        mixed $json,
    ): AcsEntranceAkilesMetadata|null {
        if (!$json) {
            return null;
        }
        return new self(
            actions: array_map(
                fn($a) => AcsEntranceActions::from_json($a),
                $json->actions ?? [],
            ),
            gadget_id: $json->gadget_id ?? null,
            site_id: $json->site_id ?? null,
            site_name: $json->site_name ?? null,
        );
    }

    public function __construct(
        public array $actions,
        public string|null $gadget_id,
        public string|null $site_id,
        public string|null $site_name,
    ) {}
}
