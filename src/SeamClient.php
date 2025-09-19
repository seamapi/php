<?php

namespace Seam;

use Seam\Objects\AccessCode;
use Seam\Objects\AccessGrant;
use Seam\Objects\AccessMethod;
use Seam\Objects\AcsAccessGroup;
use Seam\Objects\AcsCredential;
use Seam\Objects\AcsCredentialPool;
use Seam\Objects\AcsCredentialProvisioningAutomation;
use Seam\Objects\AcsEncoder;
use Seam\Objects\AcsEntrance;
use Seam\Objects\AcsSystem;
use Seam\Objects\AcsUser;
use Seam\Objects\ActionAttempt;
use Seam\Objects\BridgeClientSession;
use Seam\Objects\BridgeConnectedSystems;
use Seam\Objects\ClientSession;
use Seam\Objects\ConnectWebview;
use Seam\Objects\ConnectedAccount;
use Seam\Objects\CustomizationProfile;
use Seam\Objects\Device;
use Seam\Objects\DeviceProvider;
use Seam\Objects\EnrollmentAutomation;
use Seam\Objects\Event;
use Seam\Objects\InstantKey;
use Seam\Objects\MagicLink;
use Seam\Objects\NoiseThreshold;
use Seam\Objects\Pagination;
use Seam\Objects\Phone;
use Seam\Objects\PhoneRegistration;
use Seam\Objects\PhoneSession;
use Seam\Objects\Space;
use Seam\Objects\ThermostatDailyProgram;
use Seam\Objects\ThermostatSchedule;
use Seam\Objects\UnmanagedAccessCode;
use Seam\Objects\UnmanagedAcsAccessGroup;
use Seam\Objects\UnmanagedAcsCredential;
use Seam\Objects\UnmanagedAcsUser;
use Seam\Objects\UnmanagedDevice;
use Seam\Objects\UserIdentity;
use Seam\Objects\Webhook;
use Seam\Objects\Workspace;
use Seam\Utils\PackageVersion;

use GuzzleHttp\Client as HTTPClient;
use \Exception as Exception;
use Seam\HttpApiError;
use Seam\HttpUnauthorizedError;
use Seam\HttpInvalidInputError;
use Seam\ActionAttemptFailedError;
use Seam\ActionAttemptTimeoutError;

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

        // TODO handle request errors
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

            throw GuzzleHttpExceptionRequestException::create(
                new GuzzleHttpPsr7Request($method, $path),
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

class AccessCodesClient
{
    private SeamClient $seam;
    public AccessCodesSimulateClient $simulate;
    public AccessCodesUnmanagedClient $unmanaged;
    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
        $this->simulate = new AccessCodesSimulateClient($seam);
        $this->unmanaged = new AccessCodesUnmanagedClient($seam);
    }

    public function create(
        string $device_id,
        ?bool $allow_external_modification = null,
        ?bool $attempt_for_offline_device = null,
        ?string $code = null,
        ?string $common_code_key = null,
        ?string $ends_at = null,
        ?bool $is_external_modification_allowed = null,
        ?bool $is_offline_access_code = null,
        ?bool $is_one_time_use = null,
        ?string $max_time_rounding = null,
        ?string $name = null,
        ?bool $prefer_native_scheduling = null,
        ?float $preferred_code_length = null,
        ?string $starts_at = null,
        ?bool $sync = null,
        ?bool $use_backup_access_code_pool = null,
        ?bool $use_offline_access_code = null,
    ): AccessCode {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($allow_external_modification !== null) {
            $request_payload[
                "allow_external_modification"
            ] = $allow_external_modification;
        }
        if ($attempt_for_offline_device !== null) {
            $request_payload[
                "attempt_for_offline_device"
            ] = $attempt_for_offline_device;
        }
        if ($code !== null) {
            $request_payload["code"] = $code;
        }
        if ($common_code_key !== null) {
            $request_payload["common_code_key"] = $common_code_key;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($is_external_modification_allowed !== null) {
            $request_payload[
                "is_external_modification_allowed"
            ] = $is_external_modification_allowed;
        }
        if ($is_offline_access_code !== null) {
            $request_payload[
                "is_offline_access_code"
            ] = $is_offline_access_code;
        }
        if ($is_one_time_use !== null) {
            $request_payload["is_one_time_use"] = $is_one_time_use;
        }
        if ($max_time_rounding !== null) {
            $request_payload["max_time_rounding"] = $max_time_rounding;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($prefer_native_scheduling !== null) {
            $request_payload[
                "prefer_native_scheduling"
            ] = $prefer_native_scheduling;
        }
        if ($preferred_code_length !== null) {
            $request_payload["preferred_code_length"] = $preferred_code_length;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }
        if ($use_backup_access_code_pool !== null) {
            $request_payload[
                "use_backup_access_code_pool"
            ] = $use_backup_access_code_pool;
        }
        if ($use_offline_access_code !== null) {
            $request_payload[
                "use_offline_access_code"
            ] = $use_offline_access_code;
        }

        $res = $this->seam->request(
            "POST",
            "/access_codes/create",
            json: (object) $request_payload,
        );

        return AccessCode::from_json($res->access_code);
    }

    public function create_multiple(
        array $device_ids,
        ?bool $allow_external_modification = null,
        ?bool $attempt_for_offline_device = null,
        ?string $behavior_when_code_cannot_be_shared = null,
        ?string $code = null,
        ?string $ends_at = null,
        ?bool $is_external_modification_allowed = null,
        ?bool $is_offline_access_code = null,
        ?bool $is_one_time_use = null,
        ?string $max_time_rounding = null,
        ?string $name = null,
        ?bool $prefer_native_scheduling = null,
        ?float $preferred_code_length = null,
        ?string $starts_at = null,
        ?bool $use_backup_access_code_pool = null,
        ?bool $use_offline_access_code = null,
    ): array {
        $request_payload = [];

        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($allow_external_modification !== null) {
            $request_payload[
                "allow_external_modification"
            ] = $allow_external_modification;
        }
        if ($attempt_for_offline_device !== null) {
            $request_payload[
                "attempt_for_offline_device"
            ] = $attempt_for_offline_device;
        }
        if ($behavior_when_code_cannot_be_shared !== null) {
            $request_payload[
                "behavior_when_code_cannot_be_shared"
            ] = $behavior_when_code_cannot_be_shared;
        }
        if ($code !== null) {
            $request_payload["code"] = $code;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($is_external_modification_allowed !== null) {
            $request_payload[
                "is_external_modification_allowed"
            ] = $is_external_modification_allowed;
        }
        if ($is_offline_access_code !== null) {
            $request_payload[
                "is_offline_access_code"
            ] = $is_offline_access_code;
        }
        if ($is_one_time_use !== null) {
            $request_payload["is_one_time_use"] = $is_one_time_use;
        }
        if ($max_time_rounding !== null) {
            $request_payload["max_time_rounding"] = $max_time_rounding;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($prefer_native_scheduling !== null) {
            $request_payload[
                "prefer_native_scheduling"
            ] = $prefer_native_scheduling;
        }
        if ($preferred_code_length !== null) {
            $request_payload["preferred_code_length"] = $preferred_code_length;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }
        if ($use_backup_access_code_pool !== null) {
            $request_payload[
                "use_backup_access_code_pool"
            ] = $use_backup_access_code_pool;
        }
        if ($use_offline_access_code !== null) {
            $request_payload[
                "use_offline_access_code"
            ] = $use_offline_access_code;
        }

        $res = $this->seam->request(
            "POST",
            "/access_codes/create_multiple",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => AccessCode::from_json($r),
            $res->access_codes,
        );
    }

    public function delete(
        string $access_code_id,
        ?string $device_id = null,
        ?bool $sync = null,
    ): void {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $this->seam->request(
            "POST",
            "/access_codes/delete",
            json: (object) $request_payload,
        );
    }

    public function generate_code(string $device_id): AccessCode
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $res = $this->seam->request(
            "POST",
            "/access_codes/generate_code",
            json: (object) $request_payload,
        );

        return AccessCode::from_json($res->generated_code);
    }

    public function get(
        ?string $access_code_id = null,
        ?string $code = null,
        ?string $device_id = null,
    ): AccessCode {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($code !== null) {
            $request_payload["code"] = $code;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $res = $this->seam->request(
            "POST",
            "/access_codes/get",
            json: (object) $request_payload,
        );

        return AccessCode::from_json($res->access_code);
    }

    public function list(
        ?array $access_code_ids = null,
        ?string $customer_key = null,
        ?string $device_id = null,
        ?float $limit = null,
        ?string $page_cursor = null,
        ?string $user_identifier_key = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($access_code_ids !== null) {
            $request_payload["access_code_ids"] = $access_code_ids;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/access_codes/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => AccessCode::from_json($r),
            $res->access_codes,
        );
    }

    public function pull_backup_access_code(string $access_code_id): AccessCode
    {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }

        $res = $this->seam->request(
            "POST",
            "/access_codes/pull_backup_access_code",
            json: (object) $request_payload,
        );

        return AccessCode::from_json($res->access_code);
    }

    public function report_device_constraints(
        string $device_id,
        mixed $max_code_length = null,
        mixed $min_code_length = null,
        ?array $supported_code_lengths = null,
    ): void {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($max_code_length !== null) {
            $request_payload["max_code_length"] = $max_code_length;
        }
        if ($min_code_length !== null) {
            $request_payload["min_code_length"] = $min_code_length;
        }
        if ($supported_code_lengths !== null) {
            $request_payload[
                "supported_code_lengths"
            ] = $supported_code_lengths;
        }

        $this->seam->request(
            "POST",
            "/access_codes/report_device_constraints",
            json: (object) $request_payload,
        );
    }

    public function update(
        string $access_code_id,
        ?bool $allow_external_modification = null,
        ?bool $attempt_for_offline_device = null,
        ?string $code = null,
        ?string $device_id = null,
        ?string $ends_at = null,
        ?bool $is_external_modification_allowed = null,
        ?bool $is_managed = null,
        ?bool $is_offline_access_code = null,
        ?bool $is_one_time_use = null,
        ?string $max_time_rounding = null,
        ?string $name = null,
        ?bool $prefer_native_scheduling = null,
        ?float $preferred_code_length = null,
        ?string $starts_at = null,
        ?bool $sync = null,
        ?string $type = null,
        ?bool $use_backup_access_code_pool = null,
        ?bool $use_offline_access_code = null,
    ): void {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($allow_external_modification !== null) {
            $request_payload[
                "allow_external_modification"
            ] = $allow_external_modification;
        }
        if ($attempt_for_offline_device !== null) {
            $request_payload[
                "attempt_for_offline_device"
            ] = $attempt_for_offline_device;
        }
        if ($code !== null) {
            $request_payload["code"] = $code;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($is_external_modification_allowed !== null) {
            $request_payload[
                "is_external_modification_allowed"
            ] = $is_external_modification_allowed;
        }
        if ($is_managed !== null) {
            $request_payload["is_managed"] = $is_managed;
        }
        if ($is_offline_access_code !== null) {
            $request_payload[
                "is_offline_access_code"
            ] = $is_offline_access_code;
        }
        if ($is_one_time_use !== null) {
            $request_payload["is_one_time_use"] = $is_one_time_use;
        }
        if ($max_time_rounding !== null) {
            $request_payload["max_time_rounding"] = $max_time_rounding;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($prefer_native_scheduling !== null) {
            $request_payload[
                "prefer_native_scheduling"
            ] = $prefer_native_scheduling;
        }
        if ($preferred_code_length !== null) {
            $request_payload["preferred_code_length"] = $preferred_code_length;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }
        if ($type !== null) {
            $request_payload["type"] = $type;
        }
        if ($use_backup_access_code_pool !== null) {
            $request_payload[
                "use_backup_access_code_pool"
            ] = $use_backup_access_code_pool;
        }
        if ($use_offline_access_code !== null) {
            $request_payload[
                "use_offline_access_code"
            ] = $use_offline_access_code;
        }

        $this->seam->request(
            "POST",
            "/access_codes/update",
            json: (object) $request_payload,
        );
    }

    public function update_multiple(
        string $common_code_key,
        ?string $ends_at = null,
        ?string $name = null,
        ?string $starts_at = null,
    ): void {
        $request_payload = [];

        if ($common_code_key !== null) {
            $request_payload["common_code_key"] = $common_code_key;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }

        $this->seam->request(
            "POST",
            "/access_codes/update_multiple",
            json: (object) $request_payload,
        );
    }
}

class AccessCodesSimulateClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function create_unmanaged_access_code(
        string $code,
        string $device_id,
        string $name,
    ): UnmanagedAccessCode {
        $request_payload = [];

        if ($code !== null) {
            $request_payload["code"] = $code;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }

        $res = $this->seam->request(
            "POST",
            "/access_codes/simulate/create_unmanaged_access_code",
            json: (object) $request_payload,
        );

        return UnmanagedAccessCode::from_json($res->access_code);
    }
}

class AccessCodesUnmanagedClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function convert_to_managed(
        string $access_code_id,
        ?bool $allow_external_modification = null,
        ?bool $force = null,
        ?bool $is_external_modification_allowed = null,
        ?bool $sync = null,
    ): void {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($allow_external_modification !== null) {
            $request_payload[
                "allow_external_modification"
            ] = $allow_external_modification;
        }
        if ($force !== null) {
            $request_payload["force"] = $force;
        }
        if ($is_external_modification_allowed !== null) {
            $request_payload[
                "is_external_modification_allowed"
            ] = $is_external_modification_allowed;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $this->seam->request(
            "POST",
            "/access_codes/unmanaged/convert_to_managed",
            json: (object) $request_payload,
        );
    }

    public function delete(string $access_code_id, ?bool $sync = null): void
    {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $this->seam->request(
            "POST",
            "/access_codes/unmanaged/delete",
            json: (object) $request_payload,
        );
    }

    public function get(
        ?string $access_code_id = null,
        ?string $code = null,
        ?string $device_id = null,
    ): UnmanagedAccessCode {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($code !== null) {
            $request_payload["code"] = $code;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $res = $this->seam->request(
            "POST",
            "/access_codes/unmanaged/get",
            json: (object) $request_payload,
        );

        return UnmanagedAccessCode::from_json($res->access_code);
    }

    public function list(
        string $device_id,
        ?float $limit = null,
        ?string $page_cursor = null,
        ?string $user_identifier_key = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/access_codes/unmanaged/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => UnmanagedAccessCode::from_json($r),
            $res->access_codes,
        );
    }

    public function update(
        string $access_code_id,
        bool $is_managed,
        ?bool $allow_external_modification = null,
        ?bool $force = null,
        ?bool $is_external_modification_allowed = null,
    ): void {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($is_managed !== null) {
            $request_payload["is_managed"] = $is_managed;
        }
        if ($allow_external_modification !== null) {
            $request_payload[
                "allow_external_modification"
            ] = $allow_external_modification;
        }
        if ($force !== null) {
            $request_payload["force"] = $force;
        }
        if ($is_external_modification_allowed !== null) {
            $request_payload[
                "is_external_modification_allowed"
            ] = $is_external_modification_allowed;
        }

        $this->seam->request(
            "POST",
            "/access_codes/unmanaged/update",
            json: (object) $request_payload,
        );
    }
}

class AccessGrantsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function create(
        array $requested_access_methods,
        ?string $user_identity_id = null,
        mixed $user_identity = null,
        ?string $access_grant_key = null,
        ?array $acs_entrance_ids = null,
        ?string $customization_profile_id = null,
        ?array $device_ids = null,
        ?string $ends_at = null,
        mixed $location = null,
        ?array $location_ids = null,
        ?string $name = null,
        ?array $space_ids = null,
        ?array $space_keys = null,
        ?string $starts_at = null,
    ): AccessGrant {
        $request_payload = [];

        if ($requested_access_methods !== null) {
            $request_payload[
                "requested_access_methods"
            ] = $requested_access_methods;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($user_identity !== null) {
            $request_payload["user_identity"] = $user_identity;
        }
        if ($access_grant_key !== null) {
            $request_payload["access_grant_key"] = $access_grant_key;
        }
        if ($acs_entrance_ids !== null) {
            $request_payload["acs_entrance_ids"] = $acs_entrance_ids;
        }
        if ($customization_profile_id !== null) {
            $request_payload[
                "customization_profile_id"
            ] = $customization_profile_id;
        }
        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($location !== null) {
            $request_payload["location"] = $location;
        }
        if ($location_ids !== null) {
            $request_payload["location_ids"] = $location_ids;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($space_ids !== null) {
            $request_payload["space_ids"] = $space_ids;
        }
        if ($space_keys !== null) {
            $request_payload["space_keys"] = $space_keys;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }

        $res = $this->seam->request(
            "POST",
            "/access_grants/create",
            json: (object) $request_payload,
        );

        return AccessGrant::from_json($res->access_grant);
    }

    public function delete(string $access_grant_id): void
    {
        $request_payload = [];

        if ($access_grant_id !== null) {
            $request_payload["access_grant_id"] = $access_grant_id;
        }

        $this->seam->request(
            "POST",
            "/access_grants/delete",
            json: (object) $request_payload,
        );
    }

    public function get(
        ?string $access_grant_id = null,
        ?string $access_grant_key = null,
    ): AccessGrant {
        $request_payload = [];

        if ($access_grant_id !== null) {
            $request_payload["access_grant_id"] = $access_grant_id;
        }
        if ($access_grant_key !== null) {
            $request_payload["access_grant_key"] = $access_grant_key;
        }

        $res = $this->seam->request(
            "POST",
            "/access_grants/get",
            json: (object) $request_payload,
        );

        return AccessGrant::from_json($res->access_grant);
    }

    public function get_related(
        array $access_grant_ids,
        ?array $exclude = null,
        ?array $include = null,
    ): void {
        $request_payload = [];

        if ($access_grant_ids !== null) {
            $request_payload["access_grant_ids"] = $access_grant_ids;
        }
        if ($exclude !== null) {
            $request_payload["exclude"] = $exclude;
        }
        if ($include !== null) {
            $request_payload["include"] = $include;
        }

        $this->seam->request(
            "POST",
            "/access_grants/get_related",
            json: (object) $request_payload,
        );
    }

    public function list(
        ?string $access_grant_key = null,
        ?string $acs_entrance_id = null,
        ?string $acs_system_id = null,
        ?string $customer_key = null,
        ?string $location_id = null,
        ?string $space_id = null,
        ?string $user_identity_id = null,
    ): array {
        $request_payload = [];

        if ($access_grant_key !== null) {
            $request_payload["access_grant_key"] = $access_grant_key;
        }
        if ($acs_entrance_id !== null) {
            $request_payload["acs_entrance_id"] = $acs_entrance_id;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($location_id !== null) {
            $request_payload["location_id"] = $location_id;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->request(
            "POST",
            "/access_grants/list",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => AccessGrant::from_json($r),
            $res->access_grants,
        );
    }

    public function update(
        string $access_grant_id,
        ?string $ends_at = null,
        ?string $name = null,
        ?string $starts_at = null,
    ): void {
        $request_payload = [];

        if ($access_grant_id !== null) {
            $request_payload["access_grant_id"] = $access_grant_id;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }

        $this->seam->request(
            "POST",
            "/access_grants/update",
            json: (object) $request_payload,
        );
    }
}

class AccessMethodsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function delete(string $access_method_id): void
    {
        $request_payload = [];

        if ($access_method_id !== null) {
            $request_payload["access_method_id"] = $access_method_id;
        }

        $this->seam->request(
            "POST",
            "/access_methods/delete",
            json: (object) $request_payload,
        );
    }

    public function encode(
        string $access_method_id,
        string $acs_encoder_id,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($access_method_id !== null) {
            $request_payload["access_method_id"] = $access_method_id;
        }
        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }

        $res = $this->seam->request(
            "POST",
            "/access_methods/encode",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }

    public function get(string $access_method_id): AccessMethod
    {
        $request_payload = [];

        if ($access_method_id !== null) {
            $request_payload["access_method_id"] = $access_method_id;
        }

        $res = $this->seam->request(
            "POST",
            "/access_methods/get",
            json: (object) $request_payload,
        );

        return AccessMethod::from_json($res->access_method);
    }

    public function get_related(
        array $access_method_ids,
        ?array $exclude = null,
        ?array $include = null,
    ): void {
        $request_payload = [];

        if ($access_method_ids !== null) {
            $request_payload["access_method_ids"] = $access_method_ids;
        }
        if ($exclude !== null) {
            $request_payload["exclude"] = $exclude;
        }
        if ($include !== null) {
            $request_payload["include"] = $include;
        }

        $this->seam->request(
            "POST",
            "/access_methods/get_related",
            json: (object) $request_payload,
        );
    }

    public function list(
        string $access_grant_id,
        ?string $acs_entrance_id = null,
        ?string $device_id = null,
        ?string $space_id = null,
    ): array {
        $request_payload = [];

        if ($access_grant_id !== null) {
            $request_payload["access_grant_id"] = $access_grant_id;
        }
        if ($acs_entrance_id !== null) {
            $request_payload["acs_entrance_id"] = $acs_entrance_id;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }

        $res = $this->seam->request(
            "POST",
            "/access_methods/list",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => AccessMethod::from_json($r),
            $res->access_methods,
        );
    }
}

class AcsAccessGroupsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function add_user(
        string $acs_access_group_id,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->seam->request(
            "POST",
            "/acs/access_groups/add_user",
            json: (object) $request_payload,
        );
    }

    public function get(string $acs_access_group_id): AcsAccessGroup
    {
        $request_payload = [];

        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/access_groups/get",
            json: (object) $request_payload,
        );

        return AcsAccessGroup::from_json($res->acs_access_group);
    }

    public function list(
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): array {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/access_groups/list",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => AcsAccessGroup::from_json($r),
            $res->acs_access_groups,
        );
    }

    public function list_accessible_entrances(
        string $acs_access_group_id,
    ): array {
        $request_payload = [];

        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/access_groups/list_accessible_entrances",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => AcsEntrance::from_json($r),
            $res->acs_entrances,
        );
    }

    public function list_users(string $acs_access_group_id): array
    {
        $request_payload = [];

        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/access_groups/list_users",
            json: (object) $request_payload,
        );

        return array_map(fn($r) => AcsUser::from_json($r), $res->acs_users);
    }

    public function remove_user(
        string $acs_access_group_id,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->seam->request(
            "POST",
            "/acs/access_groups/remove_user",
            json: (object) $request_payload,
        );
    }
}

class AcsClient
{
    private SeamClient $seam;
    public AcsAccessGroupsClient $access_groups;
    public AcsCredentialsClient $credentials;
    public AcsEncodersClient $encoders;
    public AcsEntrancesClient $entrances;
    public AcsSystemsClient $systems;
    public AcsUsersClient $users;
    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
        $this->access_groups = new AcsAccessGroupsClient($seam);
        $this->credentials = new AcsCredentialsClient($seam);
        $this->encoders = new AcsEncodersClient($seam);
        $this->entrances = new AcsEntrancesClient($seam);
        $this->systems = new AcsSystemsClient($seam);
        $this->users = new AcsUsersClient($seam);
    }
}

class AcsCredentialsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function assign(
        string $acs_credential_id,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->seam->request(
            "POST",
            "/acs/credentials/assign",
            json: (object) $request_payload,
        );
    }

    public function create(
        string $access_method,
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?array $allowed_acs_entrance_ids = null,
        mixed $assa_abloy_vostio_metadata = null,
        ?string $code = null,
        ?string $credential_manager_acs_system_id = null,
        ?string $ends_at = null,
        mixed $hotek_metadata = null,
        ?bool $is_multi_phone_sync_credential = null,
        mixed $salto_space_metadata = null,
        ?string $starts_at = null,
        ?string $user_identity_id = null,
        mixed $visionline_metadata = null,
    ): AcsCredential {
        $request_payload = [];

        if ($access_method !== null) {
            $request_payload["access_method"] = $access_method;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($allowed_acs_entrance_ids !== null) {
            $request_payload[
                "allowed_acs_entrance_ids"
            ] = $allowed_acs_entrance_ids;
        }
        if ($assa_abloy_vostio_metadata !== null) {
            $request_payload[
                "assa_abloy_vostio_metadata"
            ] = $assa_abloy_vostio_metadata;
        }
        if ($code !== null) {
            $request_payload["code"] = $code;
        }
        if ($credential_manager_acs_system_id !== null) {
            $request_payload[
                "credential_manager_acs_system_id"
            ] = $credential_manager_acs_system_id;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($hotek_metadata !== null) {
            $request_payload["hotek_metadata"] = $hotek_metadata;
        }
        if ($is_multi_phone_sync_credential !== null) {
            $request_payload[
                "is_multi_phone_sync_credential"
            ] = $is_multi_phone_sync_credential;
        }
        if ($salto_space_metadata !== null) {
            $request_payload["salto_space_metadata"] = $salto_space_metadata;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($visionline_metadata !== null) {
            $request_payload["visionline_metadata"] = $visionline_metadata;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/credentials/create",
            json: (object) $request_payload,
        );

        return AcsCredential::from_json($res->acs_credential);
    }

    public function delete(string $acs_credential_id): void
    {
        $request_payload = [];

        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }

        $this->seam->request(
            "POST",
            "/acs/credentials/delete",
            json: (object) $request_payload,
        );
    }

    public function get(string $acs_credential_id): AcsCredential
    {
        $request_payload = [];

        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/credentials/get",
            json: (object) $request_payload,
        );

        return AcsCredential::from_json($res->acs_credential);
    }

    public function list(
        ?string $acs_user_id = null,
        ?string $acs_system_id = null,
        ?string $user_identity_id = null,
        ?string $created_before = null,
        ?bool $is_multi_phone_sync_credential = null,
        ?float $limit = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($created_before !== null) {
            $request_payload["created_before"] = $created_before;
        }
        if ($is_multi_phone_sync_credential !== null) {
            $request_payload[
                "is_multi_phone_sync_credential"
            ] = $is_multi_phone_sync_credential;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/credentials/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => AcsCredential::from_json($r),
            $res->acs_credentials,
        );
    }

    public function list_accessible_entrances(string $acs_credential_id): array
    {
        $request_payload = [];

        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/credentials/list_accessible_entrances",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => AcsEntrance::from_json($r),
            $res->acs_entrances,
        );
    }

    public function unassign(
        string $acs_credential_id,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->seam->request(
            "POST",
            "/acs/credentials/unassign",
            json: (object) $request_payload,
        );
    }

    public function update(
        string $acs_credential_id,
        ?string $code = null,
        ?string $ends_at = null,
    ): void {
        $request_payload = [];

        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }
        if ($code !== null) {
            $request_payload["code"] = $code;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }

        $this->seam->request(
            "POST",
            "/acs/credentials/update",
            json: (object) $request_payload,
        );
    }
}

class AcsEncodersClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function encode_credential(
        string $acs_encoder_id,
        ?string $access_method_id = null,
        ?string $acs_credential_id = null,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }
        if ($access_method_id !== null) {
            $request_payload["access_method_id"] = $access_method_id;
        }
        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/encoders/encode_credential",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }

    public function get(string $acs_encoder_id): AcsEncoder
    {
        $request_payload = [];

        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/encoders/get",
            json: (object) $request_payload,
        );

        return AcsEncoder::from_json($res->acs_encoder);
    }

    public function list(
        ?string $acs_system_id = null,
        ?array $acs_system_ids = null,
        ?array $acs_encoder_ids = null,
        ?float $limit = null,
        ?string $page_cursor = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_system_ids !== null) {
            $request_payload["acs_system_ids"] = $acs_system_ids;
        }
        if ($acs_encoder_ids !== null) {
            $request_payload["acs_encoder_ids"] = $acs_encoder_ids;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/encoders/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => AcsEncoder::from_json($r),
            $res->acs_encoders,
        );
    }

    public function scan_credential(
        string $acs_encoder_id,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/encoders/scan_credential",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }
}

class AcsEncodersSimulateClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function next_credential_encode_will_fail(
        string $acs_encoder_id,
        ?string $error_code = null,
        ?string $acs_credential_id = null,
    ): void {
        $request_payload = [];

        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }
        if ($error_code !== null) {
            $request_payload["error_code"] = $error_code;
        }
        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }

        $this->seam->request(
            "POST",
            "/acs/encoders/simulate/next_credential_encode_will_fail",
            json: (object) $request_payload,
        );
    }

    public function next_credential_encode_will_succeed(
        string $acs_encoder_id,
        ?string $scenario = null,
    ): void {
        $request_payload = [];

        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }
        if ($scenario !== null) {
            $request_payload["scenario"] = $scenario;
        }

        $this->seam->request(
            "POST",
            "/acs/encoders/simulate/next_credential_encode_will_succeed",
            json: (object) $request_payload,
        );
    }

    public function next_credential_scan_will_fail(
        string $acs_encoder_id,
        ?string $error_code = null,
        ?string $acs_credential_id_on_seam = null,
    ): void {
        $request_payload = [];

        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }
        if ($error_code !== null) {
            $request_payload["error_code"] = $error_code;
        }
        if ($acs_credential_id_on_seam !== null) {
            $request_payload[
                "acs_credential_id_on_seam"
            ] = $acs_credential_id_on_seam;
        }

        $this->seam->request(
            "POST",
            "/acs/encoders/simulate/next_credential_scan_will_fail",
            json: (object) $request_payload,
        );
    }

    public function next_credential_scan_will_succeed(
        string $acs_encoder_id,
        ?string $acs_credential_id_on_seam = null,
        ?string $scenario = null,
    ): void {
        $request_payload = [];

        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }
        if ($acs_credential_id_on_seam !== null) {
            $request_payload[
                "acs_credential_id_on_seam"
            ] = $acs_credential_id_on_seam;
        }
        if ($scenario !== null) {
            $request_payload["scenario"] = $scenario;
        }

        $this->seam->request(
            "POST",
            "/acs/encoders/simulate/next_credential_scan_will_succeed",
            json: (object) $request_payload,
        );
    }
}

class AcsEntrancesClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function get(string $acs_entrance_id): AcsEntrance
    {
        $request_payload = [];

        if ($acs_entrance_id !== null) {
            $request_payload["acs_entrance_id"] = $acs_entrance_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/entrances/get",
            json: (object) $request_payload,
        );

        return AcsEntrance::from_json($res->acs_entrance);
    }

    public function grant_access(
        string $acs_entrance_id,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        if ($acs_entrance_id !== null) {
            $request_payload["acs_entrance_id"] = $acs_entrance_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->seam->request(
            "POST",
            "/acs/entrances/grant_access",
            json: (object) $request_payload,
        );
    }

    public function list(
        ?string $acs_credential_id = null,
        ?array $acs_entrance_ids = null,
        ?string $acs_system_id = null,
        ?string $connected_account_id = null,
        ?string $customer_key = null,
        mixed $limit = null,
        ?string $location_id = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?string $space_id = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }
        if ($acs_entrance_ids !== null) {
            $request_payload["acs_entrance_ids"] = $acs_entrance_ids;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($location_id !== null) {
            $request_payload["location_id"] = $location_id;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/entrances/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => AcsEntrance::from_json($r),
            $res->acs_entrances,
        );
    }

    public function list_credentials_with_access(
        string $acs_entrance_id,
        ?array $include_if = null,
    ): array {
        $request_payload = [];

        if ($acs_entrance_id !== null) {
            $request_payload["acs_entrance_id"] = $acs_entrance_id;
        }
        if ($include_if !== null) {
            $request_payload["include_if"] = $include_if;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/entrances/list_credentials_with_access",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => AcsCredential::from_json($r),
            $res->acs_credentials,
        );
    }
}

class AcsSystemsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function get(string $acs_system_id): AcsSystem
    {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/systems/get",
            json: (object) $request_payload,
        );

        return AcsSystem::from_json($res->acs_system);
    }

    public function list(
        ?string $connected_account_id = null,
        ?string $customer_key = null,
    ): array {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/systems/list",
            json: (object) $request_payload,
        );

        return array_map(fn($r) => AcsSystem::from_json($r), $res->acs_systems);
    }

    public function list_compatible_credential_manager_acs_systems(
        string $acs_system_id,
    ): array {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/systems/list_compatible_credential_manager_acs_systems",
            json: (object) $request_payload,
        );

        return array_map(fn($r) => AcsSystem::from_json($r), $res->acs_systems);
    }
}

class AcsUsersClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function add_to_access_group(
        string $acs_access_group_id,
        string $acs_user_id,
    ): void {
        $request_payload = [];

        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $this->seam->request(
            "POST",
            "/acs/users/add_to_access_group",
            json: (object) $request_payload,
        );
    }

    public function create(
        string $acs_system_id,
        string $full_name,
        mixed $access_schedule = null,
        ?array $acs_access_group_ids = null,
        ?string $email = null,
        ?string $email_address = null,
        ?string $phone_number = null,
        ?string $user_identity_id = null,
    ): AcsUser {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($full_name !== null) {
            $request_payload["full_name"] = $full_name;
        }
        if ($access_schedule !== null) {
            $request_payload["access_schedule"] = $access_schedule;
        }
        if ($acs_access_group_ids !== null) {
            $request_payload["acs_access_group_ids"] = $acs_access_group_ids;
        }
        if ($email !== null) {
            $request_payload["email"] = $email;
        }
        if ($email_address !== null) {
            $request_payload["email_address"] = $email_address;
        }
        if ($phone_number !== null) {
            $request_payload["phone_number"] = $phone_number;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/users/create",
            json: (object) $request_payload,
        );

        return AcsUser::from_json($res->acs_user);
    }

    public function delete(
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->seam->request(
            "POST",
            "/acs/users/delete",
            json: (object) $request_payload,
        );
    }

    public function get(
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): AcsUser {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/users/get",
            json: (object) $request_payload,
        );

        return AcsUser::from_json($res->acs_user);
    }

    public function list(
        ?string $acs_system_id = null,
        ?string $created_before = null,
        mixed $limit = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?string $user_identity_email_address = null,
        ?string $user_identity_id = null,
        ?string $user_identity_phone_number = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($created_before !== null) {
            $request_payload["created_before"] = $created_before;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
        }
        if ($user_identity_email_address !== null) {
            $request_payload[
                "user_identity_email_address"
            ] = $user_identity_email_address;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($user_identity_phone_number !== null) {
            $request_payload[
                "user_identity_phone_number"
            ] = $user_identity_phone_number;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/users/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(fn($r) => AcsUser::from_json($r), $res->acs_users);
    }

    public function list_accessible_entrances(
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): array {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/users/list_accessible_entrances",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => AcsEntrance::from_json($r),
            $res->acs_entrances,
        );
    }

    public function remove_from_access_group(
        string $acs_access_group_id,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->seam->request(
            "POST",
            "/acs/users/remove_from_access_group",
            json: (object) $request_payload,
        );
    }

    public function revoke_access_to_all_entrances(
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->seam->request(
            "POST",
            "/acs/users/revoke_access_to_all_entrances",
            json: (object) $request_payload,
        );
    }

    public function suspend(
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->seam->request(
            "POST",
            "/acs/users/suspend",
            json: (object) $request_payload,
        );
    }

    public function unsuspend(
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->seam->request(
            "POST",
            "/acs/users/unsuspend",
            json: (object) $request_payload,
        );
    }

    public function update(
        mixed $access_schedule = null,
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?string $email = null,
        ?string $email_address = null,
        ?string $full_name = null,
        ?string $hid_acs_system_id = null,
        ?string $phone_number = null,
        ?string $user_identity_id = null,
    ): void {
        $request_payload = [];

        if ($access_schedule !== null) {
            $request_payload["access_schedule"] = $access_schedule;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($email !== null) {
            $request_payload["email"] = $email;
        }
        if ($email_address !== null) {
            $request_payload["email_address"] = $email_address;
        }
        if ($full_name !== null) {
            $request_payload["full_name"] = $full_name;
        }
        if ($hid_acs_system_id !== null) {
            $request_payload["hid_acs_system_id"] = $hid_acs_system_id;
        }
        if ($phone_number !== null) {
            $request_payload["phone_number"] = $phone_number;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->seam->request(
            "POST",
            "/acs/users/update",
            json: (object) $request_payload,
        );
    }
}

class ActionAttemptsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function get(string $action_attempt_id): ActionAttempt
    {
        $request_payload = [];

        if ($action_attempt_id !== null) {
            $request_payload["action_attempt_id"] = $action_attempt_id;
        }

        $res = $this->seam->request(
            "POST",
            "/action_attempts/get",
            json: (object) $request_payload,
        );

        return ActionAttempt::from_json($res->action_attempt);
    }

    public function list(array $action_attempt_ids): array
    {
        $request_payload = [];

        if ($action_attempt_ids !== null) {
            $request_payload["action_attempt_ids"] = $action_attempt_ids;
        }

        $res = $this->seam->request(
            "POST",
            "/action_attempts/list",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => ActionAttempt::from_json($r),
            $res->action_attempts,
        );
    }
    public function poll_until_ready(
        string $action_attempt_id,
        float $timeout = 20.0,
    ): ActionAttempt {
        $seam = $this->seam;
        $time_waiting = 0.0;
        $polling_interval = 0.4;
        $action_attempt = $seam->action_attempts->get($action_attempt_id);

        while ($action_attempt->status == "pending") {
            $action_attempt = $seam->action_attempts->get(
                $action_attempt->action_attempt_id,
            );
            if ($time_waiting > $timeout) {
                throw new ActionAttemptTimeoutError($action_attempt, $timeout);
            }
            $time_waiting += $polling_interval;
            usleep($polling_interval * 1000000);
        }

        if ($action_attempt->status == "error") {
            throw new ActionAttemptFailedError($action_attempt);
        }

        return $action_attempt;
    }
}

class ClientSessionsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function create(
        ?array $connect_webview_ids = null,
        ?array $connected_account_ids = null,
        ?string $customer_id = null,
        ?string $customer_key = null,
        ?string $expires_at = null,
        ?string $user_identifier_key = null,
        ?string $user_identity_id = null,
        ?array $user_identity_ids = null,
    ): ClientSession {
        $request_payload = [];

        if ($connect_webview_ids !== null) {
            $request_payload["connect_webview_ids"] = $connect_webview_ids;
        }
        if ($connected_account_ids !== null) {
            $request_payload["connected_account_ids"] = $connected_account_ids;
        }
        if ($customer_id !== null) {
            $request_payload["customer_id"] = $customer_id;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($expires_at !== null) {
            $request_payload["expires_at"] = $expires_at;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($user_identity_ids !== null) {
            $request_payload["user_identity_ids"] = $user_identity_ids;
        }

        $res = $this->seam->request(
            "POST",
            "/client_sessions/create",
            json: (object) $request_payload,
        );

        return ClientSession::from_json($res->client_session);
    }

    public function delete(string $client_session_id): void
    {
        $request_payload = [];

        if ($client_session_id !== null) {
            $request_payload["client_session_id"] = $client_session_id;
        }

        $this->seam->request(
            "POST",
            "/client_sessions/delete",
            json: (object) $request_payload,
        );
    }

    public function get(
        ?string $client_session_id = null,
        ?string $user_identifier_key = null,
    ): ClientSession {
        $request_payload = [];

        if ($client_session_id !== null) {
            $request_payload["client_session_id"] = $client_session_id;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/client_sessions/get",
            json: (object) $request_payload,
        );

        return ClientSession::from_json($res->client_session);
    }

    public function get_or_create(
        ?array $connect_webview_ids = null,
        ?array $connected_account_ids = null,
        ?string $expires_at = null,
        ?string $user_identifier_key = null,
        ?string $user_identity_id = null,
        ?array $user_identity_ids = null,
    ): ClientSession {
        $request_payload = [];

        if ($connect_webview_ids !== null) {
            $request_payload["connect_webview_ids"] = $connect_webview_ids;
        }
        if ($connected_account_ids !== null) {
            $request_payload["connected_account_ids"] = $connected_account_ids;
        }
        if ($expires_at !== null) {
            $request_payload["expires_at"] = $expires_at;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($user_identity_ids !== null) {
            $request_payload["user_identity_ids"] = $user_identity_ids;
        }

        $res = $this->seam->request(
            "POST",
            "/client_sessions/get_or_create",
            json: (object) $request_payload,
        );

        return ClientSession::from_json($res->client_session);
    }

    public function grant_access(
        ?string $client_session_id = null,
        ?array $connect_webview_ids = null,
        ?array $connected_account_ids = null,
        ?string $user_identifier_key = null,
        ?string $user_identity_id = null,
        ?array $user_identity_ids = null,
    ): void {
        $request_payload = [];

        if ($client_session_id !== null) {
            $request_payload["client_session_id"] = $client_session_id;
        }
        if ($connect_webview_ids !== null) {
            $request_payload["connect_webview_ids"] = $connect_webview_ids;
        }
        if ($connected_account_ids !== null) {
            $request_payload["connected_account_ids"] = $connected_account_ids;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($user_identity_ids !== null) {
            $request_payload["user_identity_ids"] = $user_identity_ids;
        }

        $this->seam->request(
            "POST",
            "/client_sessions/grant_access",
            json: (object) $request_payload,
        );
    }

    public function list(
        ?string $client_session_id = null,
        ?string $connect_webview_id = null,
        ?string $user_identifier_key = null,
        ?string $user_identity_id = null,
        ?bool $without_user_identifier_key = null,
    ): array {
        $request_payload = [];

        if ($client_session_id !== null) {
            $request_payload["client_session_id"] = $client_session_id;
        }
        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($without_user_identifier_key !== null) {
            $request_payload[
                "without_user_identifier_key"
            ] = $without_user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/client_sessions/list",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => ClientSession::from_json($r),
            $res->client_sessions,
        );
    }

    public function revoke(string $client_session_id): void
    {
        $request_payload = [];

        if ($client_session_id !== null) {
            $request_payload["client_session_id"] = $client_session_id;
        }

        $this->seam->request(
            "POST",
            "/client_sessions/revoke",
            json: (object) $request_payload,
        );
    }
}

class ConnectWebviewsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function create(
        ?array $accepted_capabilities = null,
        ?array $accepted_providers = null,
        ?bool $automatically_manage_new_devices = null,
        mixed $custom_metadata = null,
        ?string $custom_redirect_failure_url = null,
        ?string $custom_redirect_url = null,
        ?string $customer_key = null,
        ?string $device_selection_mode = null,
        ?array $excluded_providers = null,
        ?string $provider_category = null,
        ?bool $wait_for_device_creation = null,
    ): ConnectWebview {
        $request_payload = [];

        if ($accepted_capabilities !== null) {
            $request_payload["accepted_capabilities"] = $accepted_capabilities;
        }
        if ($accepted_providers !== null) {
            $request_payload["accepted_providers"] = $accepted_providers;
        }
        if ($automatically_manage_new_devices !== null) {
            $request_payload[
                "automatically_manage_new_devices"
            ] = $automatically_manage_new_devices;
        }
        if ($custom_metadata !== null) {
            $request_payload["custom_metadata"] = $custom_metadata;
        }
        if ($custom_redirect_failure_url !== null) {
            $request_payload[
                "custom_redirect_failure_url"
            ] = $custom_redirect_failure_url;
        }
        if ($custom_redirect_url !== null) {
            $request_payload["custom_redirect_url"] = $custom_redirect_url;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($device_selection_mode !== null) {
            $request_payload["device_selection_mode"] = $device_selection_mode;
        }
        if ($excluded_providers !== null) {
            $request_payload["excluded_providers"] = $excluded_providers;
        }
        if ($provider_category !== null) {
            $request_payload["provider_category"] = $provider_category;
        }
        if ($wait_for_device_creation !== null) {
            $request_payload[
                "wait_for_device_creation"
            ] = $wait_for_device_creation;
        }

        $res = $this->seam->request(
            "POST",
            "/connect_webviews/create",
            json: (object) $request_payload,
        );

        return ConnectWebview::from_json($res->connect_webview);
    }

    public function delete(string $connect_webview_id): void
    {
        $request_payload = [];

        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }

        $this->seam->request(
            "POST",
            "/connect_webviews/delete",
            json: (object) $request_payload,
        );
    }

    public function get(string $connect_webview_id): ConnectWebview
    {
        $request_payload = [];

        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }

        $res = $this->seam->request(
            "POST",
            "/connect_webviews/get",
            json: (object) $request_payload,
        );

        return ConnectWebview::from_json($res->connect_webview);
    }

    public function list(
        mixed $custom_metadata_has = null,
        ?string $customer_key = null,
        ?float $limit = null,
        ?string $page_cursor = null,
        ?string $user_identifier_key = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($custom_metadata_has !== null) {
            $request_payload["custom_metadata_has"] = $custom_metadata_has;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/connect_webviews/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => ConnectWebview::from_json($r),
            $res->connect_webviews,
        );
    }
}

class ConnectedAccountsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function delete(
        string $connected_account_id,
        ?bool $sync = null,
    ): void {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $this->seam->request(
            "POST",
            "/connected_accounts/delete",
            json: (object) $request_payload,
        );
    }

    public function get(
        ?string $connected_account_id = null,
        ?string $email = null,
    ): ConnectedAccount {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($email !== null) {
            $request_payload["email"] = $email;
        }

        $res = $this->seam->request(
            "POST",
            "/connected_accounts/get",
            json: (object) $request_payload,
        );

        return ConnectedAccount::from_json($res->connected_account);
    }

    public function list(
        mixed $custom_metadata_has = null,
        ?string $customer_key = null,
        mixed $limit = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?string $user_identifier_key = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($custom_metadata_has !== null) {
            $request_payload["custom_metadata_has"] = $custom_metadata_has;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/connected_accounts/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => ConnectedAccount::from_json($r),
            $res->connected_accounts,
        );
    }

    public function sync(string $connected_account_id): void
    {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }

        $this->seam->request(
            "POST",
            "/connected_accounts/sync",
            json: (object) $request_payload,
        );
    }

    public function update(
        string $connected_account_id,
        ?array $accepted_capabilities = null,
        ?bool $automatically_manage_new_devices = null,
        mixed $custom_metadata = null,
    ): void {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($accepted_capabilities !== null) {
            $request_payload["accepted_capabilities"] = $accepted_capabilities;
        }
        if ($automatically_manage_new_devices !== null) {
            $request_payload[
                "automatically_manage_new_devices"
            ] = $automatically_manage_new_devices;
        }
        if ($custom_metadata !== null) {
            $request_payload["custom_metadata"] = $custom_metadata;
        }

        $this->seam->request(
            "POST",
            "/connected_accounts/update",
            json: (object) $request_payload,
        );
    }
}

class CustomersClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function create_portal(
        mixed $features = null,
        ?bool $is_embedded = null,
        mixed $landing_page = null,
        mixed $customer_data = null,
    ): MagicLink {
        $request_payload = [];

        if ($features !== null) {
            $request_payload["features"] = $features;
        }
        if ($is_embedded !== null) {
            $request_payload["is_embedded"] = $is_embedded;
        }
        if ($landing_page !== null) {
            $request_payload["landing_page"] = $landing_page;
        }
        if ($customer_data !== null) {
            $request_payload["customer_data"] = $customer_data;
        }

        $res = $this->seam->request(
            "POST",
            "/customers/create_portal",
            json: (object) $request_payload,
        );

        return MagicLink::from_json($res->magic_link);
    }

    public function delete_data(
        ?array $access_grant_keys = null,
        ?array $booking_keys = null,
        ?array $building_keys = null,
        ?array $common_area_keys = null,
        ?array $customer_keys = null,
        ?array $facility_keys = null,
        ?array $guest_keys = null,
        ?array $listing_keys = null,
        ?array $property_keys = null,
        ?array $property_listing_keys = null,
        ?array $reservation_keys = null,
        ?array $resident_keys = null,
        ?array $room_keys = null,
        ?array $space_keys = null,
        ?array $tenant_keys = null,
        ?array $unit_keys = null,
        ?array $user_identity_keys = null,
        ?array $user_keys = null,
    ): void {
        $request_payload = [];

        if ($access_grant_keys !== null) {
            $request_payload["access_grant_keys"] = $access_grant_keys;
        }
        if ($booking_keys !== null) {
            $request_payload["booking_keys"] = $booking_keys;
        }
        if ($building_keys !== null) {
            $request_payload["building_keys"] = $building_keys;
        }
        if ($common_area_keys !== null) {
            $request_payload["common_area_keys"] = $common_area_keys;
        }
        if ($customer_keys !== null) {
            $request_payload["customer_keys"] = $customer_keys;
        }
        if ($facility_keys !== null) {
            $request_payload["facility_keys"] = $facility_keys;
        }
        if ($guest_keys !== null) {
            $request_payload["guest_keys"] = $guest_keys;
        }
        if ($listing_keys !== null) {
            $request_payload["listing_keys"] = $listing_keys;
        }
        if ($property_keys !== null) {
            $request_payload["property_keys"] = $property_keys;
        }
        if ($property_listing_keys !== null) {
            $request_payload["property_listing_keys"] = $property_listing_keys;
        }
        if ($reservation_keys !== null) {
            $request_payload["reservation_keys"] = $reservation_keys;
        }
        if ($resident_keys !== null) {
            $request_payload["resident_keys"] = $resident_keys;
        }
        if ($room_keys !== null) {
            $request_payload["room_keys"] = $room_keys;
        }
        if ($space_keys !== null) {
            $request_payload["space_keys"] = $space_keys;
        }
        if ($tenant_keys !== null) {
            $request_payload["tenant_keys"] = $tenant_keys;
        }
        if ($unit_keys !== null) {
            $request_payload["unit_keys"] = $unit_keys;
        }
        if ($user_identity_keys !== null) {
            $request_payload["user_identity_keys"] = $user_identity_keys;
        }
        if ($user_keys !== null) {
            $request_payload["user_keys"] = $user_keys;
        }

        $this->seam->request(
            "POST",
            "/customers/delete_data",
            json: (object) $request_payload,
        );
    }

    public function push_data(
        string $customer_key,
        ?array $access_grants = null,
        ?array $bookings = null,
        ?array $buildings = null,
        ?array $common_areas = null,
        ?array $facilities = null,
        ?array $guests = null,
        ?array $listings = null,
        ?array $properties = null,
        ?array $property_listings = null,
        ?array $reservations = null,
        ?array $residents = null,
        ?array $rooms = null,
        ?array $spaces = null,
        ?array $tenants = null,
        ?array $units = null,
        ?array $user_identities = null,
        ?array $users = null,
    ): void {
        $request_payload = [];

        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($access_grants !== null) {
            $request_payload["access_grants"] = $access_grants;
        }
        if ($bookings !== null) {
            $request_payload["bookings"] = $bookings;
        }
        if ($buildings !== null) {
            $request_payload["buildings"] = $buildings;
        }
        if ($common_areas !== null) {
            $request_payload["common_areas"] = $common_areas;
        }
        if ($facilities !== null) {
            $request_payload["facilities"] = $facilities;
        }
        if ($guests !== null) {
            $request_payload["guests"] = $guests;
        }
        if ($listings !== null) {
            $request_payload["listings"] = $listings;
        }
        if ($properties !== null) {
            $request_payload["properties"] = $properties;
        }
        if ($property_listings !== null) {
            $request_payload["property_listings"] = $property_listings;
        }
        if ($reservations !== null) {
            $request_payload["reservations"] = $reservations;
        }
        if ($residents !== null) {
            $request_payload["residents"] = $residents;
        }
        if ($rooms !== null) {
            $request_payload["rooms"] = $rooms;
        }
        if ($spaces !== null) {
            $request_payload["spaces"] = $spaces;
        }
        if ($tenants !== null) {
            $request_payload["tenants"] = $tenants;
        }
        if ($units !== null) {
            $request_payload["units"] = $units;
        }
        if ($user_identities !== null) {
            $request_payload["user_identities"] = $user_identities;
        }
        if ($users !== null) {
            $request_payload["users"] = $users;
        }

        $this->seam->request(
            "POST",
            "/customers/push_data",
            json: (object) $request_payload,
        );
    }
}

class DevicesClient
{
    private SeamClient $seam;
    public DevicesSimulateClient $simulate;
    public DevicesUnmanagedClient $unmanaged;
    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
        $this->simulate = new DevicesSimulateClient($seam);
        $this->unmanaged = new DevicesUnmanagedClient($seam);
    }

    public function get(?string $device_id = null, ?string $name = null): Device
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }

        $res = $this->seam->request(
            "POST",
            "/devices/get",
            json: (object) $request_payload,
        );

        return Device::from_json($res->device);
    }

    public function list(
        ?string $connect_webview_id = null,
        ?string $connected_account_id = null,
        ?array $connected_account_ids = null,
        ?string $created_before = null,
        mixed $custom_metadata_has = null,
        ?string $customer_key = null,
        ?array $device_ids = null,
        ?string $device_type = null,
        ?array $device_types = null,
        ?array $exclude_if = null,
        ?array $include_if = null,
        ?float $limit = null,
        ?string $manufacturer = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?string $space_id = null,
        ?string $unstable_location_id = null,
        ?string $user_identifier_key = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }
        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($connected_account_ids !== null) {
            $request_payload["connected_account_ids"] = $connected_account_ids;
        }
        if ($created_before !== null) {
            $request_payload["created_before"] = $created_before;
        }
        if ($custom_metadata_has !== null) {
            $request_payload["custom_metadata_has"] = $custom_metadata_has;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($device_type !== null) {
            $request_payload["device_type"] = $device_type;
        }
        if ($device_types !== null) {
            $request_payload["device_types"] = $device_types;
        }
        if ($exclude_if !== null) {
            $request_payload["exclude_if"] = $exclude_if;
        }
        if ($include_if !== null) {
            $request_payload["include_if"] = $include_if;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($manufacturer !== null) {
            $request_payload["manufacturer"] = $manufacturer;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }
        if ($unstable_location_id !== null) {
            $request_payload["unstable_location_id"] = $unstable_location_id;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/devices/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(fn($r) => Device::from_json($r), $res->devices);
    }

    public function list_device_providers(
        ?string $provider_category = null,
    ): array {
        $request_payload = [];

        if ($provider_category !== null) {
            $request_payload["provider_category"] = $provider_category;
        }

        $res = $this->seam->request(
            "POST",
            "/devices/list_device_providers",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => DeviceProvider::from_json($r),
            $res->device_providers,
        );
    }

    public function update(
        string $device_id,
        mixed $custom_metadata = null,
        ?bool $is_managed = null,
        ?string $name = null,
        mixed $properties = null,
    ): void {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($custom_metadata !== null) {
            $request_payload["custom_metadata"] = $custom_metadata;
        }
        if ($is_managed !== null) {
            $request_payload["is_managed"] = $is_managed;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($properties !== null) {
            $request_payload["properties"] = $properties;
        }

        $this->seam->request(
            "POST",
            "/devices/update",
            json: (object) $request_payload,
        );
    }
}

class DevicesSimulateClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function connect(string $device_id): void
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->request(
            "POST",
            "/devices/simulate/connect",
            json: (object) $request_payload,
        );
    }

    public function connect_to_hub(string $device_id): void
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->request(
            "POST",
            "/devices/simulate/connect_to_hub",
            json: (object) $request_payload,
        );
    }

    public function disconnect(string $device_id): void
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->request(
            "POST",
            "/devices/simulate/disconnect",
            json: (object) $request_payload,
        );
    }

    public function disconnect_from_hub(string $device_id): void
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->request(
            "POST",
            "/devices/simulate/disconnect_from_hub",
            json: (object) $request_payload,
        );
    }

    public function paid_subscription(string $device_id, bool $is_expired): void
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($is_expired !== null) {
            $request_payload["is_expired"] = $is_expired;
        }

        $this->seam->request(
            "POST",
            "/devices/simulate/paid_subscription",
            json: (object) $request_payload,
        );
    }

    public function remove(string $device_id): void
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->request(
            "POST",
            "/devices/simulate/remove",
            json: (object) $request_payload,
        );
    }
}

class DevicesUnmanagedClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function get(
        ?string $device_id = null,
        ?string $name = null,
    ): UnmanagedDevice {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }

        $res = $this->seam->request(
            "POST",
            "/devices/unmanaged/get",
            json: (object) $request_payload,
        );

        return UnmanagedDevice::from_json($res->device);
    }

    public function list(
        ?string $connect_webview_id = null,
        ?string $connected_account_id = null,
        ?array $connected_account_ids = null,
        ?string $created_before = null,
        mixed $custom_metadata_has = null,
        ?string $customer_key = null,
        ?array $device_ids = null,
        ?string $device_type = null,
        ?array $device_types = null,
        ?array $exclude_if = null,
        ?array $include_if = null,
        ?float $limit = null,
        ?string $manufacturer = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?string $space_id = null,
        ?string $unstable_location_id = null,
        ?string $user_identifier_key = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }
        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($connected_account_ids !== null) {
            $request_payload["connected_account_ids"] = $connected_account_ids;
        }
        if ($created_before !== null) {
            $request_payload["created_before"] = $created_before;
        }
        if ($custom_metadata_has !== null) {
            $request_payload["custom_metadata_has"] = $custom_metadata_has;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($device_type !== null) {
            $request_payload["device_type"] = $device_type;
        }
        if ($device_types !== null) {
            $request_payload["device_types"] = $device_types;
        }
        if ($exclude_if !== null) {
            $request_payload["exclude_if"] = $exclude_if;
        }
        if ($include_if !== null) {
            $request_payload["include_if"] = $include_if;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($manufacturer !== null) {
            $request_payload["manufacturer"] = $manufacturer;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }
        if ($unstable_location_id !== null) {
            $request_payload["unstable_location_id"] = $unstable_location_id;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/devices/unmanaged/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => UnmanagedDevice::from_json($r),
            $res->devices,
        );
    }

    public function update(
        string $device_id,
        mixed $custom_metadata = null,
        ?bool $is_managed = null,
    ): void {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($custom_metadata !== null) {
            $request_payload["custom_metadata"] = $custom_metadata;
        }
        if ($is_managed !== null) {
            $request_payload["is_managed"] = $is_managed;
        }

        $this->seam->request(
            "POST",
            "/devices/unmanaged/update",
            json: (object) $request_payload,
        );
    }
}

class EventsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function get(
        ?string $device_id = null,
        ?string $event_id = null,
        ?string $event_type = null,
    ): Event {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($event_id !== null) {
            $request_payload["event_id"] = $event_id;
        }
        if ($event_type !== null) {
            $request_payload["event_type"] = $event_type;
        }

        $res = $this->seam->request(
            "POST",
            "/events/get",
            json: (object) $request_payload,
        );

        return Event::from_json($res->event);
    }

    public function list(
        ?string $access_code_id = null,
        ?array $access_code_ids = null,
        ?string $acs_system_id = null,
        ?array $acs_system_ids = null,
        ?array $between = null,
        ?string $connect_webview_id = null,
        ?string $connected_account_id = null,
        ?string $customer_key = null,
        ?string $device_id = null,
        ?array $device_ids = null,
        ?array $event_ids = null,
        ?string $event_type = null,
        ?array $event_types = null,
        ?float $limit = null,
        ?string $since = null,
        ?float $unstable_offset = null,
    ): array {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($access_code_ids !== null) {
            $request_payload["access_code_ids"] = $access_code_ids;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_system_ids !== null) {
            $request_payload["acs_system_ids"] = $acs_system_ids;
        }
        if ($between !== null) {
            $request_payload["between"] = $between;
        }
        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }
        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($event_ids !== null) {
            $request_payload["event_ids"] = $event_ids;
        }
        if ($event_type !== null) {
            $request_payload["event_type"] = $event_type;
        }
        if ($event_types !== null) {
            $request_payload["event_types"] = $event_types;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($since !== null) {
            $request_payload["since"] = $since;
        }
        if ($unstable_offset !== null) {
            $request_payload["unstable_offset"] = $unstable_offset;
        }

        $res = $this->seam->request(
            "POST",
            "/events/list",
            json: (object) $request_payload,
        );

        return array_map(fn($r) => Event::from_json($r), $res->events);
    }
}

class InstantKeysClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function delete(string $instant_key_id): void
    {
        $request_payload = [];

        if ($instant_key_id !== null) {
            $request_payload["instant_key_id"] = $instant_key_id;
        }

        $this->seam->request(
            "POST",
            "/instant_keys/delete",
            json: (object) $request_payload,
        );
    }

    public function get(
        ?string $instant_key_id = null,
        ?string $instant_key_url = null,
    ): InstantKey {
        $request_payload = [];

        if ($instant_key_id !== null) {
            $request_payload["instant_key_id"] = $instant_key_id;
        }
        if ($instant_key_url !== null) {
            $request_payload["instant_key_url"] = $instant_key_url;
        }

        $res = $this->seam->request(
            "POST",
            "/instant_keys/get",
            json: (object) $request_payload,
        );

        return InstantKey::from_json($res->instant_key);
    }

    public function list(?string $user_identity_id = null): array
    {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->request(
            "POST",
            "/instant_keys/list",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => InstantKey::from_json($r),
            $res->instant_keys,
        );
    }
}

class LocksClient
{
    private SeamClient $seam;
    public LocksSimulateClient $simulate;
    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
        $this->simulate = new LocksSimulateClient($seam);
    }

    public function get(?string $device_id = null, ?string $name = null): Device
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }

        $res = $this->seam->request(
            "POST",
            "/locks/get",
            json: (object) $request_payload,
        );

        return Device::from_json($res->device);
    }

    public function list(
        ?string $connect_webview_id = null,
        ?string $connected_account_id = null,
        ?array $connected_account_ids = null,
        ?string $created_before = null,
        mixed $custom_metadata_has = null,
        ?string $customer_key = null,
        ?array $device_ids = null,
        ?string $device_type = null,
        ?array $device_types = null,
        ?array $exclude_if = null,
        ?array $include_if = null,
        ?float $limit = null,
        ?string $manufacturer = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?string $space_id = null,
        ?string $unstable_location_id = null,
        ?string $user_identifier_key = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }
        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($connected_account_ids !== null) {
            $request_payload["connected_account_ids"] = $connected_account_ids;
        }
        if ($created_before !== null) {
            $request_payload["created_before"] = $created_before;
        }
        if ($custom_metadata_has !== null) {
            $request_payload["custom_metadata_has"] = $custom_metadata_has;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($device_type !== null) {
            $request_payload["device_type"] = $device_type;
        }
        if ($device_types !== null) {
            $request_payload["device_types"] = $device_types;
        }
        if ($exclude_if !== null) {
            $request_payload["exclude_if"] = $exclude_if;
        }
        if ($include_if !== null) {
            $request_payload["include_if"] = $include_if;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($manufacturer !== null) {
            $request_payload["manufacturer"] = $manufacturer;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }
        if ($unstable_location_id !== null) {
            $request_payload["unstable_location_id"] = $unstable_location_id;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/locks/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(fn($r) => Device::from_json($r), $res->devices);
    }

    public function lock_door(
        string $device_id,
        ?bool $sync = null,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $res = $this->seam->request(
            "POST",
            "/locks/lock_door",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }

    public function unlock_door(
        string $device_id,
        ?bool $sync = null,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $res = $this->seam->request(
            "POST",
            "/locks/unlock_door",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }
}

class LocksSimulateClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function keypad_code_entry(
        string $code,
        string $device_id,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($code !== null) {
            $request_payload["code"] = $code;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $res = $this->seam->request(
            "POST",
            "/locks/simulate/keypad_code_entry",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }

    public function manual_lock_via_keypad(
        string $device_id,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $res = $this->seam->request(
            "POST",
            "/locks/simulate/manual_lock_via_keypad",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }
}

class NoiseSensorsClient
{
    private SeamClient $seam;
    public NoiseSensorsNoiseThresholdsClient $noise_thresholds;
    public NoiseSensorsSimulateClient $simulate;
    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
        $this->noise_thresholds = new NoiseSensorsNoiseThresholdsClient($seam);
        $this->simulate = new NoiseSensorsSimulateClient($seam);
    }

    public function list(
        ?string $connect_webview_id = null,
        ?string $connected_account_id = null,
        ?array $connected_account_ids = null,
        ?string $created_before = null,
        mixed $custom_metadata_has = null,
        ?string $customer_key = null,
        ?array $device_ids = null,
        ?string $device_type = null,
        ?array $device_types = null,
        ?array $exclude_if = null,
        ?array $include_if = null,
        ?float $limit = null,
        ?string $manufacturer = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?string $space_id = null,
        ?string $unstable_location_id = null,
        ?string $user_identifier_key = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }
        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($connected_account_ids !== null) {
            $request_payload["connected_account_ids"] = $connected_account_ids;
        }
        if ($created_before !== null) {
            $request_payload["created_before"] = $created_before;
        }
        if ($custom_metadata_has !== null) {
            $request_payload["custom_metadata_has"] = $custom_metadata_has;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($device_type !== null) {
            $request_payload["device_type"] = $device_type;
        }
        if ($device_types !== null) {
            $request_payload["device_types"] = $device_types;
        }
        if ($exclude_if !== null) {
            $request_payload["exclude_if"] = $exclude_if;
        }
        if ($include_if !== null) {
            $request_payload["include_if"] = $include_if;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($manufacturer !== null) {
            $request_payload["manufacturer"] = $manufacturer;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }
        if ($unstable_location_id !== null) {
            $request_payload["unstable_location_id"] = $unstable_location_id;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/noise_sensors/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(fn($r) => Device::from_json($r), $res->devices);
    }
}

class NoiseSensorsNoiseThresholdsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function create(
        string $device_id,
        string $ends_daily_at,
        string $starts_daily_at,
        ?string $name = null,
        ?float $noise_threshold_decibels = null,
        ?float $noise_threshold_nrs = null,
        ?bool $sync = null,
    ): NoiseThreshold {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($ends_daily_at !== null) {
            $request_payload["ends_daily_at"] = $ends_daily_at;
        }
        if ($starts_daily_at !== null) {
            $request_payload["starts_daily_at"] = $starts_daily_at;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($noise_threshold_decibels !== null) {
            $request_payload[
                "noise_threshold_decibels"
            ] = $noise_threshold_decibels;
        }
        if ($noise_threshold_nrs !== null) {
            $request_payload["noise_threshold_nrs"] = $noise_threshold_nrs;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $res = $this->seam->request(
            "POST",
            "/noise_sensors/noise_thresholds/create",
            json: (object) $request_payload,
        );

        return NoiseThreshold::from_json($res->noise_threshold);
    }

    public function delete(
        string $device_id,
        string $noise_threshold_id,
        ?bool $sync = null,
    ): void {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($noise_threshold_id !== null) {
            $request_payload["noise_threshold_id"] = $noise_threshold_id;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $this->seam->request(
            "POST",
            "/noise_sensors/noise_thresholds/delete",
            json: (object) $request_payload,
        );
    }

    public function get(string $noise_threshold_id): NoiseThreshold
    {
        $request_payload = [];

        if ($noise_threshold_id !== null) {
            $request_payload["noise_threshold_id"] = $noise_threshold_id;
        }

        $res = $this->seam->request(
            "POST",
            "/noise_sensors/noise_thresholds/get",
            json: (object) $request_payload,
        );

        return NoiseThreshold::from_json($res->noise_threshold);
    }

    public function list(string $device_id, ?bool $is_programmed = null): array
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($is_programmed !== null) {
            $request_payload["is_programmed"] = $is_programmed;
        }

        $res = $this->seam->request(
            "POST",
            "/noise_sensors/noise_thresholds/list",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => NoiseThreshold::from_json($r),
            $res->noise_thresholds,
        );
    }

    public function update(
        string $device_id,
        string $noise_threshold_id,
        ?string $ends_daily_at = null,
        ?string $name = null,
        ?float $noise_threshold_decibels = null,
        ?float $noise_threshold_nrs = null,
        ?string $starts_daily_at = null,
        ?bool $sync = null,
    ): void {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($noise_threshold_id !== null) {
            $request_payload["noise_threshold_id"] = $noise_threshold_id;
        }
        if ($ends_daily_at !== null) {
            $request_payload["ends_daily_at"] = $ends_daily_at;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($noise_threshold_decibels !== null) {
            $request_payload[
                "noise_threshold_decibels"
            ] = $noise_threshold_decibels;
        }
        if ($noise_threshold_nrs !== null) {
            $request_payload["noise_threshold_nrs"] = $noise_threshold_nrs;
        }
        if ($starts_daily_at !== null) {
            $request_payload["starts_daily_at"] = $starts_daily_at;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $this->seam->request(
            "POST",
            "/noise_sensors/noise_thresholds/update",
            json: (object) $request_payload,
        );
    }
}

class NoiseSensorsSimulateClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function trigger_noise_threshold(string $device_id): void
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->request(
            "POST",
            "/noise_sensors/simulate/trigger_noise_threshold",
            json: (object) $request_payload,
        );
    }
}

class PhonesClient
{
    private SeamClient $seam;
    public PhonesSimulateClient $simulate;
    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
        $this->simulate = new PhonesSimulateClient($seam);
    }

    public function deactivate(string $device_id): void
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->request(
            "POST",
            "/phones/deactivate",
            json: (object) $request_payload,
        );
    }

    public function get(string $device_id): Phone
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $res = $this->seam->request(
            "POST",
            "/phones/get",
            json: (object) $request_payload,
        );

        return Phone::from_json($res->phone);
    }

    public function list(
        ?string $acs_credential_id = null,
        ?string $owner_user_identity_id = null,
    ): array {
        $request_payload = [];

        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }
        if ($owner_user_identity_id !== null) {
            $request_payload[
                "owner_user_identity_id"
            ] = $owner_user_identity_id;
        }

        $res = $this->seam->request(
            "POST",
            "/phones/list",
            json: (object) $request_payload,
        );

        return array_map(fn($r) => Phone::from_json($r), $res->phones);
    }
}

class PhonesSimulateClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function create_sandbox_phone(
        string $user_identity_id,
        mixed $assa_abloy_metadata = null,
        ?string $custom_sdk_installation_id = null,
        mixed $phone_metadata = null,
    ): Phone {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($assa_abloy_metadata !== null) {
            $request_payload["assa_abloy_metadata"] = $assa_abloy_metadata;
        }
        if ($custom_sdk_installation_id !== null) {
            $request_payload[
                "custom_sdk_installation_id"
            ] = $custom_sdk_installation_id;
        }
        if ($phone_metadata !== null) {
            $request_payload["phone_metadata"] = $phone_metadata;
        }

        $res = $this->seam->request(
            "POST",
            "/phones/simulate/create_sandbox_phone",
            json: (object) $request_payload,
        );

        return Phone::from_json($res->phone);
    }
}

class SpacesClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function add_acs_entrances(
        array $acs_entrance_ids,
        string $space_id,
    ): void {
        $request_payload = [];

        if ($acs_entrance_ids !== null) {
            $request_payload["acs_entrance_ids"] = $acs_entrance_ids;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }

        $this->seam->request(
            "POST",
            "/spaces/add_acs_entrances",
            json: (object) $request_payload,
        );
    }

    public function add_devices(array $device_ids, string $space_id): void
    {
        $request_payload = [];

        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }

        $this->seam->request(
            "POST",
            "/spaces/add_devices",
            json: (object) $request_payload,
        );
    }

    public function create(
        string $name,
        ?array $acs_entrance_ids = null,
        ?array $device_ids = null,
        ?string $space_key = null,
    ): Space {
        $request_payload = [];

        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($acs_entrance_ids !== null) {
            $request_payload["acs_entrance_ids"] = $acs_entrance_ids;
        }
        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($space_key !== null) {
            $request_payload["space_key"] = $space_key;
        }

        $res = $this->seam->request(
            "POST",
            "/spaces/create",
            json: (object) $request_payload,
        );

        return Space::from_json($res->space);
    }

    public function delete(string $space_id): void
    {
        $request_payload = [];

        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }

        $this->seam->request(
            "POST",
            "/spaces/delete",
            json: (object) $request_payload,
        );
    }

    public function get(
        ?string $space_id = null,
        ?string $space_key = null,
    ): Space {
        $request_payload = [];

        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }
        if ($space_key !== null) {
            $request_payload["space_key"] = $space_key;
        }

        $res = $this->seam->request(
            "POST",
            "/spaces/get",
            json: (object) $request_payload,
        );

        return Space::from_json($res->space);
    }

    public function get_related(
        ?array $exclude = null,
        ?array $include = null,
        ?array $space_ids = null,
        ?array $space_keys = null,
    ): void {
        $request_payload = [];

        if ($exclude !== null) {
            $request_payload["exclude"] = $exclude;
        }
        if ($include !== null) {
            $request_payload["include"] = $include;
        }
        if ($space_ids !== null) {
            $request_payload["space_ids"] = $space_ids;
        }
        if ($space_keys !== null) {
            $request_payload["space_keys"] = $space_keys;
        }

        $this->seam->request(
            "POST",
            "/spaces/get_related",
            json: (object) $request_payload,
        );
    }

    public function list(
        ?string $connected_account_id = null,
        ?string $customer_key = null,
        ?string $search = null,
        ?string $space_key = null,
    ): array {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
        }
        if ($space_key !== null) {
            $request_payload["space_key"] = $space_key;
        }

        $res = $this->seam->request(
            "POST",
            "/spaces/list",
            json: (object) $request_payload,
        );

        return array_map(fn($r) => Space::from_json($r), $res->spaces);
    }

    public function remove_acs_entrances(
        array $acs_entrance_ids,
        string $space_id,
    ): void {
        $request_payload = [];

        if ($acs_entrance_ids !== null) {
            $request_payload["acs_entrance_ids"] = $acs_entrance_ids;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }

        $this->seam->request(
            "POST",
            "/spaces/remove_acs_entrances",
            json: (object) $request_payload,
        );
    }

    public function remove_devices(array $device_ids, string $space_id): void
    {
        $request_payload = [];

        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }

        $this->seam->request(
            "POST",
            "/spaces/remove_devices",
            json: (object) $request_payload,
        );
    }

    public function update(
        ?array $acs_entrance_ids = null,
        ?array $device_ids = null,
        ?string $name = null,
        ?string $space_id = null,
        ?string $space_key = null,
    ): Space {
        $request_payload = [];

        if ($acs_entrance_ids !== null) {
            $request_payload["acs_entrance_ids"] = $acs_entrance_ids;
        }
        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }
        if ($space_key !== null) {
            $request_payload["space_key"] = $space_key;
        }

        $res = $this->seam->request(
            "POST",
            "/spaces/update",
            json: (object) $request_payload,
        );

        return Space::from_json($res->space);
    }
}

class ThermostatsClient
{
    private SeamClient $seam;
    public ThermostatsDailyProgramsClient $daily_programs;
    public ThermostatsSchedulesClient $schedules;
    public ThermostatsSimulateClient $simulate;
    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
        $this->daily_programs = new ThermostatsDailyProgramsClient($seam);
        $this->schedules = new ThermostatsSchedulesClient($seam);
        $this->simulate = new ThermostatsSimulateClient($seam);
    }

    public function activate_climate_preset(
        string $climate_preset_key,
        string $device_id,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($climate_preset_key !== null) {
            $request_payload["climate_preset_key"] = $climate_preset_key;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/activate_climate_preset",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }

    public function cool(
        string $device_id,
        ?float $cooling_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        ?bool $sync = null,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($cooling_set_point_celsius !== null) {
            $request_payload[
                "cooling_set_point_celsius"
            ] = $cooling_set_point_celsius;
        }
        if ($cooling_set_point_fahrenheit !== null) {
            $request_payload[
                "cooling_set_point_fahrenheit"
            ] = $cooling_set_point_fahrenheit;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/cool",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }

    public function create_climate_preset(
        string $climate_preset_key,
        string $device_id,
        ?string $climate_preset_mode = null,
        ?float $cooling_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        mixed $ecobee_metadata = null,
        ?string $fan_mode_setting = null,
        ?float $heating_set_point_celsius = null,
        ?float $heating_set_point_fahrenheit = null,
        ?string $hvac_mode_setting = null,
        ?bool $manual_override_allowed = null,
        ?string $name = null,
    ): void {
        $request_payload = [];

        if ($climate_preset_key !== null) {
            $request_payload["climate_preset_key"] = $climate_preset_key;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($climate_preset_mode !== null) {
            $request_payload["climate_preset_mode"] = $climate_preset_mode;
        }
        if ($cooling_set_point_celsius !== null) {
            $request_payload[
                "cooling_set_point_celsius"
            ] = $cooling_set_point_celsius;
        }
        if ($cooling_set_point_fahrenheit !== null) {
            $request_payload[
                "cooling_set_point_fahrenheit"
            ] = $cooling_set_point_fahrenheit;
        }
        if ($ecobee_metadata !== null) {
            $request_payload["ecobee_metadata"] = $ecobee_metadata;
        }
        if ($fan_mode_setting !== null) {
            $request_payload["fan_mode_setting"] = $fan_mode_setting;
        }
        if ($heating_set_point_celsius !== null) {
            $request_payload[
                "heating_set_point_celsius"
            ] = $heating_set_point_celsius;
        }
        if ($heating_set_point_fahrenheit !== null) {
            $request_payload[
                "heating_set_point_fahrenheit"
            ] = $heating_set_point_fahrenheit;
        }
        if ($hvac_mode_setting !== null) {
            $request_payload["hvac_mode_setting"] = $hvac_mode_setting;
        }
        if ($manual_override_allowed !== null) {
            $request_payload[
                "manual_override_allowed"
            ] = $manual_override_allowed;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }

        $this->seam->request(
            "POST",
            "/thermostats/create_climate_preset",
            json: (object) $request_payload,
        );
    }

    public function delete_climate_preset(
        string $climate_preset_key,
        string $device_id,
    ): void {
        $request_payload = [];

        if ($climate_preset_key !== null) {
            $request_payload["climate_preset_key"] = $climate_preset_key;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->request(
            "POST",
            "/thermostats/delete_climate_preset",
            json: (object) $request_payload,
        );
    }

    public function heat(
        string $device_id,
        ?float $heating_set_point_celsius = null,
        ?float $heating_set_point_fahrenheit = null,
        ?bool $sync = null,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($heating_set_point_celsius !== null) {
            $request_payload[
                "heating_set_point_celsius"
            ] = $heating_set_point_celsius;
        }
        if ($heating_set_point_fahrenheit !== null) {
            $request_payload[
                "heating_set_point_fahrenheit"
            ] = $heating_set_point_fahrenheit;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/heat",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }

    public function heat_cool(
        string $device_id,
        ?float $cooling_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        ?float $heating_set_point_celsius = null,
        ?float $heating_set_point_fahrenheit = null,
        ?bool $sync = null,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($cooling_set_point_celsius !== null) {
            $request_payload[
                "cooling_set_point_celsius"
            ] = $cooling_set_point_celsius;
        }
        if ($cooling_set_point_fahrenheit !== null) {
            $request_payload[
                "cooling_set_point_fahrenheit"
            ] = $cooling_set_point_fahrenheit;
        }
        if ($heating_set_point_celsius !== null) {
            $request_payload[
                "heating_set_point_celsius"
            ] = $heating_set_point_celsius;
        }
        if ($heating_set_point_fahrenheit !== null) {
            $request_payload[
                "heating_set_point_fahrenheit"
            ] = $heating_set_point_fahrenheit;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/heat_cool",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }

    public function list(
        ?string $connect_webview_id = null,
        ?string $connected_account_id = null,
        ?array $connected_account_ids = null,
        ?string $created_before = null,
        mixed $custom_metadata_has = null,
        ?string $customer_key = null,
        ?array $device_ids = null,
        ?string $device_type = null,
        ?array $device_types = null,
        ?array $exclude_if = null,
        ?array $include_if = null,
        ?float $limit = null,
        ?string $manufacturer = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?string $space_id = null,
        ?string $unstable_location_id = null,
        ?string $user_identifier_key = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }
        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($connected_account_ids !== null) {
            $request_payload["connected_account_ids"] = $connected_account_ids;
        }
        if ($created_before !== null) {
            $request_payload["created_before"] = $created_before;
        }
        if ($custom_metadata_has !== null) {
            $request_payload["custom_metadata_has"] = $custom_metadata_has;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
        }
        if ($device_type !== null) {
            $request_payload["device_type"] = $device_type;
        }
        if ($device_types !== null) {
            $request_payload["device_types"] = $device_types;
        }
        if ($exclude_if !== null) {
            $request_payload["exclude_if"] = $exclude_if;
        }
        if ($include_if !== null) {
            $request_payload["include_if"] = $include_if;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($manufacturer !== null) {
            $request_payload["manufacturer"] = $manufacturer;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }
        if ($unstable_location_id !== null) {
            $request_payload["unstable_location_id"] = $unstable_location_id;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(fn($r) => Device::from_json($r), $res->devices);
    }

    public function off(
        string $device_id,
        ?bool $sync = null,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/off",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }

    public function set_fallback_climate_preset(
        string $climate_preset_key,
        string $device_id,
    ): void {
        $request_payload = [];

        if ($climate_preset_key !== null) {
            $request_payload["climate_preset_key"] = $climate_preset_key;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->request(
            "POST",
            "/thermostats/set_fallback_climate_preset",
            json: (object) $request_payload,
        );
    }

    public function set_fan_mode(
        string $device_id,
        ?string $fan_mode = null,
        ?string $fan_mode_setting = null,
        ?bool $sync = null,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($fan_mode !== null) {
            $request_payload["fan_mode"] = $fan_mode;
        }
        if ($fan_mode_setting !== null) {
            $request_payload["fan_mode_setting"] = $fan_mode_setting;
        }
        if ($sync !== null) {
            $request_payload["sync"] = $sync;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/set_fan_mode",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }

    public function set_hvac_mode(
        string $device_id,
        string $hvac_mode_setting,
        ?float $cooling_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        ?float $heating_set_point_celsius = null,
        ?float $heating_set_point_fahrenheit = null,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($hvac_mode_setting !== null) {
            $request_payload["hvac_mode_setting"] = $hvac_mode_setting;
        }
        if ($cooling_set_point_celsius !== null) {
            $request_payload[
                "cooling_set_point_celsius"
            ] = $cooling_set_point_celsius;
        }
        if ($cooling_set_point_fahrenheit !== null) {
            $request_payload[
                "cooling_set_point_fahrenheit"
            ] = $cooling_set_point_fahrenheit;
        }
        if ($heating_set_point_celsius !== null) {
            $request_payload[
                "heating_set_point_celsius"
            ] = $heating_set_point_celsius;
        }
        if ($heating_set_point_fahrenheit !== null) {
            $request_payload[
                "heating_set_point_fahrenheit"
            ] = $heating_set_point_fahrenheit;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/set_hvac_mode",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }

    public function set_temperature_threshold(
        string $device_id,
        ?float $lower_limit_celsius = null,
        ?float $lower_limit_fahrenheit = null,
        ?float $upper_limit_celsius = null,
        ?float $upper_limit_fahrenheit = null,
    ): void {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($lower_limit_celsius !== null) {
            $request_payload["lower_limit_celsius"] = $lower_limit_celsius;
        }
        if ($lower_limit_fahrenheit !== null) {
            $request_payload[
                "lower_limit_fahrenheit"
            ] = $lower_limit_fahrenheit;
        }
        if ($upper_limit_celsius !== null) {
            $request_payload["upper_limit_celsius"] = $upper_limit_celsius;
        }
        if ($upper_limit_fahrenheit !== null) {
            $request_payload[
                "upper_limit_fahrenheit"
            ] = $upper_limit_fahrenheit;
        }

        $this->seam->request(
            "POST",
            "/thermostats/set_temperature_threshold",
            json: (object) $request_payload,
        );
    }

    public function update_climate_preset(
        string $climate_preset_key,
        string $device_id,
        ?string $climate_preset_mode = null,
        ?float $cooling_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        mixed $ecobee_metadata = null,
        ?string $fan_mode_setting = null,
        ?float $heating_set_point_celsius = null,
        ?float $heating_set_point_fahrenheit = null,
        ?string $hvac_mode_setting = null,
        ?bool $manual_override_allowed = null,
        ?string $name = null,
    ): void {
        $request_payload = [];

        if ($climate_preset_key !== null) {
            $request_payload["climate_preset_key"] = $climate_preset_key;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($climate_preset_mode !== null) {
            $request_payload["climate_preset_mode"] = $climate_preset_mode;
        }
        if ($cooling_set_point_celsius !== null) {
            $request_payload[
                "cooling_set_point_celsius"
            ] = $cooling_set_point_celsius;
        }
        if ($cooling_set_point_fahrenheit !== null) {
            $request_payload[
                "cooling_set_point_fahrenheit"
            ] = $cooling_set_point_fahrenheit;
        }
        if ($ecobee_metadata !== null) {
            $request_payload["ecobee_metadata"] = $ecobee_metadata;
        }
        if ($fan_mode_setting !== null) {
            $request_payload["fan_mode_setting"] = $fan_mode_setting;
        }
        if ($heating_set_point_celsius !== null) {
            $request_payload[
                "heating_set_point_celsius"
            ] = $heating_set_point_celsius;
        }
        if ($heating_set_point_fahrenheit !== null) {
            $request_payload[
                "heating_set_point_fahrenheit"
            ] = $heating_set_point_fahrenheit;
        }
        if ($hvac_mode_setting !== null) {
            $request_payload["hvac_mode_setting"] = $hvac_mode_setting;
        }
        if ($manual_override_allowed !== null) {
            $request_payload[
                "manual_override_allowed"
            ] = $manual_override_allowed;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }

        $this->seam->request(
            "POST",
            "/thermostats/update_climate_preset",
            json: (object) $request_payload,
        );
    }

    public function update_weekly_program(
        string $device_id,
        ?string $friday_program_id = null,
        ?string $monday_program_id = null,
        ?string $saturday_program_id = null,
        ?string $sunday_program_id = null,
        ?string $thursday_program_id = null,
        ?string $tuesday_program_id = null,
        ?string $wednesday_program_id = null,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($friday_program_id !== null) {
            $request_payload["friday_program_id"] = $friday_program_id;
        }
        if ($monday_program_id !== null) {
            $request_payload["monday_program_id"] = $monday_program_id;
        }
        if ($saturday_program_id !== null) {
            $request_payload["saturday_program_id"] = $saturday_program_id;
        }
        if ($sunday_program_id !== null) {
            $request_payload["sunday_program_id"] = $sunday_program_id;
        }
        if ($thursday_program_id !== null) {
            $request_payload["thursday_program_id"] = $thursday_program_id;
        }
        if ($tuesday_program_id !== null) {
            $request_payload["tuesday_program_id"] = $tuesday_program_id;
        }
        if ($wednesday_program_id !== null) {
            $request_payload["wednesday_program_id"] = $wednesday_program_id;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/update_weekly_program",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }
}

class ThermostatsDailyProgramsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function create(
        string $device_id,
        string $name,
        array $periods,
    ): ThermostatDailyProgram {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($periods !== null) {
            $request_payload["periods"] = $periods;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/daily_programs/create",
            json: (object) $request_payload,
        );

        return ThermostatDailyProgram::from_json(
            $res->thermostat_daily_program,
        );
    }

    public function delete(string $thermostat_daily_program_id): void
    {
        $request_payload = [];

        if ($thermostat_daily_program_id !== null) {
            $request_payload[
                "thermostat_daily_program_id"
            ] = $thermostat_daily_program_id;
        }

        $this->seam->request(
            "POST",
            "/thermostats/daily_programs/delete",
            json: (object) $request_payload,
        );
    }

    public function update(
        string $name,
        array $periods,
        string $thermostat_daily_program_id,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($periods !== null) {
            $request_payload["periods"] = $periods;
        }
        if ($thermostat_daily_program_id !== null) {
            $request_payload[
                "thermostat_daily_program_id"
            ] = $thermostat_daily_program_id;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/daily_programs/update",
            json: (object) $request_payload,
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }
}

class ThermostatsSchedulesClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function create(
        string $climate_preset_key,
        string $device_id,
        string $ends_at,
        string $starts_at,
        ?bool $is_override_allowed = null,
        mixed $max_override_period_minutes = null,
        ?string $name = null,
    ): ThermostatSchedule {
        $request_payload = [];

        if ($climate_preset_key !== null) {
            $request_payload["climate_preset_key"] = $climate_preset_key;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }
        if ($is_override_allowed !== null) {
            $request_payload["is_override_allowed"] = $is_override_allowed;
        }
        if ($max_override_period_minutes !== null) {
            $request_payload[
                "max_override_period_minutes"
            ] = $max_override_period_minutes;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/schedules/create",
            json: (object) $request_payload,
        );

        return ThermostatSchedule::from_json($res->thermostat_schedule);
    }

    public function delete(string $thermostat_schedule_id): void
    {
        $request_payload = [];

        if ($thermostat_schedule_id !== null) {
            $request_payload[
                "thermostat_schedule_id"
            ] = $thermostat_schedule_id;
        }

        $this->seam->request(
            "POST",
            "/thermostats/schedules/delete",
            json: (object) $request_payload,
        );
    }

    public function get(string $thermostat_schedule_id): ThermostatSchedule
    {
        $request_payload = [];

        if ($thermostat_schedule_id !== null) {
            $request_payload[
                "thermostat_schedule_id"
            ] = $thermostat_schedule_id;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/schedules/get",
            json: (object) $request_payload,
        );

        return ThermostatSchedule::from_json($res->thermostat_schedule);
    }

    public function list(
        string $device_id,
        ?string $user_identifier_key = null,
    ): array {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/schedules/list",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => ThermostatSchedule::from_json($r),
            $res->thermostat_schedules,
        );
    }

    public function update(
        string $thermostat_schedule_id,
        ?string $climate_preset_key = null,
        ?string $ends_at = null,
        ?bool $is_override_allowed = null,
        mixed $max_override_period_minutes = null,
        ?string $name = null,
        ?string $starts_at = null,
    ): void {
        $request_payload = [];

        if ($thermostat_schedule_id !== null) {
            $request_payload[
                "thermostat_schedule_id"
            ] = $thermostat_schedule_id;
        }
        if ($climate_preset_key !== null) {
            $request_payload["climate_preset_key"] = $climate_preset_key;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($is_override_allowed !== null) {
            $request_payload["is_override_allowed"] = $is_override_allowed;
        }
        if ($max_override_period_minutes !== null) {
            $request_payload[
                "max_override_period_minutes"
            ] = $max_override_period_minutes;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }

        $this->seam->request(
            "POST",
            "/thermostats/schedules/update",
            json: (object) $request_payload,
        );
    }
}

class ThermostatsSimulateClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function hvac_mode_adjusted(
        string $device_id,
        string $hvac_mode,
        ?float $cooling_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
        ?float $heating_set_point_celsius = null,
        ?float $heating_set_point_fahrenheit = null,
    ): void {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($hvac_mode !== null) {
            $request_payload["hvac_mode"] = $hvac_mode;
        }
        if ($cooling_set_point_celsius !== null) {
            $request_payload[
                "cooling_set_point_celsius"
            ] = $cooling_set_point_celsius;
        }
        if ($cooling_set_point_fahrenheit !== null) {
            $request_payload[
                "cooling_set_point_fahrenheit"
            ] = $cooling_set_point_fahrenheit;
        }
        if ($heating_set_point_celsius !== null) {
            $request_payload[
                "heating_set_point_celsius"
            ] = $heating_set_point_celsius;
        }
        if ($heating_set_point_fahrenheit !== null) {
            $request_payload[
                "heating_set_point_fahrenheit"
            ] = $heating_set_point_fahrenheit;
        }

        $this->seam->request(
            "POST",
            "/thermostats/simulate/hvac_mode_adjusted",
            json: (object) $request_payload,
        );
    }

    public function temperature_reached(
        string $device_id,
        ?float $temperature_celsius = null,
        ?float $temperature_fahrenheit = null,
    ): void {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($temperature_celsius !== null) {
            $request_payload["temperature_celsius"] = $temperature_celsius;
        }
        if ($temperature_fahrenheit !== null) {
            $request_payload[
                "temperature_fahrenheit"
            ] = $temperature_fahrenheit;
        }

        $this->seam->request(
            "POST",
            "/thermostats/simulate/temperature_reached",
            json: (object) $request_payload,
        );
    }
}

class UserIdentitiesClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function add_acs_user(
        string $acs_user_id,
        ?string $user_identity_id = null,
        ?string $user_identity_key = null,
    ): void {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($user_identity_key !== null) {
            $request_payload["user_identity_key"] = $user_identity_key;
        }

        $this->seam->request(
            "POST",
            "/user_identities/add_acs_user",
            json: (object) $request_payload,
        );
    }

    public function create(
        ?array $acs_system_ids = null,
        ?string $email_address = null,
        ?string $full_name = null,
        ?string $phone_number = null,
        ?string $user_identity_key = null,
    ): UserIdentity {
        $request_payload = [];

        if ($acs_system_ids !== null) {
            $request_payload["acs_system_ids"] = $acs_system_ids;
        }
        if ($email_address !== null) {
            $request_payload["email_address"] = $email_address;
        }
        if ($full_name !== null) {
            $request_payload["full_name"] = $full_name;
        }
        if ($phone_number !== null) {
            $request_payload["phone_number"] = $phone_number;
        }
        if ($user_identity_key !== null) {
            $request_payload["user_identity_key"] = $user_identity_key;
        }

        $res = $this->seam->request(
            "POST",
            "/user_identities/create",
            json: (object) $request_payload,
        );

        return UserIdentity::from_json($res->user_identity);
    }

    public function delete(string $user_identity_id): void
    {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->seam->request(
            "POST",
            "/user_identities/delete",
            json: (object) $request_payload,
        );
    }

    public function generate_instant_key(
        string $user_identity_id,
        ?string $customization_profile_id = null,
        ?float $max_use_count = null,
    ): InstantKey {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($customization_profile_id !== null) {
            $request_payload[
                "customization_profile_id"
            ] = $customization_profile_id;
        }
        if ($max_use_count !== null) {
            $request_payload["max_use_count"] = $max_use_count;
        }

        $res = $this->seam->request(
            "POST",
            "/user_identities/generate_instant_key",
            json: (object) $request_payload,
        );

        return InstantKey::from_json($res->instant_key);
    }

    public function get(
        ?string $user_identity_id = null,
        ?string $user_identity_key = null,
    ): UserIdentity {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($user_identity_key !== null) {
            $request_payload["user_identity_key"] = $user_identity_key;
        }

        $res = $this->seam->request(
            "POST",
            "/user_identities/get",
            json: (object) $request_payload,
        );

        return UserIdentity::from_json($res->user_identity);
    }

    public function grant_access_to_device(
        string $device_id,
        string $user_identity_id,
    ): void {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->seam->request(
            "POST",
            "/user_identities/grant_access_to_device",
            json: (object) $request_payload,
        );
    }

    public function list(
        ?string $credential_manager_acs_system_id = null,
        ?string $search = null,
    ): array {
        $request_payload = [];

        if ($credential_manager_acs_system_id !== null) {
            $request_payload[
                "credential_manager_acs_system_id"
            ] = $credential_manager_acs_system_id;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
        }

        $res = $this->seam->request(
            "POST",
            "/user_identities/list",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => UserIdentity::from_json($r),
            $res->user_identities,
        );
    }

    public function list_accessible_devices(string $user_identity_id): array
    {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->request(
            "POST",
            "/user_identities/list_accessible_devices",
            json: (object) $request_payload,
        );

        return array_map(fn($r) => Device::from_json($r), $res->devices);
    }

    public function list_acs_systems(string $user_identity_id): array
    {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->request(
            "POST",
            "/user_identities/list_acs_systems",
            json: (object) $request_payload,
        );

        return array_map(fn($r) => AcsSystem::from_json($r), $res->acs_systems);
    }

    public function list_acs_users(string $user_identity_id): array
    {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->request(
            "POST",
            "/user_identities/list_acs_users",
            json: (object) $request_payload,
        );

        return array_map(fn($r) => AcsUser::from_json($r), $res->acs_users);
    }

    public function remove_acs_user(
        string $acs_user_id,
        string $user_identity_id,
    ): void {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->seam->request(
            "POST",
            "/user_identities/remove_acs_user",
            json: (object) $request_payload,
        );
    }

    public function revoke_access_to_device(
        string $device_id,
        string $user_identity_id,
    ): void {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $this->seam->request(
            "POST",
            "/user_identities/revoke_access_to_device",
            json: (object) $request_payload,
        );
    }

    public function update(
        string $user_identity_id,
        ?string $email_address = null,
        ?string $full_name = null,
        ?string $phone_number = null,
        ?string $user_identity_key = null,
    ): void {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($email_address !== null) {
            $request_payload["email_address"] = $email_address;
        }
        if ($full_name !== null) {
            $request_payload["full_name"] = $full_name;
        }
        if ($phone_number !== null) {
            $request_payload["phone_number"] = $phone_number;
        }
        if ($user_identity_key !== null) {
            $request_payload["user_identity_key"] = $user_identity_key;
        }

        $this->seam->request(
            "POST",
            "/user_identities/update",
            json: (object) $request_payload,
        );
    }
}

class WebhooksClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function create(string $url, ?array $event_types = null): Webhook
    {
        $request_payload = [];

        if ($url !== null) {
            $request_payload["url"] = $url;
        }
        if ($event_types !== null) {
            $request_payload["event_types"] = $event_types;
        }

        $res = $this->seam->request(
            "POST",
            "/webhooks/create",
            json: (object) $request_payload,
        );

        return Webhook::from_json($res->webhook);
    }

    public function delete(string $webhook_id): void
    {
        $request_payload = [];

        if ($webhook_id !== null) {
            $request_payload["webhook_id"] = $webhook_id;
        }

        $this->seam->request(
            "POST",
            "/webhooks/delete",
            json: (object) $request_payload,
        );
    }

    public function get(string $webhook_id): Webhook
    {
        $request_payload = [];

        if ($webhook_id !== null) {
            $request_payload["webhook_id"] = $webhook_id;
        }

        $res = $this->seam->request(
            "POST",
            "/webhooks/get",
            json: (object) $request_payload,
        );

        return Webhook::from_json($res->webhook);
    }

    public function list(): array
    {
        $res = $this->seam->request("POST", "/webhooks/list");

        return array_map(fn($r) => Webhook::from_json($r), $res->webhooks);
    }

    public function update(array $event_types, string $webhook_id): void
    {
        $request_payload = [];

        if ($event_types !== null) {
            $request_payload["event_types"] = $event_types;
        }
        if ($webhook_id !== null) {
            $request_payload["webhook_id"] = $webhook_id;
        }

        $this->seam->request(
            "POST",
            "/webhooks/update",
            json: (object) $request_payload,
        );
    }
}

class WorkspacesClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function create(
        string $name,
        ?string $company_name = null,
        ?string $connect_partner_name = null,
        mixed $connect_webview_customization = null,
        ?bool $is_sandbox = null,
        ?string $webview_logo_shape = null,
        ?string $webview_primary_button_color = null,
        ?string $webview_primary_button_text_color = null,
        ?string $webview_success_message = null,
    ): Workspace {
        $request_payload = [];

        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($company_name !== null) {
            $request_payload["company_name"] = $company_name;
        }
        if ($connect_partner_name !== null) {
            $request_payload["connect_partner_name"] = $connect_partner_name;
        }
        if ($connect_webview_customization !== null) {
            $request_payload[
                "connect_webview_customization"
            ] = $connect_webview_customization;
        }
        if ($is_sandbox !== null) {
            $request_payload["is_sandbox"] = $is_sandbox;
        }
        if ($webview_logo_shape !== null) {
            $request_payload["webview_logo_shape"] = $webview_logo_shape;
        }
        if ($webview_primary_button_color !== null) {
            $request_payload[
                "webview_primary_button_color"
            ] = $webview_primary_button_color;
        }
        if ($webview_primary_button_text_color !== null) {
            $request_payload[
                "webview_primary_button_text_color"
            ] = $webview_primary_button_text_color;
        }
        if ($webview_success_message !== null) {
            $request_payload[
                "webview_success_message"
            ] = $webview_success_message;
        }

        $res = $this->seam->request(
            "POST",
            "/workspaces/create",
            json: (object) $request_payload,
        );

        return Workspace::from_json($res->workspace);
    }

    public function get(): Workspace
    {
        $res = $this->seam->request("POST", "/workspaces/get");

        return Workspace::from_json($res->workspace);
    }

    public function list(): array
    {
        $res = $this->seam->request("POST", "/workspaces/list");

        return array_map(fn($r) => Workspace::from_json($r), $res->workspaces);
    }

    public function reset_sandbox(
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $res = $this->seam->request("POST", "/workspaces/reset_sandbox");

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res->action_attempt);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt->action_attempt_id,
        );

        return $action_attempt;
    }
}
