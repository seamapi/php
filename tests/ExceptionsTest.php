<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use GuzzleHttp\Client;
use Seam\Exceptions\ApiException;
use Seam\Exceptions\HttpException;
use Seam\Exceptions\MissingInnerObjectException;

final class ExceptionsTest extends TestCase
{
    public function testApiExceptionBackwardCompatibility(): void
    {
        try {
            throw new ApiException('POST', 'test/', 'not_supported', 'The API response', 'some-uuid');
        } catch (Exception $e) {
            $this->assertTrue(
                str_contains($e->getMessage(), "Error Calling")
            );
        }
    }

    public function testApiExceptionGetters(): void
    {
        try {
            throw new ApiException('POST', 'test/', 'not_supported', 'The API response', 'some-uuid');
        } catch (ApiException $e) {
            $this->assertEquals("POST",  $e->getMethod());
            $this->assertEquals("test/",  $e->getPath());
            $this->assertEquals("not_supported",  $e->getErrorType());
            $this->assertEquals("The API response",  $e->getError());
            $this->assertEquals("some-uuid",  $e->getRequestId());
        }
    }

    public function testHttpExceptionBackwardCompatibility(): void
    {
        try {
            throw new HttpException('POST', 'test/', 'Bad request', 'some-uuid', 400);
        } catch (Exception $e) {
            $this->assertTrue(
                str_contains($e->getMessage(), "HTTP Error")
            );
        }
    }

    public function testHttpExceptionGetters(): void
    {
        try {
            throw new HttpException('POST', 'test/', 'Bad request', 'some-uuid', 400);
        } catch (HttpException $e) {
            $this->assertEquals("POST",  $e->getMethod());
            $this->assertEquals("test/",  $e->getPath());
            $this->assertEquals("Bad request",  $e->getError());
            $this->assertEquals("some-uuid",  $e->getRequestId());
            $this->assertEquals(400,  $e->getStatusCode());
        }
    }

    public function testMissingInnerObjectExceptionBackwardCompatibility(): void
    {
        try {
            throw new MissingInnerObjectException('POST', 'test/', 'code', 'some-uuid');
        } catch (Exception $e) {
            $this->assertTrue(
                str_contains($e->getMessage(), "Missing Inner Object")
            );
        }
    }

    public function testMissingInnerObjectExceptionGetters(): void
    {
        try {
            throw new MissingInnerObjectException('POST', 'test/', 'code', 'some-uuid');
        } catch (MissingInnerObjectException $e) {
            $this->assertEquals("POST",  $e->getMethod());
            $this->assertEquals("test/",  $e->getPath());
            $this->assertEquals("code",  $e->getInnerObjectName());
            $this->assertEquals("some-uuid",  $e->getRequestId());
        }
    }
}
