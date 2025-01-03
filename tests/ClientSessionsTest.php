<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;

final class ClientSessionsTest extends TestCase
{
    public function testCreateClientSession(): void
    {
        $seam = Fixture::getTestServer();
        $client_session = $seam->client_sessions->create();

        $this->assertIsString($client_session->client_session_id);
        $this->assertIsString($client_session->token);
    }
}
