<?php

namespace Seam;

class Paginator
{
    private $request;
    private $params;
    private $pagination_cache = [];
    private const FIRST_PAGE = "FIRST_PAGE";

    public function __construct(callable $request, array $params = [])
    {
        $this->request = $request;
        $this->params = $params;
    }

    public function firstPage(): array
    {
        $request = $this->request;
        $params = $this->params;

        $params["on_response"] = fn($response) => $this->cachePagination(
            $response,
            self::FIRST_PAGE
        );

        $data = $request($params);

        return [$data, $this->pagination_cache[self::FIRST_PAGE]];
    }

    public function nextPage(string $next_page_cursor): array
    {
        if ($next_page_cursor === null) {
            throw new \InvalidArgumentException(
                "Cannot get the next page with a null next_page_cursor"
            );
        }

        $request = $this->request;
        $params = $this->params;

        $params["page_cursor"] = $next_page_cursor;
        $params["on_response"] = fn($response) => $this->cachePagination(
            $response,
            $next_page_cursor
        );

        $data = $request($params);

        return [$data, $this->pagination_cache[$next_page_cursor]];
    }

    private function cachePagination($response, $next_page_cursor)
    {
        $this->pagination_cache[$next_page_cursor] = $response->pagination;
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
