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

        $params["on_response"] = fn($response) => $this->cachePagination(
            $response,
            $cursor,
        );

        $data = $request($params);

        return [$data, $this->pagination_cache[$cursor] ?? new Pagination()];
    }

    private function cachePagination($response, string $cursor): void
    {
        $this->pagination_cache[$cursor] = Pagination::from_json(
            $response->pagination ?? null,
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
