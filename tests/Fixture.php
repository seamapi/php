<?php

namespace Tests;

use Seam\Seam;

final class Fixture
{
    public static function getTestServer()
    {
        $random_string = substr(
            str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"),
            0,
            10
        );
        echo $random_string;
        $api_url = "https://{$random_string}.fakeseamconnect.seam.vc";
        $api_key = "seam_apikey1_token";

        $seam = new Seam(api_key: $api_key, endpoint: $api_url);

        return $seam;
    }
}
