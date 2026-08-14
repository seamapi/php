<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Seam\StrictUrlSearchParamsSerializer;
use Seam\UrlSearchParams;

final class StrictUrlSearchParamsSerializerTest extends TestCase
{
    public function testAddsStrictToNonEmptyQueryStrings(): void
    {
        $this->assertSame("", StrictUrlSearchParamsSerializer::serialize([]));
        $this->assertSame(
            "foo=d&_strict=true",
            StrictUrlSearchParamsSerializer::serialize(["foo" => "d"]),
        );
    }

    public function testAppendsStrictAfterTheSortedParams(): void
    {
        $this->assertSame(
            "B=2&a=1&_strict=true",
            StrictUrlSearchParamsSerializer::serialize(["a" => 1, "B" => 2]),
        );
    }

    public function testReplacesACallerSuppliedStrictParam(): void
    {
        $this->assertSame(
            "_strict=true",
            StrictUrlSearchParamsSerializer::serialize(["_strict" => false]),
        );
    }

    public function testUpdateCountsExistingParamsAsNonEmpty(): void
    {
        $search_params = new UrlSearchParams([["foo", "bar"]]);
        StrictUrlSearchParamsSerializer::update($search_params, []);

        $this->assertSame("foo=bar&_strict=true", $search_params->to_string());
    }
}
