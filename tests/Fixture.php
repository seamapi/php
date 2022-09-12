<?php

namespace Tests;

use Seam\Seam;

final class Fixture
{
  public static function getTestServer()
  {
    $seam = new Seam(getenv('SEAM_API_KEY'));
    $seam->workspaces->reset_sandbox();
    return $seam;
  }
}
