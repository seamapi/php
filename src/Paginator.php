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
    private ?Pagination $pagination = null;

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
        return $this->fetchPage(null);
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
    private function fetchPage(?string $cursor): array
    {
        $request = $this->request;
        $params = $this->params;

        if ($cursor !== null) {
            $params["page_cursor"] = $cursor;
        }

        // Chained rather than replaced, so a callback the caller passed in
        // through the params still fires.
        $on_response = $params["on_response"] ?? null;

        $this->pagination = null;

        $params["on_response"] = function ($response) use ($on_response): void {
            $this->readPagination($response);

            if (is_callable($on_response)) {
                $on_response($response);
            }
        };

        $data = $request($params);

        if ($this->pagination === null) {
            throw new \InvalidArgumentException(
                "Cannot use a paginator with an unpaginated endpoint",
            );
        }

        return [$data, $this->pagination];
    }

    private function readPagination($response): void
    {
        if (!is_object($response) || !isset($response->pagination)) {
            throw new \InvalidArgumentException(
                "Cannot use a paginator with an unpaginated endpoint",
            );
        }

        $this->pagination = Pagination::from_json($response->pagination);
    }

    public function flattenToArray(): array
    {
        $items = [];

        foreach ($this->walk() as [$response]) {
            $items = array_merge($items, $response);
        }

        return $items;
    }

    public function flatten()
    {
        foreach ($this->walk() as [$response]) {
            foreach ($response as $item) {
                yield $item;
            }
        }
    }

    /**
     * @return \Generator<array{0: array, 1: Pagination}>
     */
    private function walk(): \Generator
    {
        $page = $this->firstPage();
        $seen = [];

        yield $page;

        while ($page[1]->has_next_page) {
            $cursor = $page[1]->next_page_cursor;

            if ($cursor === null || isset($seen[$cursor])) {
                return;
            }

            $seen[$cursor] = true;

            $page = $this->nextPage($cursor);

            yield $page;
        }
    }
}
