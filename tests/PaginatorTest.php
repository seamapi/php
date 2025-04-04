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
            fn($params) => $seam->devices->list(...$params),
            ["limit" => 2]
        );
        [$devices, $pagination] = $pages->firstPage();

        $this->assertTrue(count($devices) == 2);
        $this->assertTrue($pagination->has_next_page);
        $this->assertTrue($pagination->next_page_cursor !== null);
        $this->assertTrue($pagination->next_page_url !== null);
    }

    public function testPaginatorNextPage(): void
    {
        $seam = Fixture::getTestServer();

        $pages = $seam->createPaginator(
            fn($params) => $seam->devices->list(...$params),
            ["limit" => 2]
        );
        [$devices, $pagination] = $pages->firstPage();

        $this->assertTrue(count($devices) == 2);
        $this->assertTrue($pagination->has_next_page);

        [$moreDevices] = $pages->nextPage($pagination->next_page_cursor);

        $this->assertTrue(count($moreDevices) == 2);
    }

    public function testPaginatorFlattenToArray(): void
    {
        $seam = Fixture::getTestServer();

        $allDevices = $seam->devices->list();

        $pages = $seam->createPaginator(
            fn($params) => $seam->devices->list(...$params),
            ["limit" => 1]
        );
        $devices = $pages->flattenToArray();

        $this->assertTrue(count($devices) > 1);
        $this->assertTrue(count($devices) == count($allDevices));
    }

    public function testPaginatorFlatten(): void
    {
        $seam = Fixture::getTestServer();

        $allDevices = $seam->devices->list();
        $pages = $seam->createPaginator(
            fn($params) => $seam->devices->list(...$params),
            ["limit" => 1]
        );

        $devices = [];
        foreach ($pages->flatten() as $device) {
            $devices[] = $device;
        }
        $this->assertTrue(count($devices) > 1);
        $this->assertTrue(count($devices) == count($allDevices));
    }
}
