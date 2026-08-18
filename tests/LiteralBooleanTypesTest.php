<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;
use Seam\Resources\AccessCode;
use Seam\Resources\ActionAttempt;
use Seam\Resources\UnmanagedAccessCode;
use TypeError;

final class LiteralBooleanTypesTest extends TestCase
{
    public function testAccessCodeBooleanConstraintsArePreserved(): void
    {
        $this->assertSame(
            "?true",
            (string) (new ReflectionProperty(
                AccessCode::class,
                "is_managed",
            ))->getType(),
        );
        $this->assertSame(
            "?false",
            (string) (new ReflectionProperty(
                UnmanagedAccessCode::class,
                "is_managed",
            ))->getType(),
        );

        foreach (
            [AccessCode\Errors::class, UnmanagedAccessCode\Errors::class]
            as $class
        ) {
            $this->assertSame(
                "?true",
                (string) (new ReflectionProperty(
                    $class,
                    "is_access_code_error",
                ))->getType(),
            );
            $this->assertSame(
                "?bool",
                (string) (new ReflectionProperty(
                    $class,
                    "is_connected_account_error",
                ))->getType(),
            );
        }

        $this->assertSame(
            "?bool",
            (string) (new ReflectionProperty(
                ActionAttempt\Result::class,
                "is_managed",
            ))->getType(),
        );
    }

    public function testUnmanagedAccessCodeRejectsManagedLiteral(): void
    {
        $code = (new ReflectionClass(
            UnmanagedAccessCode::class,
        ))->newInstanceWithoutConstructor();

        $this->expectException(TypeError::class);
        $code->is_managed = true;
    }
}
