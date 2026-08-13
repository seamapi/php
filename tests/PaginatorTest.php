<?php

declare(strict_types=1);

namespace Tests;

use Seam\Pagination;
use Seam\Paginator;
use Tests\Support\FakeSeamConnectTestCase;

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

    /**
     * Not every list endpoint returns pagination metadata, and asking for a
     * page of one that does not should not fail.
     */
    public function testEndpointWithoutPaginationYieldsAnEmptyPagination(): void
    {
        $seam = $this->seam();

        $pages = $seam->createPaginator(fn($p) => $seam->workspaces->list());

        [$workspaces, $pagination] = $pages->firstPage();

        $this->assertNotEmpty($workspaces);
        $this->assertInstanceOf(Pagination::class, $pagination);
        $this->assertFalse($pagination->has_next_page);
        $this->assertNull($pagination->next_page_cursor);
    }
}
