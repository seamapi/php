<?php

namespace Tests;

use Seam\Seam;

final class Fixture
{
  public static function getTestServer()
  {
    $client = new Seam(getenv('SEAM_API_KEY'));

    // TODO clear sandbox

    return $client;
  }
}
