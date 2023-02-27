<?php

namespace Tests;

use Seam\SeamClient;

final class Fixture
{
  public static function getTestServer($load_devices = false)
  {
    $seam = new SeamClient(getenv('SEAM_API_KEY'));
    $seam->workspaces->reset_sandbox();
    if ($load_devices) {
      $seam->workspaces->_internal_load_schlage_factory();
    }
    return $seam;
  }
}
