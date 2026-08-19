<?php

declare(strict_types=1);

namespace Tests;

use Seam\Pagination;
use Seam\Paginator;
use Seam\Seam;
use Tests\Support\FakeSeamConnectTestCase;
use Tests\Support\RecordingClient;

final class PaginatorTest extends FakeSeamConnectTestCase
{
    private function paginator(array $params = ["limit" => 2]): Paginator
    {
        $seam = $this->seam();

        return $seam->createPaginator(
            fn($p) => $seam->connected_accounts->list(...$p),
            $params,
        );
    }

    public function testCreatePaginatorReturnsAPaginator(): void
    {
        $this->assertInstanceOf(Paginator::class, $this->paginator());
    }

    public function testFirstPageReturnsTheFirstPage(): void
    {
        [$accounts, $pagination] = $this->paginator()->firstPage();

        $this->assertCount(2, $accounts);
        $this->assertInstanceOf(Pagination::class, $pagination);
        $this->assertTrue($pagination->has_next_page);
        $this->assertNotNull($pagination->next_page_cursor);
    }

    public function testNextPageReturnsTheNextPage(): void
    {
        $pages = $this->paginator();

        [$first, $pagination] = $pages->firstPage();
        [$second] = $pages->nextPage($pagination->next_page_cursor);

        $this->assertNotEmpty($second);

        $first_ids = array_map(
            fn($account) => $account->connected_account_id,
            $first,
        );
        $second_ids = array_map(
            fn($account) => $account->connected_account_id,
            $second,
        );

        $this->assertEmpty(array_intersect($first_ids, $second_ids));
    }

    /**
     * The paginator reads the pagination metadata through on_response, but
     * must not swallow a callback the caller passed in themselves.
     */
    public function testOnResponseParamIsChainedNotReplaced(): void
    {
        $seen = 0;

        [, $pagination] = $this->paginator([
            "limit" => 2,
            "on_response" => function ($response) use (&$seen): void {
                $seen++;
                $this->assertObjectHasProperty("pagination", $response);
            },
        ])->firstPage();

        $this->assertSame(1, $seen);
        // The paginator's own callback has to keep working as well.
        $this->assertTrue($pagination->has_next_page);
    }

    public function testNextPageRequiresACursor(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("next_page_cursor");

        $this->paginator()->nextPage(null);
    }

    public function testNextPageRejectsAnEmptyCursor(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->paginator()->nextPage("");
    }

    public function testLastPageHasNoNextPage(): void
    {
        $pages = $this->paginator(["limit" => 100]);

        [, $pagination] = $pages->firstPage();

        $this->assertFalse($pagination->has_next_page);
        $this->assertNull($pagination->next_page_cursor);
    }

    public function testFlattenToArrayReturnsEveryResource(): void
    {
        $all = $this->paginator()->flattenToArray();
        $expected = $this->seam()->connected_accounts->list();

        $this->assertCount(count($expected), $all);
    }

    public function testFlattenIteratesEveryResource(): void
    {
        $ids = [];

        foreach ($this->paginator()->flatten() as $account) {
            $ids[] = $account->connected_account_id;
        }

        $expected = $this->seam()->connected_accounts->list();

        $this->assertCount(count($expected), $ids);
        $this->assertSame(array_unique($ids), $ids);
    }

    public function testEndpointWithoutPaginationIsRejected(): void
    {
        $seam = $this->seam();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("unpaginated endpoint");

        $seam
            ->createPaginator(fn($p) => $seam->workspaces->list())
            ->firstPage();
    }

    private function pinned_cursor_paginator(string $cursor): Paginator
    {
        $recorder = RecordingClient::repeating(
            RecordingClient::json(200, [
                "connected_accounts" => [
                    ["connected_account_id" => "ca_1"],
                    ["connected_account_id" => "ca_2"],
                ],
                "pagination" => [
                    "has_next_page" => true,
                    "next_page_cursor" => $cursor,
                ],
                "ok" => true,
            ]),
            times: 200,
        );

        $seam = Seam::from_api_key(
            "seam_apikey_token",
            endpoint: "https://example.com",
            guzzle_options: $recorder->guzzle_options(),
            retries: 0,
        );

        return $seam->createPaginator(
            fn($p) => $seam->connected_accounts->list(...$p),
            ["limit" => 2],
        );
    }

    public function testFlattenToArrayStopsWhenTheCursorRepeats(): void
    {
        $all = $this->pinned_cursor_paginator("stuck")->flattenToArray();

        $this->assertCount(4, $all);
    }

    public function testFlattenStopsWhenTheCursorRepeats(): void
    {
        $ids = [];

        foreach ($this->pinned_cursor_paginator("stuck")->flatten() as $item) {
            $ids[] = $item->connected_account_id;
            $this->assertLessThan(10, count($ids), "flatten did not terminate");
        }

        $this->assertCount(4, $ids);
    }

    public function testACursorNamedFirstPageStillAdvances(): void
    {
        $pages = $this->pinned_cursor_paginator("FIRST_PAGE");

        [, $pagination] = $pages->firstPage();
        $this->assertSame("FIRST_PAGE", $pagination->next_page_cursor);

        [$second] = $pages->nextPage($pagination->next_page_cursor);

        $this->assertNotEmpty($second);
        $this->assertCount(4, $pages->flattenToArray());
    }
}
