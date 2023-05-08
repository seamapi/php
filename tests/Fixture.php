<?php

namespace Tests;

use Seam\SeamClient;

final class Fixture
{
  public static function getTestServer($load_devices = false)
  {
    $seam = new SeamClient('seam_test3CGc_4Desq33kSCEym8YNgxkW3CuB');
    $seam->workspaces->reset_sandbox();
    if ($load_devices) {
      $seam->workspaces->_internal_load_august_factory();
    }
    return $seam;
  }
}
