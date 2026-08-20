<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Seam\NullValue;

final class NullValueTest extends TestCase
{
    public function testIsDetectedByType(): void
    {
        $this->assertInstanceOf(NullValue::class, NullValue::NULL);
        $this->assertNotInstanceOf(NullValue::class, null);
        $this->assertNotInstanceOf(NullValue::class, "NULL");
    }

    public function testIsASingleton(): void
    {
        $this->assertSame(NullValue::NULL, NullValue::NULL);
        $this->assertCount(1, NullValue::cases());
    }

    public function testReadsAsItsOwnName(): void
    {
        $this->assertSame("NULL", NullValue::NULL->name);
    }

    public function testReplaceReplacesTheSentinel(): void
    {
        $this->assertNull(NullValue::replace(NullValue::NULL));
    }

    public function testReplaceLeavesOtherValuesUnchanged(): void
    {
        foreach ([null, "NULL", 0, false, 20.5, ["a"]] as $value) {
            $this->assertSame($value, NullValue::replace($value));
        }
    }

    public function testReplaceRecursesIntoArrays(): void
    {
        $this->assertSame(
            [
                "name" => null,
                "codes" => [null, "1234"],
                "nested" => ["code" => null],
            ],
            NullValue::replace([
                "name" => NullValue::NULL,
                "codes" => [NullValue::NULL, "1234"],
                "nested" => ["code" => NullValue::NULL],
            ]),
        );
    }

    public function testReplaceCopiesStdClassObjectsWithoutMutatingThem(): void
    {
        $payload = (object) [
            "name" => NullValue::NULL,
            "nested" => (object) ["code" => NullValue::NULL],
        ];

        $replaced = NullValue::replace($payload);

        $this->assertNotSame($payload, $replaced);
        $this->assertNull($replaced->name);
        $this->assertNull($replaced->nested->code);
        $this->assertSame(NullValue::NULL, $payload->name);
        $this->assertSame(NullValue::NULL, $payload->nested->code);
    }

    public function testReplaceDoesNotDescendIntoStrings(): void
    {
        $this->assertSame("a NULL b", NullValue::replace("a NULL b"));
    }
}
