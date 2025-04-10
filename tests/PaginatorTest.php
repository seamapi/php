<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use Seam\Paginator;

final class PaginatorTest extends TestCase
{
    public function testPaginatorFirstPage(): void
    {
        $seam = Fixture::getTestServer();

        $pages = $seam->createPaginator(
            fn($params) => $seam->connected_accounts->list(...$params),
            ["limit" => 2]
        );
        [$connectedAccounts, $pagination] = $pages->firstPage();

        $this->assertTrue(count($connectedAccounts) == 2);
        $this->assertTrue($pagination->has_next_page);
        $this->assertTrue($pagination->next_page_cursor !== null);
        $this->assertTrue($pagination->next_page_url !== null);
    }

    public function testPaginatorNextPage(): void
    {
        $seam = Fixture::getTestServer();

        $pages = $seam->createPaginator(
            fn($params) => $seam->connected_accounts->list(...$params),
            ["limit" => 2]
        );
        [$connectedAccounts, $pagination] = $pages->firstPage();

        $this->assertTrue(count($connectedAccounts) == 2);
        $this->assertTrue($pagination->has_next_page);

        [$moreConnectedAccounts] = $pages->nextPage(
            $pagination->next_page_cursor
        );

        $this->assertTrue(count($moreConnectedAccounts) == 1);
    }

    public function testPaginatorFlattenToArray(): void
    {
        $seam = Fixture::getTestServer();

        $allConnectedAccounts = $seam->connected_accounts->list();

        $pages = $seam->createPaginator(
            fn($params) => $seam->connected_accounts->list(...$params),
            ["limit" => 1]
        );
        $devices = $pages->flattenToArray();

        $this->assertTrue(count($devices) > 1);
        $this->assertTrue(count($devices) == count($allConnectedAccounts));
    }

    public function testPaginatorFlatten(): void
    {
        $seam = Fixture::getTestServer();

        $allConnectedAccounts = $seam->connected_accounts->list();
        $pages = $seam->createPaginator(
            fn($params) => $seam->connected_accounts->list(...$params),
            ["limit" => 1]
        );

        $connectedAccounts = [];
        foreach ($pages->flatten() as $connectedAccount) {
            $connectedAccounts[] = $connectedAccount;
        }
        $this->assertTrue(count($connectedAccounts) > 1);
        $this->assertTrue(
            count($connectedAccounts) == count($allConnectedAccounts)
        );
    }
}
