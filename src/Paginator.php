<?php

namespace Seam;

class Paginator
{
    private $callable;
    private $params;
    private $pagination_cache = [];
    private const FIRST_PAGE = "FIRST_PAGE";

    public function __construct(callable $callable, array $params = [])
    {
        $this->callable = $callable;
        $this->params = $params;
    }

    public function firstPage(): array
    {
        $callable = $this->callable;
        $params = $this->params;

        $params["on_response"] = fn($response) => $this->cachePagination(
            $response,
            self::FIRST_PAGE
        );

        $data = $callable($params);

        return [$data, $this->pagination_cache[self::FIRST_PAGE]];
    }

    public function nextPage(string $nextPageCursor): array
    {
        $callable = $this->callable;
        $params = $this->params;

        $params["page_cursor"] = $nextPageCursor;
        $params["on_response"] = fn($response) => $this->cachePagination(
            $response,
            $nextPageCursor
        );

        $data = $callable($params);

        return [$data, $this->pagination_cache[$nextPageCursor]];
    }

    private function cachePagination($response, $nextPageCursor)
    {
        $this->pagination_cache[$nextPageCursor] = $response->pagination;
    }

    public function flattenToArray(): array
    {
        $items = [];

        [$response, $pagination] = $this->firstPage();
        $items = array_merge($items, $response);

        while ($pagination->has_next_page) {
            [$response, $pagination] = $this->nextPage(
                $pagination->next_page_cursor
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
                $pagination->next_page_cursor
            );

            foreach ($current as $item) {
                yield $item;
            }
        }
    }
}
