<?php

namespace Seam;

use Seam\Routes\AccessCodesClient;
use Seam\Routes\AccessGrantsClient;
use Seam\Routes\AccessMethodsClient;
use Seam\Routes\AcsClient;
use Seam\Routes\ActionAttemptsClient;
use Seam\Routes\ClientSessionsClient;
use Seam\Routes\ConnectedAccountsClient;
use Seam\Routes\ConnectWebviewsClient;
use Seam\Routes\CustomersClient;
use Seam\Routes\DevicesClient;
use Seam\Routes\EventsClient;
use Seam\Routes\InstantKeysClient;
use Seam\Routes\LocksClient;
use Seam\Routes\NoiseSensorsClient;
use Seam\Routes\PhonesClient;
use Seam\Routes\SpacesClient;
use Seam\Routes\ThermostatsClient;
use Seam\Routes\UserIdentitiesClient;
use Seam\Routes\WebhooksClient;
use Seam\Routes\WorkspacesClient;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Seam\Http\ClientFactory;

/**
 * Client for the Seam API.
 *
 * Authenticate with an API key, which is scoped to a single workspace, or with
 * a personal access token together with the id of the workspace to act on.
 * When neither is given, the SEAM_API_KEY or SEAM_PERSONAL_ACCESS_TOKEN and
 * SEAM_WORKSPACE_ID environment variables are used.
 *
 * @see https://docs.seam.co/
 */
class Seam
{
    public AccessCodesClient $access_codes;
    public AccessGrantsClient $access_grants;
    public AccessMethodsClient $access_methods;
    public AcsClient $acs;
    public ActionAttemptsClient $action_attempts;
    public ClientSessionsClient $client_sessions;
    public ConnectWebviewsClient $connect_webviews;
    public ConnectedAccountsClient $connected_accounts;
    public CustomersClient $customers;
    public DevicesClient $devices;
    public EventsClient $events;
    public InstantKeysClient $instant_keys;
    public LocksClient $locks;
    public NoiseSensorsClient $noise_sensors;
    public PhonesClient $phones;
    public SpacesClient $spaces;
    public ThermostatsClient $thermostats;
    public UserIdentitiesClient $user_identities;
    public WebhooksClient $webhooks;
    public WorkspacesClient $workspaces;

    /**
     * The long term support version of the Seam API this SDK targets.
     */
    public const LTS_VERSION = ClientFactory::LTS_VERSION;

    /**
     * The Guzzle client this instance makes its requests with.
     */
    public ClientInterface $client;

    /**
     * Default request options applied to every call, currently just
     * wait_for_action_attempt.
     *
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    public array $defaults;

    /**
     * @param bool|array{timeout?: float, polling_interval?: float}|null $wait_for_action_attempt Whether to wait for action attempts to finish, optionally with timeout and polling_interval in seconds. Defaults to true.
     * @param array<string, mixed> $guzzle_options Options merged into the underlying Guzzle client, e.g. headers or proxy.
     * @param int|null $retries How many times to retry a failed request. Defaults to 2; pass 0 to disable.
     * @param float|null $timeout Request timeout in seconds, covering connecting and reading. Defaults to 30; pass 0 to disable.
     * @param ClientInterface|null $client A preconfigured Guzzle client, used as is. It carries its own endpoint and authorization, so no other authentication option applies to it.
     */
    public function __construct(
        ?string $api_key = null,
        ?string $personal_access_token = null,
        ?string $workspace_id = null,
        ?string $endpoint = null,
        bool|array|null $wait_for_action_attempt = null,
        array $guzzle_options = [],
        ?int $retries = null,
        ?float $timeout = null,
        ?ClientInterface $client = null,
    ) {
        $this->defaults = [
            "wait_for_action_attempt" => $wait_for_action_attempt ?? true,
        ];

        // A client carries its own endpoint and authorization, so the
        // authentication options are only read when one has to be built.
        $this->client =
            $client ??
            ClientFactory::create(
                Options::get_endpoint($endpoint),
                Auth::get_auth_headers(
                    $api_key,
                    $personal_access_token,
                    $workspace_id,
                ),
                $guzzle_options,
                $retries,
                $timeout,
            );

        $this->access_codes = new AccessCodesClient(
            $this->client,
            $this->defaults,
        );
        $this->access_grants = new AccessGrantsClient(
            $this->client,
            $this->defaults,
        );
        $this->access_methods = new AccessMethodsClient(
            $this->client,
            $this->defaults,
        );
        $this->acs = new AcsClient($this->client, $this->defaults);
        $this->action_attempts = new ActionAttemptsClient(
            $this->client,
            $this->defaults,
        );
        $this->client_sessions = new ClientSessionsClient(
            $this->client,
            $this->defaults,
        );
        $this->connect_webviews = new ConnectWebviewsClient(
            $this->client,
            $this->defaults,
        );
        $this->connected_accounts = new ConnectedAccountsClient(
            $this->client,
            $this->defaults,
        );
        $this->customers = new CustomersClient($this->client, $this->defaults);
        $this->devices = new DevicesClient($this->client, $this->defaults);
        $this->events = new EventsClient($this->client, $this->defaults);
        $this->instant_keys = new InstantKeysClient(
            $this->client,
            $this->defaults,
        );
        $this->locks = new LocksClient($this->client, $this->defaults);
        $this->noise_sensors = new NoiseSensorsClient(
            $this->client,
            $this->defaults,
        );
        $this->phones = new PhonesClient($this->client, $this->defaults);
        $this->spaces = new SpacesClient($this->client, $this->defaults);
        $this->thermostats = new ThermostatsClient(
            $this->client,
            $this->defaults,
        );
        $this->user_identities = new UserIdentitiesClient(
            $this->client,
            $this->defaults,
        );
        $this->webhooks = new WebhooksClient($this->client, $this->defaults);
        $this->workspaces = new WorkspacesClient(
            $this->client,
            $this->defaults,
        );
    }

    /**
     * Creates a client authorized with an API key.
     */
    public static function from_api_key(
        string $api_key,
        ?string $endpoint = null,
        bool|array|null $wait_for_action_attempt = null,
        array $guzzle_options = [],
        ?int $retries = null,
        ?float $timeout = null,
    ): static {
        return new static(
            api_key: $api_key,
            endpoint: $endpoint,
            wait_for_action_attempt: $wait_for_action_attempt,
            guzzle_options: $guzzle_options,
            retries: $retries,
            timeout: $timeout,
        );
    }

    /**
     * Creates a client authorized with a personal access token, scoped to the
     * given workspace.
     */
    public static function from_personal_access_token(
        string $personal_access_token,
        string $workspace_id,
        ?string $endpoint = null,
        bool|array|null $wait_for_action_attempt = null,
        array $guzzle_options = [],
        ?int $retries = null,
        ?float $timeout = null,
    ): static {
        return new static(
            personal_access_token: $personal_access_token,
            workspace_id: $workspace_id,
            endpoint: $endpoint,
            wait_for_action_attempt: $wait_for_action_attempt,
            retries: $retries,
            timeout: $timeout,
            guzzle_options: $guzzle_options,
        );
    }

    /**
     * Creates a client from a preconfigured Guzzle client.
     */
    public static function from_client(
        ClientInterface $client,
        bool|array|null $wait_for_action_attempt = null,
    ): static {
        return new static(
            client: $client,
            wait_for_action_attempt: $wait_for_action_attempt,
        );
    }

    public function lts_version(): string
    {
        return self::LTS_VERSION;
    }

    /**
     * Creates a paginator for a list endpoint.
     *
     * @param callable $request Invokes the list method with a params array, e.g. fn($params) => $seam->devices->list(...$params)
     * @param array<string, mixed> $params
     */
    public function createPaginator(
        callable $request,
        array $params = [],
    ): Paginator {
        return new Paginator($request, $params);
    }
}
