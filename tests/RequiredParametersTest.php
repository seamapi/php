<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Seam\Seam;
use Tests\Support\RecordingClient;

final class RequiredParametersTest extends TestCase
{
    /**
     * @return array{0: Seam, 1: RecordingClient}
     */
    private function recorded(): array
    {
        $recorder = new RecordingClient([
            RecordingClient::json(200, ["access_codes" => [], "events" => []]),
        ]);

        return [
            Seam::from_api_key(
                "seam_apikey_token",
                endpoint: "https://example.com",
                guzzle_options: $recorder->guzzle_options(),
                retries: 0,
            ),
            $recorder,
        ];
    }

    private function assertRejected(callable $call, string $path): void
    {
        [$seam, $recorder] = $this->recorded();

        try {
            $call($seam);
            $this->fail("Expected InvalidArgumentException for $path");
        } catch (\InvalidArgumentException $error) {
            $this->assertSame(
                "At least one parameter is required for $path",
                $error->getMessage(),
            );
        }

        $this->assertSame(0, $recorder->request_count());
    }

    public function testRejectsACallThatNamesNothing(): void
    {
        $this->assertRejected(
            fn(Seam $seam) => $seam->access_codes->list(),
            "/access_codes/list",
        );
    }

    /**
     * @dataProvider paginationOnlyCalls
     */
    public function testPaginationParamsAloneDoNotSatisfyTheGuard(
        callable $call,
        string $path,
    ): void {
        $this->assertRejected($call, $path);
    }

    public static function paginationOnlyCalls(): array
    {
        return [
            "limit" => [
                fn(Seam $seam) => $seam->access_codes->list(limit: 20),
                "/access_codes/list",
            ],
            "page cursor" => [
                fn(Seam $seam) => $seam->access_codes->list(
                    page_cursor: "cursor",
                ),
                "/access_codes/list",
            ],
            "limit and page cursor" => [
                fn(Seam $seam) => $seam->access_codes->list(
                    limit: 20,
                    page_cursor: "cursor",
                ),
                "/access_codes/list",
            ],
            "limit on an unpaginated list" => [
                fn(Seam $seam) => $seam->events->list(limit: 20),
                "/events/list",
            ],
        ];
    }

    /**
     * @dataProvider filteredCalls
     */
    public function testAcceptsACallThatNamesAFilter(
        callable $call,
        string $expected_query,
    ): void {
        [$seam, $recorder] = $this->recorded();

        $call($seam);

        $this->assertSame(1, $recorder->request_count());
        $this->assertStringContainsString(
            $expected_query,
            $recorder->request()->getUri()->getQuery(),
        );
    }

    public static function filteredCalls(): array
    {
        return [
            "a filter" => [
                fn(Seam $seam) => $seam->access_codes->list(
                    device_id: "device-1",
                ),
                "device_id=device-1",
            ],
            "a filter alongside pagination" => [
                fn(Seam $seam) => $seam->access_codes->list(
                    device_id: "device-1",
                    limit: 20,
                ),
                "device_id=device-1",
            ],
            "a filter on an unpaginated list" => [
                fn(Seam $seam) => $seam->events->list(
                    event_type: "device.connected",
                ),
                "event_type=device.connected",
            ],
        ];
    }

    public function testAPaginatorOverAnUnfilteredListIsRejectedThroughout(): void
    {
        [$seam] = $this->recorded();

        $pages = $seam->createPaginator(
            fn($params) => $seam->access_codes->list(...$params),
        );

        foreach (
            [
                fn() => $pages->firstPage(),
                fn() => $pages->flattenToArray(),
                fn() => iterator_to_array($pages->flatten()),
            ]
            as $call
        ) {
            try {
                $call();
                $this->fail("Expected InvalidArgumentException");
            } catch (\InvalidArgumentException $error) {
                $this->assertSame(
                    "At least one parameter is required for /access_codes/list",
                    $error->getMessage(),
                );
            }
        }
    }
}
