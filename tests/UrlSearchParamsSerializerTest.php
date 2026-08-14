<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Seam\NullValue;
use Seam\UnserializableParamError;
use Seam\UrlSearchParams;
use Seam\UrlSearchParamsSerializer;

final class UrlSearchParamsSerializerTest extends TestCase
{
    private static function serialize(array|\stdClass $params): string
    {
        return UrlSearchParamsSerializer::serialize($params);
    }

    public function testSerializesEmptyParams(): void
    {
        $this->assertSame("", self::serialize([]));
        $this->assertSame("", self::serialize(new \stdClass()));
    }

    public function testSerializesString(): void
    {
        $this->assertSame("foo=d", self::serialize(["foo" => "d"]));
        $this->assertSame("foo=null", self::serialize(["foo" => "null"]));
        $this->assertSame(
            "foo=undefined",
            self::serialize(["foo" => "undefined"]),
        );
        $this->assertSame("foo=0", self::serialize(["foo" => "0"]));
    }

    public function testRemovesTheEmptyString(): void
    {
        $this->assertSame("", self::serialize(["foo" => ""]));
        $this->assertSame(
            "foo=d",
            self::serialize(["foo" => "d", "bar" => ""]),
        );
    }

    public function testSerializesInt(): void
    {
        $this->assertSame("foo=1", self::serialize(["foo" => 1]));
        $this->assertSame("foo=0", self::serialize(["foo" => 0]));
        $this->assertSame("foo=-42", self::serialize(["foo" => -42]));
    }

    public function testSerializesLargeIntWithFullPrecision(): void
    {
        $this->assertSame(
            "foo=9007199254740993",
            self::serialize(["foo" => 9007199254740993]),
        );
        $this->assertSame(
            "foo=9223372036854775807",
            self::serialize(["foo" => PHP_INT_MAX]),
        );
    }

    public function testSerializesFloat(): void
    {
        $this->assertSame("foo=23.8", self::serialize(["foo" => 23.8]));
        $this->assertSame("foo=-23.8", self::serialize(["foo" => -23.8]));
        $this->assertSame(
            "foo=0.30000000000000004",
            self::serialize(["foo" => 0.1 + 0.2]),
        );
    }

    public function testSerializesFloatUsingTheEcmascriptNumberFormat(): void
    {
        $this->assertSame("foo=1", self::serialize(["foo" => 1.0]));
        $this->assertSame("foo=0", self::serialize(["foo" => -0.0]));
        $this->assertSame("foo=100", self::serialize(["foo" => 100.0]));
        $this->assertSame(
            "foo=10000000000000000",
            self::serialize(["foo" => 1e16]),
        );
        $this->assertSame(
            "foo=100000000000000000000",
            self::serialize(["foo" => 1e20]),
        );
        $this->assertSame("foo=1e%2B21", self::serialize(["foo" => 1e21]));
        $this->assertSame("foo=0.0001", self::serialize(["foo" => 0.0001]));
        $this->assertSame("foo=0.000001", self::serialize(["foo" => 1e-6]));
        $this->assertSame("foo=1e-7", self::serialize(["foo" => 1e-7]));
        $this->assertSame("foo=5e-324", self::serialize(["foo" => 5e-324]));
        $this->assertSame(
            "foo=1.7976931348623157e%2B308",
            self::serialize(["foo" => PHP_FLOAT_MAX]),
        );
    }

    public function testSerializesBool(): void
    {
        $this->assertSame("foo=true", self::serialize(["foo" => true]));
        $this->assertSame("foo=false", self::serialize(["foo" => false]));
        $this->assertSame(
            "bar=false&foo=true",
            self::serialize(["foo" => true, "bar" => false]),
        );
    }

    public function testRemovesNullParams(): void
    {
        $this->assertSame("", self::serialize(["bar" => null]));
        $this->assertSame(
            "foo=1",
            self::serialize(["foo" => 1, "bar" => null]),
        );
    }

    public function testSerializesNullValueParams(): void
    {
        $this->assertSame("bar=", self::serialize(["bar" => NullValue::NULL]));
        $this->assertSame(
            "bar=&foo=1",
            self::serialize(["foo" => 1, "bar" => NullValue::NULL]),
        );
    }

    public function testRemovesNullParamsAtAnyDepth(): void
    {
        $this->assertSame(
            "foo.baz=1",
            self::serialize(["foo" => ["bar" => null, "baz" => 1]]),
        );
        $this->assertSame("", self::serialize(["foo" => ["bar" => null]]));
    }

    public function testSerializesEmptyArrayParams(): void
    {
        $this->assertSame("bar=", self::serialize(["bar" => []]));
        $this->assertSame(
            "bar=&foo=1",
            self::serialize(["foo" => 1, "bar" => []]),
        );
    }

    public function testSerializesArrayParamsWithOneValue(): void
    {
        $this->assertSame("bar=a", self::serialize(["bar" => ["a"]]));
        $this->assertSame(
            "bar=a&foo=1",
            self::serialize(["foo" => 1, "bar" => ["a"]]),
        );
    }

    public function testSerializesArrayParamsWithManyValues(): void
    {
        $this->assertSame(
            "bar=a&bar=2&foo=1",
            self::serialize(["foo" => 1, "bar" => ["a", "2"]]),
        );
        $this->assertSame(
            "bar=null&bar=2&bar=undefined&foo=1",
            self::serialize(["foo" => 1, "bar" => ["null", "2", "undefined"]]),
        );
    }

    public function testSerializesArrayParamsWithMixedValues(): void
    {
        $this->assertSame(
            "bar=1&bar=a&bar=true&bar=1970-01-01T00%3A00%3A00.000Z",
            self::serialize([
                "bar" => [
                    1,
                    "a",
                    true,
                    new \DateTimeImmutable("1970-01-01T00:00:00Z"),
                ],
            ]),
        );
    }

    public function testSerializesDatetime(): void
    {
        $this->assertSame(
            "foo=1&now=2025-02-24T18%3A44%3A39.000Z",
            self::serialize([
                "foo" => 1,
                "now" => new \DateTimeImmutable("2025-02-24T18:44:39Z"),
            ]),
        );
    }

    public function testSerializesMutableDatetime(): void
    {
        $now = new \DateTime("2025-02-24T18:44:39Z");

        $this->assertSame(
            "now=2025-02-24T18%3A44%3A39.000Z",
            self::serialize(["now" => $now]),
        );
        $this->assertSame("2025-02-24T18:44:39+00:00", $now->format("c"));
    }

    public function testSerializesDatetimeWithMilliseconds(): void
    {
        $this->assertSame(
            "now=2025-02-24T18%3A44%3A39.123Z",
            self::serialize([
                "now" => new \DateTimeImmutable("2025-02-24T18:44:39.123Z"),
            ]),
        );
    }

    public function testTruncatesDatetimeMicroseconds(): void
    {
        $this->assertSame(
            "now=2025-02-24T18%3A44%3A39.123Z",
            self::serialize([
                "now" => new \DateTimeImmutable("2025-02-24T18:44:39.123999Z"),
            ]),
        );
    }

    public function testSerializesDatetimeAsUtc(): void
    {
        $this->assertSame(
            "now=2025-02-24T18%3A44%3A39.000Z",
            self::serialize([
                "now" => new \DateTimeImmutable("2025-02-24T13:44:39-05:00"),
            ]),
        );
    }

    public function testSerializesDatetimeBeforeTheEpoch(): void
    {
        $this->assertSame(
            "then=1969-12-31T23%3A59%3A59.000Z",
            self::serialize([
                "then" => new \DateTimeImmutable("1969-12-31T23:59:59Z"),
            ]),
        );
    }

    public function testZeroPadsTheYearToFourDigits(): void
    {
        $this->assertSame(
            "then=0050-01-02T03%3A04%3A05.000Z",
            self::serialize([
                "then" => new \DateTimeImmutable("0050-01-02T03:04:05Z"),
            ]),
        );
    }

    public function testSerializesNestedParams(): void
    {
        $this->assertSame(
            "bar.baz=a&foo=1",
            self::serialize(["foo" => 1, "bar" => ["baz" => "a"]]),
        );

        $this->assertSame(
            "bar.baz.x.z=1&foo=1",
            self::serialize([
                "foo" => 1,
                "bar" => ["baz" => ["x" => ["z" => 1]]],
            ]),
        );

        $this->assertSame(
            "bar.baz.x.z=&foo=1",
            self::serialize([
                "foo" => 1,
                "bar" => ["baz" => ["x" => ["z" => NullValue::NULL]]],
            ]),
        );

        $this->assertSame(
            "bar.baz=1&bar.baz=a&foo=1",
            self::serialize(["foo" => 1, "bar" => ["baz" => [1, "a"]]]),
        );

        $this->assertSame(
            "bar=2",
            self::serialize(["foo" => new \stdClass(), "bar" => 2]),
        );

        $this->assertSame(
            "bar=2",
            self::serialize(["foo" => ["x" => new \stdClass()], "bar" => 2]),
        );

        $this->assertSame(
            "bar.baz.x.z=",
            self::serialize([
                "foo" => new \stdClass(),
                "bar" => [
                    "baz" => [
                        "x" => ["z" => NullValue::NULL, "t" => new \stdClass()],
                        "q" => new \stdClass(),
                    ],
                ],
            ]),
        );
    }

    public function testSerializesStdClassParams(): void
    {
        $this->assertSame(
            "bar.baz=a&foo=1",
            self::serialize(
                (object) ["foo" => 1, "bar" => (object) ["baz" => "a"]],
            ),
        );
    }

    public function testSortsParamsByName(): void
    {
        $this->assertSame(
            "a=2&b=1&c=3",
            self::serialize(["b" => 1, "a" => 2, "c" => 3]),
        );
        $this->assertSame(
            "A=2&B=4&a=3&b=1",
            self::serialize(["b" => 1, "A" => 2, "a" => 3, "B" => 4]),
        );
        $this->assertSame(
            "a1=3&a10=1&a2=2",
            self::serialize(["a10" => 1, "a2" => 2, "a1" => 3]),
        );
        $this->assertSame(
            "a.b=3&a.z=2&zz=1",
            self::serialize(["zz" => 1, "a" => ["z" => 2, "b" => 3]]),
        );
        $this->assertSame(
            "a.b=2&ab=1",
            self::serialize(["ab" => 1, "a" => ["b" => 2]]),
        );
    }

    public function testSortsParamsByUtf16CodeUnit(): void
    {
        $this->assertSame(
            "%F0%9F%98%80=2&%EF%BF%BF=1",
            self::serialize(["\u{FFFF}" => 1, "\u{1F600}" => 2]),
        );
    }

    public function testSortingPreservesArrayOrder(): void
    {
        $this->assertSame(
            "a=1&b=3&b=1&b=2",
            self::serialize(["b" => ["3", "1", "2"], "a" => 1]),
        );
    }

    public function testEncodesParamsAsFormUrlencoded(): void
    {
        $this->assertSame("foo=a+b", self::serialize(["foo" => "a b"]));
        $this->assertSame("foo=a%2Bb", self::serialize(["foo" => "a+b"]));
        $this->assertSame("foo=a%7Eb", self::serialize(["foo" => "a~b"]));
        $this->assertSame("foo=a*b", self::serialize(["foo" => "a*b"]));
        $this->assertSame(
            "foo=abcXYZ019*-._",
            self::serialize(["foo" => "abcXYZ019*-._"]),
        );
        $this->assertSame("foo=a+*%7E+b", self::serialize(["foo" => "a *~ b"]));
        $this->assertSame(
            "foo=a%26b%3Dc%3Fd%23e%2Ff",
            self::serialize(["foo" => "a&b=c?d#e/f"]),
        );
        $this->assertSame("foo=100%25", self::serialize(["foo" => "100%"]));
        $this->assertSame("foo=a%0Ab", self::serialize(["foo" => "a\nb"]));
    }

    public function testEncodesUnicodeParams(): void
    {
        $this->assertSame(
            "foo=h%C3%A9llo+w%C3%B6rld",
            self::serialize(["foo" => "héllo wörld"]),
        );
        $this->assertSame(
            "foo=%E6%97%A5%E6%9C%AC%E8%AA%9E",
            self::serialize(["foo" => "日本語"]),
        );
        $this->assertSame("%F0%9F%94%92=a", self::serialize(["🔒" => "a"]));
        $this->assertSame("a+b=1", self::serialize(["a b" => 1]));
    }

    public function testCannotSerializeKeysContainingADot(): void
    {
        $this->expectException(UnserializableParamError::class);

        self::serialize(["foo.bar" => 1]);
    }

    public function testCannotSerializeNestedKeysContainingADot(): void
    {
        $this->expectException(UnserializableParamError::class);

        self::serialize(["foo" => ["bar.baz" => 1]]);
    }

    public function testCannotSerializeNonStringKeys(): void
    {
        $this->expectException(UnserializableParamError::class);

        self::serialize(["foo" => [1 => "a", "b" => "c"]]);
    }

    public function testCannotSerializeClosures(): void
    {
        $this->expectException(UnserializableParamError::class);

        self::serialize(["foo" => fn() => null]);
    }

    /**
     * @dataProvider provideNonFiniteFloats
     */
    public function testCannotSerializeNonFiniteFloats(
        float $value,
        string $message,
    ): void {
        try {
            self::serialize(["foo" => $value]);
            $this->fail("Expected an UnserializableParamError");
        } catch (UnserializableParamError $error) {
            $this->assertSame(
                "Could not serialize parameter: 'foo' {$message}",
                $error->getMessage(),
            );
            $this->assertSame("foo", $error->getName());
        }
    }

    public static function provideNonFiniteFloats(): array
    {
        return [
            "NaN" => [NAN, "is NaN"],
            "Infinity" => [INF, "is Infinity"],
            "-Infinity" => [-INF, "is -Infinity"],
        ];
    }

    public function testCannotSerializeArbitraryObjects(): void
    {
        $this->expectException(UnserializableParamError::class);

        self::serialize([
            "foo" => new class {
                public string $device_id = "a";
            },
        ]);
    }

    /**
     * @dataProvider provideUnserializableArrays
     */
    public function testCannotSerializeArrayParamsWithUnserializableValues(
        array $params,
    ): void {
        $this->expectException(UnserializableParamError::class);

        self::serialize($params);
    }

    public static function provideUnserializableArrays(): array
    {
        return [
            "single empty string" => [["foo" => [""]]],
            "null element" => [["bar" => ["a", null]]],
            "NullValue element" => [["bar" => ["a", NullValue::NULL]]],
            "nested list" => [["bar" => ["a", ["s"]]]],
            "nested empty list" => [["bar" => ["a", []]]],
            "nested list with empty string" => [["bar" => ["a", [""]]]],
            "nested object" => [["bar" => ["a", new \stdClass()]]],
            "nested map" => [["bar" => ["a", ["x" => 2]]]],
            "closure element" => [["bar" => ["a", fn() => null]]],
            "empty strings around values" => [
                ["foo" => 1, "bar" => ["", "a", ""]],
            ],
            "leading empty string" => [["foo" => 1, "bar" => ["", "a", "2"]]],
            "only empty strings" => [["foo" => 1, "bar" => ["", "", ""]]],
            "NaN element" => [["foo" => [1, NAN]]],
        ];
    }

    public function testUnserializableParamErrorMessage(): void
    {
        try {
            self::serialize(["foo" => ["bar.baz" => 1]]);
            $this->fail("Expected an UnserializableParamError");
        } catch (UnserializableParamError $error) {
            $this->assertSame(
                "Could not serialize parameter: 'bar.baz' contains one or " .
                    'more dots "." in its name which is unsupported',
                $error->getMessage(),
            );
            $this->assertSame("bar.baz", $error->getName());
        }
    }

    public function testUnserializableParamErrorMessageUsesTheFullPath(): void
    {
        try {
            self::serialize(["foo" => ["bar" => NAN]]);
            $this->fail("Expected an UnserializableParamError");
        } catch (UnserializableParamError $error) {
            $this->assertSame(
                "Could not serialize parameter: 'foo.bar' is NaN",
                $error->getMessage(),
            );
        }
    }

    public function testUpdateUrlSearchParams(): void
    {
        $search_params = new UrlSearchParams();
        UrlSearchParamsSerializer::update($search_params, [
            "foo" => "d",
            "bar" => 2,
        ]);

        $this->assertSame("bar=2&foo=d", $search_params->to_string());
    }

    public function testUpdatePreservesExistingParams(): void
    {
        $search_params = new UrlSearchParams([["foo", "bar"]]);
        UrlSearchParamsSerializer::update($search_params, [
            "name" => "Dax",
            "age" => 27,
            "is_admin" => true,
            "tags" => ["cars", "planes"],
        ]);

        $this->assertSame(
            "age=27&foo=bar&is_admin=true&name=Dax&tags=cars&tags=planes",
            $search_params->to_string(),
        );
    }

    public function testUpdateOverwritesExistingParams(): void
    {
        $search_params = new UrlSearchParams([
            ["foo", "a"],
            ["bar", "x"],
            ["foo", "b"],
        ]);
        UrlSearchParamsSerializer::update($search_params, ["foo" => "new"]);

        $this->assertSame("bar=x&foo=new", $search_params->to_string());
    }

    public function testUpdateAppendsArrayParams(): void
    {
        $search_params = new UrlSearchParams([["foo", "old"]]);
        UrlSearchParamsSerializer::update($search_params, ["foo" => [1, 2]]);

        $this->assertSame("foo=old&foo=1&foo=2", $search_params->to_string());
    }

    public function testUpdateKeepsExistingParamsForAbsentValues(): void
    {
        foreach ([null, "", new \stdClass()] as $value) {
            $search_params = new UrlSearchParams([["foo", "a"]]);
            UrlSearchParamsSerializer::update($search_params, [
                "foo" => $value,
            ]);

            $this->assertSame("foo=a", $search_params->to_string());
        }
    }

    public function testUrlSearchParamsFromQueryString(): void
    {
        $search_params = new UrlSearchParams(
            "?a=1&b=hello+world&c=%F0%9F%94%92&d",
        );

        $this->assertSame("1", $search_params->get("a"));
        $this->assertSame("hello world", $search_params->get("b"));
        $this->assertSame("🔒", $search_params->get("c"));
        $this->assertSame("", $search_params->get("d"));
        $this->assertSame(
            "a=1&b=hello+world&c=%F0%9F%94%92&d=",
            $search_params->to_string(),
        );
    }

    public function testUrlSearchParamsFromMap(): void
    {
        $this->assertSame(
            "a=1&b=2",
            (new UrlSearchParams(["a" => "1", "b" => "2"]))->to_string(),
        );
    }

    public function testUrlSearchParamsAppendAndGet(): void
    {
        $search_params = new UrlSearchParams();
        $search_params->append("foo", "a");
        $search_params->append("foo", "b");

        $this->assertSame("a", $search_params->get("foo"));
        $this->assertSame(["a", "b"], $search_params->get_all("foo"));
        $this->assertNull($search_params->get("bar"));
        $this->assertSame([], $search_params->get_all("bar"));
        $this->assertCount(2, $search_params);
        $this->assertSame(
            [["foo", "a"], ["foo", "b"]],
            iterator_to_array($search_params),
        );
    }

    public function testUrlSearchParamsSetKeepsTheFirstPairsPosition(): void
    {
        $search_params = new UrlSearchParams([
            ["foo", "a"],
            ["bar", "x"],
            ["foo", "b"],
        ]);
        $search_params->set("foo", "c");

        $this->assertSame(
            [["foo", "c"], ["bar", "x"]],
            iterator_to_array($search_params),
        );

        $search_params->set("baz", "y");

        $this->assertSame("y", $search_params->get("baz"));
    }

    public function testUrlSearchParamsHasAndDelete(): void
    {
        $search_params = new UrlSearchParams([["foo", "a"], ["foo", "b"]]);

        $this->assertTrue($search_params->has("foo"));

        $search_params->delete("foo");

        $this->assertFalse($search_params->has("foo"));
        $this->assertCount(0, $search_params);
    }

    public function testUrlSearchParamsCastsToString(): void
    {
        $this->assertSame(
            "foo=a+b",
            (string) new UrlSearchParams([["foo", "a b"]]),
        );
    }
}
