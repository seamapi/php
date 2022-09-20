<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use GuzzleHttp\Client;

final class WorkspacesTest extends TestCase
{
  public function testGetAndListWorkspaces(): void
  {
    $seam = Fixture::getTestServer(true);

    $workspaces = $seam->workspaces->list();
    $this->assertTrue(count($workspaces) > 0);

    $workspace = $seam->workspaces->get();
    $this->assertIsObject($workspace);

    $workspace_id = $workspace->workspace_id;
    $workspace = $seam->workspaces->get(workspace_id: $workspace_id);
    $this->assertTrue($workspace->workspace_id === $workspace_id);
  }
}
