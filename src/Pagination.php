<?php

namespace Seam;

/**
 * The pagination metadata returned alongside a page of resources.
 */
class Pagination
{
    public function __construct(
        public readonly bool $has_next_page = false,
        public readonly ?string $next_page_cursor = null,
        public readonly ?string $next_page_url = null,
    ) {}

    /**
     * Builds the pagination from a response envelope, tolerating a response
     * that carries no pagination at all.
     */
    public static function from_json(mixed $json): self
    {
        if (!is_object($json)) {
            return new self();
        }

        return new self(
            has_next_page: (bool) ($json->has_next_page ?? false),
            next_page_cursor: $json->next_page_cursor ?? null,
            next_page_url: $json->next_page_url ?? null,
        );
    }
}
