<?php

namespace Seam\Objects;

class DeviceAkilesMetadata
{
    public static function from_json(mixed $json): DeviceAkilesMetadata|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            _member_group_id: $json->_member_group_id ?? null,
            gadget_id: $json->gadget_id ?? null,
            gadget_name: $json->gadget_name ?? null,
            product_name: $json->product_name ?? null,
        );
    }

    public function __construct(
        public string|null $_member_group_id,
        public string|null $gadget_id,
        public string|null $gadget_name,
        public string|null $product_name,
    ) {}
}
