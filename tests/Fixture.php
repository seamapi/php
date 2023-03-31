<?php

namespace Tests;

use Testcontainer\Container\PostgresContainer;
use PHPUnit\Framework\TestCase;
use Seam\SeamClient;

final class Fixture
{
	public static function setupContainers()
	{
		print 'starting';
		$container = PostgresContainer::make('15.0', 'password');
		$container->withPostgresDatabase('postgres');
		$container->withPostgresUser('username');

		$container->run();

		$host_ip = sprintf('postgresql://%s:5432/%s', $container->getAddress(), 'postgres');
		$pdo = new \PDO(
			sprintf('pgsql:host=%s;port=5432;dbname=postgres', $container->getAddress()),
			'username',
			'password'
		);

		$pdo->exec('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');

		fwrite(STDERR, print_r($host_ip, true));

		return true;
	}

	public static function getTestServer($load_devices = false)
	{
		$seam = new SeamClient(getenv('SEAM_API_KEY'));
		$seam->workspaces->reset_sandbox();
		if ($load_devices) {
			$seam->workspaces->_internal_load_august_factory();
		}
		return $seam;
	}
}
