<?php

namespace Seam;

use Seam\Routes\Routes;
use GuzzleHttp\Client as GuzzleHttpClient;

class Seam
{
    private Routes $routes;
    public GuzzleHttpClient $client;
    public readonly array $defaults;
    public static string $ltsVersion = Config::LTS_VERSION;

    public function __construct(
        ?GuzzleHttpClient $client = null,
        ?string $api_key = null,
        ?string $personal_access_token = null,
        ?string $workspace_id = null,
        ?string $endpoint = null,
        ?bool $wait_for_action_attempt = true,
        ?array $guzzle_options = []
    ) {
        $options = Options::parseOptions(
            $api_key,
            $personal_access_token,
            $workspace_id,
            $endpoint
        );

        $this->defaults = [
            "wait_for_action_attempt" => $wait_for_action_attempt,
        ];

        $this->client =
            $client ??
            Http::createClient([
                "base_url" => $options["endpoint"],
                "headers" => $options["headers"],
                "guzzle_options" => $guzzle_options,
            ]);

        $this->routes = new Routes($this);
    }

    public function __get(string $name): mixed
    {
        return $this->routes->$name;
    }

    public static function fromApiKey(
        string $api_key,
        ?string $endpoint = null,
        bool $wait_for_action_attempt = true,
        array $guzzle_options = []
    ): self {
        return new self(
            api_key: $api_key,
            endpoint: $endpoint,
            wait_for_action_attempt: $wait_for_action_attempt,
            guzzle_options: $guzzle_options
        );
    }

    public static function fromPersonalAccessToken(
        string $personal_access_token,
        string $workspace_id,
        ?string $endpoint = null,
        bool $wait_for_action_attempt = true,
        array $guzzle_options = []
    ): self {
        return new self(
            personal_access_token: $personal_access_token,
            workspace_id: $workspace_id,
            endpoint: $endpoint,
            wait_for_action_attempt: $wait_for_action_attempt,
            guzzle_options: $guzzle_options
        );
    }
}
