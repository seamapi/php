<?php

namespace Seam;

/**
 * Fetches and walks the pages of a list endpoint.
 *
 * Create one with `Seam::createPaginator`, passing a callable that invokes the
 * list method with a params array.
 */
class Paginator
{
    private $request;
    private array $params;
    private array $pagination_cache = [];
    private const FIRST_PAGE = "FIRST_PAGE";

    public function __construct(callable $request, array $params = [])
    {
        $this->request = $request;
        $this->params = $params;
    }

    /**
     * @return array{0: array, 1: Pagination}
     */
    public function firstPage(): array
    {
        return $this->fetchPage(self::FIRST_PAGE);
    }

    /**
     * @return array{0: array, 1: Pagination}
     */
    public function nextPage(?string $next_page_cursor): array
    {
        if ($next_page_cursor === null || $next_page_cursor === "") {
            throw new \InvalidArgumentException(
                "Cannot get the next page without a next_page_cursor",
            );
        }

        return $this->fetchPage($next_page_cursor);
    }

    /**
     * @return array{0: array, 1: Pagination}
     */
    private function fetchPage(string $cursor): array
    {
        $request = $this->request;
        $params = $this->params;

        if ($cursor !== self::FIRST_PAGE) {
            $params["page_cursor"] = $cursor;
        }

        // Chained rather than replaced, so a callback the caller passed in
        // through the params still fires.
        $on_response = $params["on_response"] ?? null;

        $params["on_response"] = function ($response) use (
            $cursor,
            $on_response,
        ): void {
            $this->cachePagination($response, $cursor);

            if (is_callable($on_response)) {
                $on_response($response);
            }
        };

        $data = $request($params);

        if (!array_key_exists($cursor, $this->pagination_cache)) {
            throw new \InvalidArgumentException(
                "Cannot use a paginator with an unpaginated endpoint",
            );
        }

        return [$data, $this->pagination_cache[$cursor]];
    }

    private function cachePagination($response, string $cursor): void
    {
        if (!is_object($response) || !isset($response->pagination)) {
            throw new \InvalidArgumentException(
                "Cannot use a paginator with an unpaginated endpoint",
            );
        }

        $this->pagination_cache[$cursor] = Pagination::from_json(
            $response->pagination,
        );
    }

    public function flattenToArray(): array
    {
        $items = [];

        [$response, $pagination] = $this->firstPage();
        $items = array_merge($items, $response);

        while ($pagination->has_next_page) {
            [$response, $pagination] = $this->nextPage(
                $pagination->next_page_cursor,
            );
            $items = array_merge($items, $response);
        }

        return $items;
    }

    public function flatten()
    {
        [$current, $pagination] = $this->firstPage();

        foreach ($current as $item) {
            yield $item;
        }

        while ($pagination->has_next_page) {
            [$current, $pagination] = $this->nextPage(
                $pagination->next_page_cursor,
            );

            foreach ($current as $item) {
                yield $item;
            }
        }
    }
}
