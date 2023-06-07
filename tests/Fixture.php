<?php

namespace Tests;

use Seam\SeamClient;

final class Fixture
{
  public static function getTestServer($load_devices = false)
  {
    $seam = new SeamClient("seam_test2EYd_6sdxF2Ddz2MvWq9QgZnjBeDQ");
    // $seam->workspaces->reset_sandbox();
    if ($load_devices) {
      $seam->workspaces->_internal_load_august_factory();
    }
    return $seam;
  }
}
