<?php

namespace Seam;

use GuzzleHttp\ClientInterface;
use Seam\Http\SeamHttpClient;
use Seam\Routes\WorkspacesClient;

/**
 * Client for the Seam API endpoints that are not scoped to a workspace.
 *
 * Authenticates with a personal access token and exposes only the workspace
 * listing and creation endpoints. Use Seam\Seam for everything else.
 */
class SeamWithoutWorkspace
{
    public const LTS_VERSION = SeamHttpClient::LTS_VERSION;

    public SeamHttpClient $client;

    public WorkspacesProxy $workspaces;

    /**
     * @param array<string, mixed> $guzzle_options
     */
    public function __construct(
        ?string $personal_access_token = null,
        ?string $endpoint = null,
        array $guzzle_options = [],
        ?int $retries = null,
        ?ClientInterface $client = null,
    ) {
        // A client carries its own endpoint and authorization, so the
        // authentication options are only read when one has to be built.
        if ($client !== null) {
            $this->client = SeamHttpClient::from_client($client);
        } else {
            if ($personal_access_token === null) {
                throw new InvalidOptionsError(
                    "Must specify a personal_access_token",
                );
            }

            $this->client = new SeamHttpClient(
                Options::get_endpoint($endpoint),
                Auth::get_auth_headers_for_personal_access_token_without_workspace(
                    $personal_access_token,
                ),
                $guzzle_options,
                $retries,
            );
        }

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
    ): static {
        return new static(
            personal_access_token: $personal_access_token,
            endpoint: $endpoint,
            guzzle_options: $guzzle_options,
            retries: $retries,
        );
    }

    /**
     * Creates a client from a preconfigured Guzzle client.
     */
    public static function from_client(ClientInterface $client): static
    {
        return new static(client: $client);
    }

    public function lts_version(): string
    {
        return self::LTS_VERSION;
    }
}
