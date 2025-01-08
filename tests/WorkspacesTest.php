<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;

final class WorkspacesTest extends TestCase
{
    public function testGetAndListWorkspaces(): void
    {
        $seam = Fixture::getTestServer();

        $workspaces = $seam->workspaces->list();
        $this->assertTrue(count($workspaces) > 0);

        $workspace = $seam->workspaces->get();
        $this->assertIsObject($workspace);
    }
}
