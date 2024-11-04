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
            gadget_id: $json->gadget_id,
            gadget_name: $json->gadget_name,
            product_name: $json->product_name,
            _member_group_id: $json->_member_group_id ?? null
        );
    }

    public function __construct(
        public string $gadget_id,
        public string $gadget_name,
        public string $product_name,
        public string|null $_member_group_id
    ) {
    }
}
