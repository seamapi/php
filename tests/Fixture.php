<?php

namespace Tests;

use Seam\SeamClient;

final class Fixture
{
  public static function getTestServer($load_devices = false)
  {
    $client_url = getenv('SEAM_API_URL');

    if($client_url == null) {
      $client_url = "https://connect.getseam.com";
    }

     echo $client_url;

    $seam = new SeamClient(
      getenv('SEAM_API_KEY'), 
      "https://connect.getseam.com"
    );
    $seam->workspaces->reset_sandbox();
    if ($load_devices) {
      $seam->workspaces->_internal_load_schlage_factory();
    }
    return $seam;
  }
}
