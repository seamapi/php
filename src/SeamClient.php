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
use Seam\Utils\PackageVersion;

use GuzzleHttp\Client as HTTPClient;
use \Exception as Exception;
use Seam\HttpApiError;
use Seam\HttpUnauthorizedError;
use Seam\HttpInvalidInputError;

define("LTS_VERSION", "1.0.0");

class SeamClient
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

    public string $api_key;
    public HTTPClient $client;
    public string $ltsVersion = LTS_VERSION;

    public function __construct(
        $api_key = null,
        $endpoint = "https://connect.getseam.com",
        $throw_http_errors = false,
    ) {
        $this->api_key = $api_key ?: (getenv("SEAM_API_KEY") ?: null);
        $seam_sdk_version = PackageVersion::get();
        $this->client = new HTTPClient([
            "base_uri" => $endpoint,
            "timeout" => 60.0,
            "headers" => [
                "Authorization" => "Bearer " . $this->api_key,
                "User-Agent" => "Seam PHP Client " . $seam_sdk_version,
                "seam-sdk-name" => "seamapi/php",
                "seam-sdk-version" => $seam_sdk_version,
                "seam-lts-version" => $this->ltsVersion,
            ],
            "http_errors" => $throw_http_errors,
        ]);
        $this->access_codes = new AccessCodesClient($this);
        $this->access_grants = new AccessGrantsClient($this);
        $this->access_methods = new AccessMethodsClient($this);
        $this->acs = new AcsClient($this);
        $this->action_attempts = new ActionAttemptsClient($this);
        $this->client_sessions = new ClientSessionsClient($this);
        $this->connect_webviews = new ConnectWebviewsClient($this);
        $this->connected_accounts = new ConnectedAccountsClient($this);
        $this->customers = new CustomersClient($this);
        $this->devices = new DevicesClient($this);
        $this->events = new EventsClient($this);
        $this->instant_keys = new InstantKeysClient($this);
        $this->locks = new LocksClient($this);
        $this->noise_sensors = new NoiseSensorsClient($this);
        $this->phones = new PhonesClient($this);
        $this->spaces = new SpacesClient($this);
        $this->thermostats = new ThermostatsClient($this);
        $this->user_identities = new UserIdentitiesClient($this);
        $this->webhooks = new WebhooksClient($this);
        $this->workspaces = new WorkspacesClient($this);
    }

    public function request($method, $path, $json = null, $query = null)
    {
        $options = [
            "json" => $json,
            "query" => $query,
        ];
        $options = array_filter($options, fn($option) => $option !== null);

        $response = $this->client->request($method, $path, $options);
        $status_code = $response->getStatusCode();
        $request_id = $response->getHeaderLine("seam-request-id");

        $res_json = null;
        try {
            $res_json = json_decode($response->getBody());
        } catch (Exception $ignoreError) {
        }

        if ($status_code >= 400) {
            if ($status_code === 401) {
                throw new HttpUnauthorizedError($request_id);
            }

            if (($res_json->error ?? null) != null) {
                if ($res_json->error->type === "invalid_input") {
                    throw new HttpInvalidInputError(
                        $res_json->error,
                        $status_code,
                        $request_id,
                    );
                }

                throw new HttpApiError(
                    $res_json->error,
                    $status_code,
                    $request_id,
                );
            }

            throw \GuzzleHttp\Exception\RequestException::create(
                new \GuzzleHttp\Psr7\Request($method, $path),
                $response,
            );
        }

        return $res_json;
    }

    public function createPaginator($request, $params = [])
    {
        return new Paginator($request, $params);
    }
}
