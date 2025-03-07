<?php

namespace Seam\Objects;

class Pagination
{
    public static function from_json(mixed $json): Pagination|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            has_next_page: $json->has_next_page,
            next_page_cursor: $json->next_page_cursor ?? null,
            next_page_url: $json->next_page_url ?? null
        );
    }

    public function __construct(
        public bool $has_next_page,
        public string|null $next_page_cursor,
        public string|null $next_page_url
    ) {
    }
}
