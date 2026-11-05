<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Seam\Http\RequestParameters;
use Seam\NullValue;
use Seam\Paginator;

final class RequiredParametersTest extends TestCase
{
    private function assertRejected(
        array $parameters,
        string $path,
        array $parameterNames,
    ): void {
        try {
            RequestParameters::requireAtLeastOne(
                $path,
                $parameters,
                $parameterNames,
            );
            $this->fail("Expected InvalidArgumentException for $path");
        } catch (\InvalidArgumentException $error) {
            $this->assertSame(
                "At least one parameter is required for $path",
                $error->getMessage(),
            );
        }
    }

    public function testRejectsACallThatNamesNothing(): void
    {
        $this->assertRejected([], "/test/list", ["device_id"]);
    }

    /**
     * @dataProvider paginationOnlyCalls
     */
    public function testPaginationParamsAloneDoNotSatisfyTheGuard(
        array $parameters,
        string $path,
        array $parameterNames,
    ): void {
        $this->assertRejected($parameters, $path, $parameterNames);
    }

    public static function paginationOnlyCalls(): array
    {
        return [
            "limit" => [["limit" => 20], "/test/list", ["device_id"]],
            "page cursor" => [
                ["page_cursor" => "cursor"],
                "/test/list",
                ["device_id"],
            ],
            "limit and page cursor" => [
                ["limit" => 20, "page_cursor" => "cursor"],
                "/test/list",
                ["device_id"],
            ],
            "limit on an unpaginated list" => [
                ["limit" => 20],
                "/test/unpaginated_list",
                ["event_type"],
            ],
            "null filter" => [
                ["device_id" => null, "limit" => 20],
                "/test/list",
                ["device_id"],
            ],
        ];
    }

    /**
     * @dataProvider filteredCalls
     */
    public function testAcceptsACallThatNamesAFilter(
        array $parameters,
        array $parameterNames,
    ): void {
        RequestParameters::requireAtLeastOne(
            "/test/list",
            $parameters,
            $parameterNames,
        );

        $this->addToAssertionCount(1);
    }

    public static function filteredCalls(): array
    {
        return [
            "a filter" => [["device_id" => "device-1"], ["device_id"]],
            "a filter alongside pagination" => [
                ["device_id" => "device-1", "limit" => 20],
                ["device_id"],
            ],
            "a filter on an unpaginated list" => [
                ["event_type" => "device.connected"],
                ["event_type"],
            ],
            "a second filter" => [
                ["device_id" => null, "other_id" => "other-1"],
                ["device_id", "other_id"],
            ],
            "false" => [["filter" => false], ["filter"]],
            "zero" => [["filter" => 0], ["filter"]],
            "empty string" => [["filter" => ""], ["filter"]],
            "empty array" => [["filter" => []], ["filter"]],
            "explicit null sentinel" => [
                ["filter" => NullValue::NULL],
                ["filter"],
            ],
        ];
    }

    public function testAPaginatorOverAnUnfilteredListIsRejectedThroughout(): void
    {
        $requestCount = 0;
        $pages = new Paginator(function (array $parameters) use (
            &$requestCount,
        ): array {
            RequestParameters::requireAtLeastOne("/test/list", $parameters, [
                "device_id",
            ]);

            $requestCount++;
            return [];
        });

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
                    "At least one parameter is required for /test/list",
                    $error->getMessage(),
                );
            }
        }

        $this->assertSame(0, $requestCount);
    }
}
