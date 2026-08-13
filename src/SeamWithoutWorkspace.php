<?php

namespace Seam;

use GuzzleHttp\ClientInterface;
use Seam\Http\ClientFactory;
use Seam\Routes\WorkspacesClient;

/**
 * Client for the Seam API endpoints that are not scoped to a workspace.
 *
 * Authenticates with a personal access token and exposes only the workspace
 * listing and creation endpoints. Use Seam\Seam for everything else.
 *
 * When no token is given, the SEAM_PERSONAL_ACCESS_TOKEN environment variable
 * is used.
 */
class SeamWithoutWorkspace
{
    public const LTS_VERSION = ClientFactory::LTS_VERSION;

    /**
     * The Guzzle client this instance makes its requests with.
     */
    public ClientInterface $client;

    public WorkspacesProxy $workspaces;

    /**
     * @param array<string, mixed> $guzzle_options
     */
    public function __construct(
        ?string $personal_access_token = null,
        ?string $endpoint = null,
        array $guzzle_options = [],
        ?int $retries = null,
        ?float $timeout = null,
        ?ClientInterface $client = null,
    ) {
        // A client carries its own endpoint and authorization, so no option
        // that would configure one can be combined with it.
        Options::check_client_options($client, [
            "personal_access_token" => $personal_access_token,
            "endpoint" => $endpoint,
            "guzzle_options" => $guzzle_options,
            "retries" => $retries,
            "timeout" => $timeout,
        ]);

        $this->client =
            $client ??
            ClientFactory::create(
                Options::get_endpoint($endpoint),
                Auth::get_auth_headers_without_workspace(
                    $personal_access_token,
                ),
                $guzzle_options,
                $retries,
                $timeout,
            );

        $this->workspaces = new WorkspacesProxy(
            new WorkspacesClient($this->client, [
                "wait_for_action_attempt" => false,
            ]),
        );
    }

    /**
     * Creates a client authorized with a personal access token, not scoped to
     * any workspace.
     */
    public static function from_personal_access_token(
        string $personal_access_token,
        ?string $endpoint = null,
        array $guzzle_options = [],
        ?int $retries = null,
        ?float $timeout = null,
    ): static {
        return new static(
            personal_access_token: $personal_access_token,
            endpoint: $endpoint,
            guzzle_options: $guzzle_options,
            retries: $retries,
            timeout: $timeout,
        );
    }

    /**
     * Creates a client from a preconfigured Guzzle client.
     */
    public static function from_client(ClientInterface $client): static
    {
        return new static(client: $client);
    }
}
