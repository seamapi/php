<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class HttpErrorTest extends TestCase
{
    public function testNonSeamError(): void
    {
        $seam = new \Seam\SeamClient(
            "seam_apikey1_token",
            "https://nonexistent.example.com",
        );

        try {
            $seam->devices->list();
            $this->fail("Expected GuzzleHttp ConnectException");
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $this->assertInstanceOf(
                \GuzzleHttp\Exception\ConnectException::class,
                $e,
            );
            $this->assertStringContainsString(
                "Could not resolve host",
                $e->getMessage(),
            );
        }
    }
}
