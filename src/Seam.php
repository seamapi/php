<?php

namespace Seam;

use Seam\Routes\Routes;
use GuzzleHttp\Client as GuzzleHttpClient;
use Seam\Utils\PackageVersion;

define("LTS_VERSION", "1.0.0");

class Seam
{
    private Routes $routes;
    public string $api_key;
    public GuzzleHttpClient $client;
    public string $ltsVersion = LTS_VERSION;

    public function __construct(
        $api_key = null,
        $endpoint = "https://connect.getseam.com"
    ) {
        $this->api_key = $api_key ?: (getenv("SEAM_API_KEY") ?: null);
        $seam_sdk_version = PackageVersion::get();

        $headers = [
            "Authorization" => "Bearer " . $this->api_key,
            "User-Agent" => "Seam PHP Client " . $seam_sdk_version,
            "seam-sdk-name" => "seamapi/php",
            "seam-sdk-version" => $seam_sdk_version,
            "seam-lts-version" => $this->ltsVersion,
        ];

        $this->client = Http::createClient([
            "base_url" => $endpoint,
            "headers" => $headers,
        ]);
        $this->routes = new Routes($this);
    }

    public function __get(string $name): mixed
    {
        return $this->routes->$name;
    }
}
