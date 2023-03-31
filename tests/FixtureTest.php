<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use GuzzleHttp\Client;

final class FixtureTest extends TestCase
{
	/** @test */
	public function runner(): void
	{
		$container = Fixture::setupContainers();

		$this->assertTrue($container);
	}
}
