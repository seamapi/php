<?php

namespace Seam;

use Seam\Objects\AccessCode;
use Seam\Objects\AcsAccessGroup;
use Seam\Objects\AcsCredential;
use Seam\Objects\AcsCredentialPool;
use Seam\Objects\AcsCredentialProvisioningAutomation;
use Seam\Objects\AcsEntrance;
use Seam\Objects\AcsSystem;
use Seam\Objects\AcsUser;
use Seam\Objects\ActionAttempt;
use Seam\Objects\ClientSession;
use Seam\Objects\ClimateSettingSchedule;
use Seam\Objects\ConnectWebview;
use Seam\Objects\ConnectedAccount;
use Seam\Objects\Device;
use Seam\Objects\DeviceProvider;
use Seam\Objects\EnrollmentAutomation;
use Seam\Objects\Event;
use Seam\Objects\Network;
use Seam\Objects\NoiseThreshold;
use Seam\Objects\Phone;
use Seam\Objects\ServiceHealth;
use Seam\Objects\UnmanagedAccessCode;
use Seam\Objects\UnmanagedDevice;
use Seam\Objects\UserIdentity;
use Seam\Objects\Webhook;
use Seam\Objects\Workspace;
use Seam\Utils\PackageVersion;

use GuzzleHttp\Client as HTTPClient;
use \Exception as Exception;

define("LTS_VERSION", "1.0.0");

class SeamClient
{
    public AccessCodesClient $access_codes;
    public AcsClient $acs;
    public ActionAttemptsClient $action_attempts;
    public ClientSessionsClient $client_sessions;
    public ConnectWebviewsClient $connect_webviews;
    public ConnectedAccountsClient $connected_accounts;
    public DevicesClient $devices;
    public EventsClient $events;
    public LocksClient $locks;
    public NetworksClient $networks;
    public NoiseSensorsClient $noise_sensors;
    public PhonesClient $phones;
    public ThermostatsClient $thermostats;
    public UserIdentitiesClient $user_identities;
    public WebhooksClient $webhooks;
    public WorkspacesClient $workspaces;

    public string $api_key;
    public HTTPClient $client;
    public string $ltsVersion = LTS_VERSION;

    public function __construct(
        $api_key,
        $endpoint = "https://connect.getseam.com",
        $throw_http_errors = false
    ) {
        $this->api_key = $api_key;
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
        $this->acs = new AcsClient($this);
        $this->action_attempts = new ActionAttemptsClient($this);
        $this->client_sessions = new ClientSessionsClient($this);
        $this->connect_webviews = new ConnectWebviewsClient($this);
        $this->connected_accounts = new ConnectedAccountsClient($this);
        $this->devices = new DevicesClient($this);
        $this->events = new EventsClient($this);
        $this->locks = new LocksClient($this);
        $this->networks = new NetworksClient($this);
        $this->noise_sensors = new NoiseSensorsClient($this);
        $this->phones = new PhonesClient($this);
        $this->thermostats = new ThermostatsClient($this);
        $this->user_identities = new UserIdentitiesClient($this);
        $this->webhooks = new WebhooksClient($this);
        $this->workspaces = new WorkspacesClient($this);
    }

    public function request(
        $method,
        $path,
        $json = null,
        $query = null,
        $inner_object = null
    ) {
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

        if (($res_json->error ?? null) != null) {
            throw new Exception(
                "Error Calling \"" .
                    $method .
                    " " .
                    $path .
                    "\" : " .
                    ($res_json->error->type ?? "") .
                    ": " .
                    $res_json->error->message .
                    " [Request ID: " .
                    $request_id .
                    "]"
            );
        }

        if ($status_code >= 400) {
            $error_message = $response->getReasonPhrase();

            throw new Exception(
                "HTTP Error: " .
                    $error_message .
                    " [" .
                    $status_code .
                    "] " .
                    $method .
                    " " .
                    $path .
                    " [Request ID: " .
                    $request_id .
                    "]"
            );
        }

        if ($inner_object) {
            if (
                !is_array($res_json->$inner_object) &&
                ($res_json->$inner_object ?? null) == null
            ) {
                throw new Exception(
                    'Missing Inner Object "' .
                        $inner_object .
                        '" for ' .
                        $method .
                        " " .
                        $path .
                        " [Request ID: " .
                        $request_id .
                        "]"
                );
            }
            return $res_json->$inner_object;
        }
        return $res_json;
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
        bool $allow_external_modification = null,
        bool $attempt_for_offline_device = null,
        string $code = null,
        string $common_code_key = null,
        string $ends_at = null,
        bool $is_external_modification_allowed = null,
        bool $is_offline_access_code = null,
        bool $is_one_time_use = null,
        string $max_time_rounding = null,
        string $name = null,
        bool $prefer_native_scheduling = null,
        string $starts_at = null,
        bool $sync = null,
        bool $use_backup_access_code_pool = null,
        bool $use_offline_access_code = null
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
            json: $request_payload,
            inner_object: "access_code"
        );

        return AccessCode::from_json($res);
    }

    public function create_multiple(
        array $device_ids,
        bool $allow_external_modification = null,
        bool $attempt_for_offline_device = null,
        string $behavior_when_code_cannot_be_shared = null,
        string $code = null,
        string $ends_at = null,
        bool $is_external_modification_allowed = null,
        bool $is_offline_access_code = null,
        bool $is_one_time_use = null,
        string $max_time_rounding = null,
        string $name = null,
        bool $prefer_native_scheduling = null,
        float $preferred_code_length = null,
        string $starts_at = null,
        bool $use_backup_access_code_pool = null,
        bool $use_offline_access_code = null
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
            json: $request_payload,
            inner_object: "access_codes"
        );

        return array_map(fn($r) => AccessCode::from_json($r), $res);
    }

    public function delete(
        string $access_code_id,
        string $device_id = null,
        bool $sync = null
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
            json: $request_payload
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
            json: $request_payload,
            inner_object: "generated_code"
        );

        return AccessCode::from_json($res);
    }

    public function get(
        string $access_code_id = null,
        string $code = null,
        string $device_id = null
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
            json: $request_payload,
            inner_object: "access_code"
        );

        return AccessCode::from_json($res);
    }

    public function list(
        array $access_code_ids = null,
        string $device_id = null,
        string $user_identifier_key = null
    ): array {
        $request_payload = [];

        if ($access_code_ids !== null) {
            $request_payload["access_code_ids"] = $access_code_ids;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/access_codes/list",
            json: $request_payload,
            inner_object: "access_codes"
        );

        return array_map(fn($r) => AccessCode::from_json($r), $res);
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
            json: $request_payload,
            inner_object: "backup_access_code"
        );

        return AccessCode::from_json($res);
    }

    public function update(
        string $access_code_id,
        bool $allow_external_modification = null,
        bool $attempt_for_offline_device = null,
        string $code = null,
        string $device_id = null,
        string $ends_at = null,
        bool $is_external_modification_allowed = null,
        bool $is_managed = null,
        bool $is_offline_access_code = null,
        bool $is_one_time_use = null,
        string $max_time_rounding = null,
        string $name = null,
        bool $prefer_native_scheduling = null,
        string $starts_at = null,
        bool $sync = null,
        string $type = null,
        bool $use_backup_access_code_pool = null,
        bool $use_offline_access_code = null
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
            json: $request_payload
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
        string $name
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
            json: $request_payload,
            inner_object: "access_code"
        );

        return UnmanagedAccessCode::from_json($res);
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
        bool $allow_external_modification = null,
        bool $force = null,
        bool $is_external_modification_allowed = null,
        bool $sync = null
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
            json: $request_payload
        );
    }

    public function delete(string $access_code_id, bool $sync = null): void
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
            json: $request_payload
        );
    }

    public function get(
        string $access_code_id = null,
        string $code = null,
        string $device_id = null
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
            json: $request_payload,
            inner_object: "access_code"
        );

        return UnmanagedAccessCode::from_json($res);
    }

    public function list(
        string $device_id,
        string $user_identifier_key = null
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
            "/access_codes/unmanaged/list",
            json: $request_payload,
            inner_object: "access_codes"
        );

        return array_map(fn($r) => UnmanagedAccessCode::from_json($r), $res);
    }

    public function update(
        string $access_code_id,
        bool $is_managed,
        bool $allow_external_modification = null,
        bool $force = null,
        bool $is_external_modification_allowed = null
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
            json: $request_payload
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
        string $acs_user_id
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
            "/acs/access_groups/add_user",
            json: $request_payload
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
            json: $request_payload,
            inner_object: "acs_access_group"
        );

        return AcsAccessGroup::from_json($res);
    }

    public function list(
        string $acs_system_id = null,
        string $acs_user_id = null
    ): array {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/access_groups/list",
            json: $request_payload,
            inner_object: "acs_access_groups"
        );

        return array_map(fn($r) => AcsAccessGroup::from_json($r), $res);
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
            json: $request_payload,
            inner_object: "acs_users"
        );

        return array_map(fn($r) => AcsUser::from_json($r), $res);
    }

    public function remove_user(
        string $acs_access_group_id,
        string $acs_user_id
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
            "/acs/access_groups/remove_user",
            json: $request_payload
        );
    }
}

class AcsClient
{
    private SeamClient $seam;
    public AcsAccessGroupsClient $access_groups;
    public AcsCredentialPoolsClient $credential_pools;
    public AcsCredentialProvisioningAutomationsClient $credential_provisioning_automations;
    public AcsCredentialsClient $credentials;
    public AcsEntrancesClient $entrances;
    public AcsSystemsClient $systems;
    public AcsUsersClient $users;
    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
        $this->access_groups = new AcsAccessGroupsClient($seam);
        $this->credential_pools = new AcsCredentialPoolsClient($seam);
        $this->credential_provisioning_automations = new AcsCredentialProvisioningAutomationsClient(
            $seam
        );
        $this->credentials = new AcsCredentialsClient($seam);
        $this->entrances = new AcsEntrancesClient($seam);
        $this->systems = new AcsSystemsClient($seam);
        $this->users = new AcsUsersClient($seam);
    }
}

class AcsCredentialPoolsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function list(string $acs_system_id): array
    {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/credential_pools/list",
            json: $request_payload,
            inner_object: "acs_credential_pools"
        );

        return array_map(fn($r) => AcsCredentialPool::from_json($r), $res);
    }
}

class AcsCredentialProvisioningAutomationsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function launch(
        string $credential_manager_acs_system_id,
        string $user_identity_id,
        string $acs_credential_pool_id = null,
        bool $create_credential_manager_user = null,
        string $credential_manager_acs_user_id = null
    ): AcsCredentialProvisioningAutomation {
        $request_payload = [];

        if ($credential_manager_acs_system_id !== null) {
            $request_payload[
                "credential_manager_acs_system_id"
            ] = $credential_manager_acs_system_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($acs_credential_pool_id !== null) {
            $request_payload[
                "acs_credential_pool_id"
            ] = $acs_credential_pool_id;
        }
        if ($create_credential_manager_user !== null) {
            $request_payload[
                "create_credential_manager_user"
            ] = $create_credential_manager_user;
        }
        if ($credential_manager_acs_user_id !== null) {
            $request_payload[
                "credential_manager_acs_user_id"
            ] = $credential_manager_acs_user_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/credential_provisioning_automations/launch",
            json: $request_payload,
            inner_object: "acs_credential_provisioning_automation"
        );

        return AcsCredentialProvisioningAutomation::from_json($res);
    }
}

class AcsCredentialsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function assign(string $acs_credential_id, string $acs_user_id): void
    {
        $request_payload = [];

        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $this->seam->request(
            "POST",
            "/acs/credentials/assign",
            json: $request_payload
        );
    }

    public function create(
        string $access_method,
        string $acs_user_id,
        array $allowed_acs_entrance_ids = null,
        string $code = null,
        string $credential_manager_acs_system_id = null,
        string $ends_at = null,
        bool $is_multi_phone_sync_credential = null,
        string $starts_at = null,
        mixed $visionline_metadata = null
    ): AcsCredential {
        $request_payload = [];

        if ($access_method !== null) {
            $request_payload["access_method"] = $access_method;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($allowed_acs_entrance_ids !== null) {
            $request_payload[
                "allowed_acs_entrance_ids"
            ] = $allowed_acs_entrance_ids;
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
        if ($is_multi_phone_sync_credential !== null) {
            $request_payload[
                "is_multi_phone_sync_credential"
            ] = $is_multi_phone_sync_credential;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }
        if ($visionline_metadata !== null) {
            $request_payload["visionline_metadata"] = $visionline_metadata;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/credentials/create",
            json: $request_payload,
            inner_object: "acs_credential"
        );

        return AcsCredential::from_json($res);
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
            json: $request_payload
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
            json: $request_payload,
            inner_object: "acs_credential"
        );

        return AcsCredential::from_json($res);
    }

    public function list(
        string $acs_user_id = null,
        string $acs_system_id = null,
        string $user_identity_id = null,
        bool $is_multi_phone_sync_credential = null
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
        if ($is_multi_phone_sync_credential !== null) {
            $request_payload[
                "is_multi_phone_sync_credential"
            ] = $is_multi_phone_sync_credential;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/credentials/list",
            json: $request_payload,
            inner_object: "acs_credentials"
        );

        return array_map(fn($r) => AcsCredential::from_json($r), $res);
    }

    public function unassign(
        string $acs_credential_id,
        string $acs_user_id
    ): void {
        $request_payload = [];

        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $this->seam->request(
            "POST",
            "/acs/credentials/unassign",
            json: $request_payload
        );
    }

    public function update(string $acs_credential_id, string $code): void
    {
        $request_payload = [];

        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }
        if ($code !== null) {
            $request_payload["code"] = $code;
        }

        $this->seam->request(
            "POST",
            "/acs/credentials/update",
            json: $request_payload
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
            json: $request_payload,
            inner_object: "acs_entrance"
        );

        return AcsEntrance::from_json($res);
    }

    public function grant_access(
        string $acs_entrance_id,
        string $acs_user_id
    ): void {
        $request_payload = [];

        if ($acs_entrance_id !== null) {
            $request_payload["acs_entrance_id"] = $acs_entrance_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $this->seam->request(
            "POST",
            "/acs/entrances/grant_access",
            json: $request_payload
        );
    }

    public function list(
        string $acs_credential_id = null,
        string $acs_system_id = null
    ): array {
        $request_payload = [];

        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/entrances/list",
            json: $request_payload,
            inner_object: "acs_entrances"
        );

        return array_map(fn($r) => AcsEntrance::from_json($r), $res);
    }

    public function list_credentials_with_access(
        string $acs_entrance_id,
        array $include_if = null
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
            json: $request_payload,
            inner_object: "acs_credentials"
        );

        return array_map(fn($r) => AcsCredential::from_json($r), $res);
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
            json: $request_payload,
            inner_object: "acs_system"
        );

        return AcsSystem::from_json($res);
    }

    public function list(string $connected_account_id = null): array
    {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/systems/list",
            json: $request_payload,
            inner_object: "acs_systems"
        );

        return array_map(fn($r) => AcsSystem::from_json($r), $res);
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
        string $acs_user_id
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
            json: $request_payload
        );
    }

    public function create(
        string $acs_system_id,
        mixed $access_schedule = null,
        array $acs_access_group_ids = null,
        string $email = null,
        string $email_address = null,
        string $full_name = null,
        string $phone_number = null,
        string $user_identity_id = null
    ): AcsUser {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
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
        if ($full_name !== null) {
            $request_payload["full_name"] = $full_name;
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
            json: $request_payload,
            inner_object: "acs_user"
        );

        return AcsUser::from_json($res);
    }

    public function delete(string $acs_user_id): void
    {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $this->seam->request(
            "POST",
            "/acs/users/delete",
            json: $request_payload
        );
    }

    public function get(string $acs_user_id): AcsUser
    {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/users/get",
            json: $request_payload,
            inner_object: "acs_user"
        );

        return AcsUser::from_json($res);
    }

    public function list(
        string $acs_system_id = null,
        string $user_identity_email_address = null,
        string $user_identity_id = null,
        string $user_identity_phone_number = null
    ): array {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
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
            json: $request_payload,
            inner_object: "acs_users"
        );

        return array_map(fn($r) => AcsUser::from_json($r), $res);
    }

    public function list_accessible_entrances(string $acs_user_id): array
    {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/users/list_accessible_entrances",
            json: $request_payload,
            inner_object: "acs_entrances"
        );

        return array_map(fn($r) => AcsEntrance::from_json($r), $res);
    }

    public function remove_from_access_group(
        string $acs_access_group_id,
        string $acs_user_id
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
            "/acs/users/remove_from_access_group",
            json: $request_payload
        );
    }

    public function revoke_access_to_all_entrances(string $acs_user_id): void
    {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $this->seam->request(
            "POST",
            "/acs/users/revoke_access_to_all_entrances",
            json: $request_payload
        );
    }

    public function suspend(string $acs_user_id): void
    {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $this->seam->request(
            "POST",
            "/acs/users/suspend",
            json: $request_payload
        );
    }

    public function unsuspend(string $acs_user_id): void
    {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $this->seam->request(
            "POST",
            "/acs/users/unsuspend",
            json: $request_payload
        );
    }

    public function update(
        string $acs_user_id,
        mixed $access_schedule = null,
        string $email = null,
        string $email_address = null,
        string $full_name = null,
        string $hid_acs_system_id = null,
        string $phone_number = null
    ): void {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($access_schedule !== null) {
            $request_payload["access_schedule"] = $access_schedule;
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

        $this->seam->request(
            "POST",
            "/acs/users/update",
            json: $request_payload
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
            json: $request_payload,
            inner_object: "action_attempt"
        );

        return ActionAttempt::from_json($res);
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
            json: $request_payload,
            inner_object: "action_attempts"
        );

        return array_map(fn($r) => ActionAttempt::from_json($r), $res);
    }
    public function poll_until_ready(string $action_attempt_id): ActionAttempt
    {
        $seam = $this->seam;
        $time_waiting = 0.0;
        $action_attempt = $seam->action_attempts->get($action_attempt_id);

        while ($action_attempt->status == "pending") {
            $action_attempt = $seam->action_attempts->get(
                $action_attempt->action_attempt_id
            );
            if ($time_waiting > 20.0) {
                throw new Exception(
                    "Timed out waiting for action attempt to be ready"
                );
            }
            $time_waiting += 0.4;
            usleep(400000); // sleep for 0.4 seconds
        }

        if ($action_attempt->status == "failed") {
            throw new Exception(
                "Action Attempt failed: " . $action_attempt->error->message
            );
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
        array $connect_webview_ids = null,
        array $connected_account_ids = null,
        string $expires_at = null,
        string $user_identifier_key = null,
        array $user_identity_ids = null
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
        if ($user_identity_ids !== null) {
            $request_payload["user_identity_ids"] = $user_identity_ids;
        }

        $res = $this->seam->request(
            "POST",
            "/client_sessions/create",
            json: $request_payload,
            inner_object: "client_session"
        );

        return ClientSession::from_json($res);
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
            json: $request_payload
        );
    }

    public function get(
        string $client_session_id = null,
        string $user_identifier_key = null
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
            json: $request_payload,
            inner_object: "client_session"
        );

        return ClientSession::from_json($res);
    }

    public function get_or_create(
        array $connect_webview_ids = null,
        array $connected_account_ids = null,
        string $expires_at = null,
        string $user_identifier_key = null,
        array $user_identity_ids = null
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
        if ($user_identity_ids !== null) {
            $request_payload["user_identity_ids"] = $user_identity_ids;
        }

        $res = $this->seam->request(
            "POST",
            "/client_sessions/get_or_create",
            json: $request_payload,
            inner_object: "client_session"
        );

        return ClientSession::from_json($res);
    }

    public function grant_access(
        string $client_session_id = null,
        array $connect_webview_ids = null,
        array $connected_account_ids = null,
        string $user_identifier_key = null,
        array $user_identity_ids = null
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
        if ($user_identity_ids !== null) {
            $request_payload["user_identity_ids"] = $user_identity_ids;
        }

        $this->seam->request(
            "POST",
            "/client_sessions/grant_access",
            json: $request_payload
        );
    }

    public function list(
        string $client_session_id = null,
        string $connect_webview_id = null,
        string $user_identifier_key = null,
        string $user_identity_id = null,
        bool $without_user_identifier_key = null
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
            json: $request_payload,
            inner_object: "client_sessions"
        );

        return array_map(fn($r) => ClientSession::from_json($r), $res);
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
            json: $request_payload
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
        array $accepted_providers = null,
        bool $automatically_manage_new_devices = null,
        mixed $custom_metadata = null,
        string $custom_redirect_failure_url = null,
        string $custom_redirect_url = null,
        string $device_selection_mode = null,
        string $provider_category = null,
        bool $wait_for_device_creation = null
    ): ConnectWebview {
        $request_payload = [];

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
        if ($device_selection_mode !== null) {
            $request_payload["device_selection_mode"] = $device_selection_mode;
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
            json: $request_payload,
            inner_object: "connect_webview"
        );

        return ConnectWebview::from_json($res);
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
            json: $request_payload
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
            json: $request_payload,
            inner_object: "connect_webview"
        );

        return ConnectWebview::from_json($res);
    }

    public function list(
        mixed $custom_metadata_has = null,
        string $user_identifier_key = null
    ): array {
        $request_payload = [];

        if ($custom_metadata_has !== null) {
            $request_payload["custom_metadata_has"] = $custom_metadata_has;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/connect_webviews/list",
            json: $request_payload,
            inner_object: "connect_webviews"
        );

        return array_map(fn($r) => ConnectWebview::from_json($r), $res);
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
        bool $sync = null
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
            json: $request_payload
        );
    }

    public function get(
        string $connected_account_id = null,
        string $email = null
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
            json: $request_payload,
            inner_object: "connected_account"
        );

        return ConnectedAccount::from_json($res);
    }

    public function list(mixed $custom_metadata_has = null): array
    {
        $request_payload = [];

        if ($custom_metadata_has !== null) {
            $request_payload["custom_metadata_has"] = $custom_metadata_has;
        }

        $res = $this->seam->request(
            "POST",
            "/connected_accounts/list",
            json: $request_payload,
            inner_object: "connected_accounts"
        );

        return array_map(fn($r) => ConnectedAccount::from_json($r), $res);
    }

    public function update(
        string $connected_account_id,
        bool $automatically_manage_new_devices = null,
        mixed $custom_metadata = null
    ): ConnectedAccount {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($automatically_manage_new_devices !== null) {
            $request_payload[
                "automatically_manage_new_devices"
            ] = $automatically_manage_new_devices;
        }
        if ($custom_metadata !== null) {
            $request_payload["custom_metadata"] = $custom_metadata;
        }

        $res = $this->seam->request(
            "POST",
            "/connected_accounts/update",
            json: $request_payload,
            inner_object: "connected_account"
        );

        return ConnectedAccount::from_json($res);
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

    public function delete(string $device_id): void
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->request("POST", "/devices/delete", json: $request_payload);
    }

    public function get(string $device_id = null, string $name = null): Device
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
            json: $request_payload,
            inner_object: "device"
        );

        return Device::from_json($res);
    }

    public function list(
        string $connect_webview_id = null,
        string $connected_account_id = null,
        array $connected_account_ids = null,
        string $created_before = null,
        mixed $custom_metadata_has = null,
        array $device_ids = null,
        string $device_type = null,
        array $device_types = null,
        array $exclude_if = null,
        array $include_if = null,
        float $limit = null,
        string $manufacturer = null,
        string $user_identifier_key = null
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
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/devices/list",
            json: $request_payload,
            inner_object: "devices"
        );

        return array_map(fn($r) => Device::from_json($r), $res);
    }

    public function list_device_providers(
        string $provider_category = null
    ): array {
        $request_payload = [];

        if ($provider_category !== null) {
            $request_payload["provider_category"] = $provider_category;
        }

        $res = $this->seam->request(
            "POST",
            "/devices/list_device_providers",
            json: $request_payload,
            inner_object: "device_providers"
        );

        return array_map(fn($r) => DeviceProvider::from_json($r), $res);
    }

    public function update(
        string $device_id,
        mixed $custom_metadata = null,
        bool $is_managed = null,
        string $name = null,
        mixed $properties = null
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

        $this->seam->request("POST", "/devices/update", json: $request_payload);
    }
}

class DevicesSimulateClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
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
            json: $request_payload
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
        string $device_id = null,
        string $name = null
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
            json: $request_payload,
            inner_object: "device"
        );

        return UnmanagedDevice::from_json($res);
    }

    public function list(
        string $connect_webview_id = null,
        string $connected_account_id = null,
        array $connected_account_ids = null,
        string $created_before = null,
        mixed $custom_metadata_has = null,
        array $device_ids = null,
        string $device_type = null,
        array $device_types = null,
        array $exclude_if = null,
        array $include_if = null,
        float $limit = null,
        string $manufacturer = null,
        string $user_identifier_key = null
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
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/devices/unmanaged/list",
            json: $request_payload,
            inner_object: "devices"
        );

        return array_map(fn($r) => UnmanagedDevice::from_json($r), $res);
    }

    public function update(string $device_id, bool $is_managed): void
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($is_managed !== null) {
            $request_payload["is_managed"] = $is_managed;
        }

        $this->seam->request(
            "POST",
            "/devices/unmanaged/update",
            json: $request_payload
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
        string $device_id = null,
        string $event_id = null,
        string $event_type = null
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
            json: $request_payload,
            inner_object: "event"
        );

        return Event::from_json($res);
    }

    public function list(
        string $access_code_id = null,
        array $access_code_ids = null,
        array $between = null,
        string $connected_account_id = null,
        string $device_id = null,
        array $device_ids = null,
        string $event_type = null,
        array $event_types = null,
        float $limit = null,
        string $since = null
    ): array {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($access_code_ids !== null) {
            $request_payload["access_code_ids"] = $access_code_ids;
        }
        if ($between !== null) {
            $request_payload["between"] = $between;
        }
        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($device_ids !== null) {
            $request_payload["device_ids"] = $device_ids;
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

        $res = $this->seam->request(
            "POST",
            "/events/list",
            json: $request_payload,
            inner_object: "events"
        );

        return array_map(fn($r) => Event::from_json($r), $res);
    }
}

class LocksClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function get(string $device_id = null, string $name = null): Device
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
            json: $request_payload,
            inner_object: "device"
        );

        return Device::from_json($res);
    }

    public function list(
        string $connect_webview_id = null,
        string $connected_account_id = null,
        array $connected_account_ids = null,
        string $created_before = null,
        mixed $custom_metadata_has = null,
        array $device_ids = null,
        string $device_type = null,
        array $device_types = null,
        array $exclude_if = null,
        array $include_if = null,
        float $limit = null,
        string $manufacturer = null,
        string $user_identifier_key = null
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
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/locks/list",
            json: $request_payload,
            inner_object: "devices"
        );

        return array_map(fn($r) => Device::from_json($r), $res);
    }

    public function lock_door(
        string $device_id,
        bool $sync = null,
        bool $wait_for_action_attempt = true
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
            json: $request_payload,
            inner_object: "action_attempt"
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt_id
        );

        return $action_attempt;
    }

    public function unlock_door(
        string $device_id,
        bool $sync = null,
        bool $wait_for_action_attempt = true
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
            json: $request_payload,
            inner_object: "action_attempt"
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt_id
        );

        return $action_attempt;
    }
}

class NetworksClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function get(string $network_id): Network
    {
        $request_payload = [];

        if ($network_id !== null) {
            $request_payload["network_id"] = $network_id;
        }

        $res = $this->seam->request(
            "POST",
            "/networks/get",
            json: $request_payload,
            inner_object: "network"
        );

        return Network::from_json($res);
    }

    public function list(): array
    {
        $request_payload = [];

        $res = $this->seam->request(
            "POST",
            "/networks/list",
            json: $request_payload,
            inner_object: "networks"
        );

        return array_map(fn($r) => Network::from_json($r), $res);
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
        string $name = null,
        float $noise_threshold_decibels = null,
        float $noise_threshold_nrs = null,
        bool $sync = null
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
            json: $request_payload,
            inner_object: "noise_threshold"
        );

        return NoiseThreshold::from_json($res);
    }

    public function delete(
        string $device_id,
        string $noise_threshold_id,
        bool $sync = null
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
            json: $request_payload
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
            json: $request_payload,
            inner_object: "noise_threshold"
        );

        return NoiseThreshold::from_json($res);
    }

    public function list(string $device_id, bool $is_programmed = null): array
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
            json: $request_payload,
            inner_object: "noise_thresholds"
        );

        return array_map(fn($r) => NoiseThreshold::from_json($r), $res);
    }

    public function update(
        string $device_id,
        string $noise_threshold_id,
        string $ends_daily_at = null,
        string $name = null,
        float $noise_threshold_decibels = null,
        float $noise_threshold_nrs = null,
        string $starts_daily_at = null,
        bool $sync = null
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
            json: $request_payload
        );
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
            json: $request_payload
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
            json: $request_payload
        );
    }

    public function list(string $owner_user_identity_id = null): array
    {
        $request_payload = [];

        if ($owner_user_identity_id !== null) {
            $request_payload[
                "owner_user_identity_id"
            ] = $owner_user_identity_id;
        }

        $res = $this->seam->request(
            "POST",
            "/phones/list",
            json: $request_payload,
            inner_object: "phones"
        );

        return array_map(fn($r) => Phone::from_json($r), $res);
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
        string $custom_sdk_installation_id = null,
        mixed $phone_metadata = null
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
            json: $request_payload,
            inner_object: "phone"
        );

        return Phone::from_json($res);
    }
}

class ThermostatsClimateSettingSchedulesClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function create(
        string $device_id,
        string $schedule_ends_at,
        string $schedule_starts_at,
        bool $automatic_cooling_enabled = null,
        bool $automatic_heating_enabled = null,
        float $cooling_set_point_celsius = null,
        float $cooling_set_point_fahrenheit = null,
        float $heating_set_point_celsius = null,
        float $heating_set_point_fahrenheit = null,
        string $hvac_mode_setting = null,
        bool $manual_override_allowed = null,
        string $name = null,
        string $schedule_type = null
    ): ClimateSettingSchedule {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($schedule_ends_at !== null) {
            $request_payload["schedule_ends_at"] = $schedule_ends_at;
        }
        if ($schedule_starts_at !== null) {
            $request_payload["schedule_starts_at"] = $schedule_starts_at;
        }
        if ($automatic_cooling_enabled !== null) {
            $request_payload[
                "automatic_cooling_enabled"
            ] = $automatic_cooling_enabled;
        }
        if ($automatic_heating_enabled !== null) {
            $request_payload[
                "automatic_heating_enabled"
            ] = $automatic_heating_enabled;
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
        if ($schedule_type !== null) {
            $request_payload["schedule_type"] = $schedule_type;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/climate_setting_schedules/create",
            json: $request_payload,
            inner_object: "climate_setting_schedule"
        );

        return ClimateSettingSchedule::from_json($res);
    }

    public function delete(string $climate_setting_schedule_id): void
    {
        $request_payload = [];

        if ($climate_setting_schedule_id !== null) {
            $request_payload[
                "climate_setting_schedule_id"
            ] = $climate_setting_schedule_id;
        }

        $this->seam->request(
            "POST",
            "/thermostats/climate_setting_schedules/delete",
            json: $request_payload
        );
    }

    public function get(
        string $climate_setting_schedule_id = null,
        string $device_id = null
    ): ClimateSettingSchedule {
        $request_payload = [];

        if ($climate_setting_schedule_id !== null) {
            $request_payload[
                "climate_setting_schedule_id"
            ] = $climate_setting_schedule_id;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/climate_setting_schedules/get",
            json: $request_payload,
            inner_object: "climate_setting_schedule"
        );

        return ClimateSettingSchedule::from_json($res);
    }

    public function list(
        string $device_id,
        string $user_identifier_key = null
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
            "/thermostats/climate_setting_schedules/list",
            json: $request_payload,
            inner_object: "climate_setting_schedules"
        );

        return array_map(fn($r) => ClimateSettingSchedule::from_json($r), $res);
    }

    public function update(
        string $climate_setting_schedule_id,
        bool $automatic_cooling_enabled = null,
        bool $automatic_heating_enabled = null,
        float $cooling_set_point_celsius = null,
        float $cooling_set_point_fahrenheit = null,
        float $heating_set_point_celsius = null,
        float $heating_set_point_fahrenheit = null,
        string $hvac_mode_setting = null,
        bool $manual_override_allowed = null,
        string $name = null,
        string $schedule_ends_at = null,
        string $schedule_starts_at = null,
        string $schedule_type = null
    ): void {
        $request_payload = [];

        if ($climate_setting_schedule_id !== null) {
            $request_payload[
                "climate_setting_schedule_id"
            ] = $climate_setting_schedule_id;
        }
        if ($automatic_cooling_enabled !== null) {
            $request_payload[
                "automatic_cooling_enabled"
            ] = $automatic_cooling_enabled;
        }
        if ($automatic_heating_enabled !== null) {
            $request_payload[
                "automatic_heating_enabled"
            ] = $automatic_heating_enabled;
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
        if ($schedule_ends_at !== null) {
            $request_payload["schedule_ends_at"] = $schedule_ends_at;
        }
        if ($schedule_starts_at !== null) {
            $request_payload["schedule_starts_at"] = $schedule_starts_at;
        }
        if ($schedule_type !== null) {
            $request_payload["schedule_type"] = $schedule_type;
        }

        $this->seam->request(
            "POST",
            "/thermostats/climate_setting_schedules/update",
            json: $request_payload
        );
    }
}

class ThermostatsClient
{
    private SeamClient $seam;
    public ThermostatsClimateSettingSchedulesClient $climate_setting_schedules;
    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
        $this->climate_setting_schedules = new ThermostatsClimateSettingSchedulesClient(
            $seam
        );
    }

    public function cool(
        string $device_id,
        float $cooling_set_point_celsius = null,
        float $cooling_set_point_fahrenheit = null,
        bool $sync = null,
        bool $wait_for_action_attempt = true
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
            json: $request_payload,
            inner_object: "action_attempt"
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt_id
        );

        return $action_attempt;
    }

    public function get(string $device_id = null, string $name = null): Device
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
            "/thermostats/get",
            json: $request_payload,
            inner_object: "thermostat"
        );

        return Device::from_json($res);
    }

    public function heat(
        string $device_id,
        float $heating_set_point_celsius = null,
        float $heating_set_point_fahrenheit = null,
        bool $sync = null,
        bool $wait_for_action_attempt = true
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
            json: $request_payload,
            inner_object: "action_attempt"
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt_id
        );

        return $action_attempt;
    }

    public function heat_cool(
        string $device_id,
        float $cooling_set_point_celsius = null,
        float $cooling_set_point_fahrenheit = null,
        float $heating_set_point_celsius = null,
        float $heating_set_point_fahrenheit = null,
        bool $sync = null,
        bool $wait_for_action_attempt = true
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
            json: $request_payload,
            inner_object: "action_attempt"
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt_id
        );

        return $action_attempt;
    }

    public function list(
        string $connect_webview_id = null,
        string $connected_account_id = null,
        array $connected_account_ids = null,
        string $created_before = null,
        mixed $custom_metadata_has = null,
        array $device_ids = null,
        string $device_type = null,
        array $device_types = null,
        array $exclude_if = null,
        array $include_if = null,
        float $limit = null,
        string $manufacturer = null,
        string $user_identifier_key = null
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
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/thermostats/list",
            json: $request_payload,
            inner_object: "thermostats"
        );

        return array_map(fn($r) => Device::from_json($r), $res);
    }

    public function off(
        string $device_id,
        bool $sync = null,
        bool $wait_for_action_attempt = true
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
            json: $request_payload,
            inner_object: "action_attempt"
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt_id
        );

        return $action_attempt;
    }

    public function set_fan_mode(
        string $device_id,
        string $fan_mode = null,
        string $fan_mode_setting = null,
        bool $sync = null,
        bool $wait_for_action_attempt = true
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
            json: $request_payload,
            inner_object: "action_attempt"
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt_id
        );

        return $action_attempt;
    }

    public function update(
        mixed $default_climate_setting,
        string $device_id
    ): void {
        $request_payload = [];

        if ($default_climate_setting !== null) {
            $request_payload[
                "default_climate_setting"
            ] = $default_climate_setting;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->request(
            "POST",
            "/thermostats/update",
            json: $request_payload
        );
    }
}

class UserIdentitiesClient
{
    private SeamClient $seam;
    public UserIdentitiesEnrollmentAutomationsClient $enrollment_automations;
    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
        $this->enrollment_automations = new UserIdentitiesEnrollmentAutomationsClient(
            $seam
        );
    }

    public function add_acs_user(
        string $acs_user_id,
        string $user_identity_id
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
            "/user_identities/add_acs_user",
            json: $request_payload
        );
    }

    public function create(
        string $email_address = null,
        string $full_name = null,
        string $phone_number = null,
        string $user_identity_key = null
    ): UserIdentity {
        $request_payload = [];

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
            json: $request_payload,
            inner_object: "user_identity"
        );

        return UserIdentity::from_json($res);
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
            json: $request_payload
        );
    }

    public function get(
        string $user_identity_id = null,
        string $user_identity_key = null
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
            json: $request_payload,
            inner_object: "user_identity"
        );

        return UserIdentity::from_json($res);
    }

    public function grant_access_to_device(
        string $device_id,
        string $user_identity_id
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
            json: $request_payload
        );
    }

    public function list(string $credential_manager_acs_system_id = null): array
    {
        $request_payload = [];

        if ($credential_manager_acs_system_id !== null) {
            $request_payload[
                "credential_manager_acs_system_id"
            ] = $credential_manager_acs_system_id;
        }

        $res = $this->seam->request(
            "POST",
            "/user_identities/list",
            json: $request_payload,
            inner_object: "user_identities"
        );

        return array_map(fn($r) => UserIdentity::from_json($r), $res);
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
            json: $request_payload,
            inner_object: "devices"
        );

        return array_map(fn($r) => Device::from_json($r), $res);
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
            json: $request_payload,
            inner_object: "acs_systems"
        );

        return array_map(fn($r) => AcsSystem::from_json($r), $res);
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
            json: $request_payload,
            inner_object: "acs_users"
        );

        return array_map(fn($r) => AcsUser::from_json($r), $res);
    }

    public function remove_acs_user(
        string $acs_user_id,
        string $user_identity_id
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
            json: $request_payload
        );
    }

    public function revoke_access_to_device(
        string $device_id,
        string $user_identity_id
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
            json: $request_payload
        );
    }

    public function update(
        string $user_identity_id,
        string $email_address = null,
        string $full_name = null,
        string $phone_number = null,
        string $user_identity_key = null
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
            json: $request_payload
        );
    }
}

class UserIdentitiesEnrollmentAutomationsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function delete(string $enrollment_automation_id): void
    {
        $request_payload = [];

        if ($enrollment_automation_id !== null) {
            $request_payload[
                "enrollment_automation_id"
            ] = $enrollment_automation_id;
        }

        $this->seam->request(
            "POST",
            "/user_identities/enrollment_automations/delete",
            json: $request_payload
        );
    }

    public function get(string $enrollment_automation_id): EnrollmentAutomation
    {
        $request_payload = [];

        if ($enrollment_automation_id !== null) {
            $request_payload[
                "enrollment_automation_id"
            ] = $enrollment_automation_id;
        }

        $res = $this->seam->request(
            "POST",
            "/user_identities/enrollment_automations/get",
            json: $request_payload,
            inner_object: "enrollment_automation"
        );

        return EnrollmentAutomation::from_json($res);
    }

    public function launch(
        string $credential_manager_acs_system_id,
        string $user_identity_id,
        string $acs_credential_pool_id = null,
        bool $create_credential_manager_user = null,
        string $credential_manager_acs_user_id = null
    ): void {
        $request_payload = [];

        if ($credential_manager_acs_system_id !== null) {
            $request_payload[
                "credential_manager_acs_system_id"
            ] = $credential_manager_acs_system_id;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($acs_credential_pool_id !== null) {
            $request_payload[
                "acs_credential_pool_id"
            ] = $acs_credential_pool_id;
        }
        if ($create_credential_manager_user !== null) {
            $request_payload[
                "create_credential_manager_user"
            ] = $create_credential_manager_user;
        }
        if ($credential_manager_acs_user_id !== null) {
            $request_payload[
                "credential_manager_acs_user_id"
            ] = $credential_manager_acs_user_id;
        }

        $this->seam->request(
            "POST",
            "/user_identities/enrollment_automations/launch",
            json: $request_payload,
            inner_object: "enrollment_automation"
        );
    }

    public function list(string $user_identity_id): array
    {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->request(
            "POST",
            "/user_identities/enrollment_automations/list",
            json: $request_payload,
            inner_object: "enrollment_automations"
        );

        return array_map(fn($r) => EnrollmentAutomation::from_json($r), $res);
    }
}

class WebhooksClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function create(string $url, array $event_types = null): Webhook
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
            json: $request_payload,
            inner_object: "webhook"
        );

        return Webhook::from_json($res);
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
            json: $request_payload
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
            json: $request_payload,
            inner_object: "webhook"
        );

        return Webhook::from_json($res);
    }

    public function list(): array
    {
        $request_payload = [];

        $res = $this->seam->request(
            "POST",
            "/webhooks/list",
            json: $request_payload,
            inner_object: "webhooks"
        );

        return array_map(fn($r) => Webhook::from_json($r), $res);
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
            json: $request_payload
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
        string $connect_partner_name,
        string $name,
        bool $is_sandbox = null,
        string $webview_logo_shape = null,
        string $webview_primary_button_color = null
    ): Workspace {
        $request_payload = [];

        if ($connect_partner_name !== null) {
            $request_payload["connect_partner_name"] = $connect_partner_name;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
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

        $res = $this->seam->request(
            "POST",
            "/workspaces/create",
            json: $request_payload,
            inner_object: "workspace"
        );

        return Workspace::from_json($res);
    }

    public function get(): Workspace
    {
        $request_payload = [];

        $res = $this->seam->request(
            "POST",
            "/workspaces/get",
            json: $request_payload,
            inner_object: "workspace"
        );

        return Workspace::from_json($res);
    }

    public function list(): array
    {
        $request_payload = [];

        $res = $this->seam->request(
            "POST",
            "/workspaces/list",
            json: $request_payload,
            inner_object: "workspaces"
        );

        return array_map(fn($r) => Workspace::from_json($r), $res);
    }

    public function reset_sandbox(
        bool $wait_for_action_attempt = true
    ): ActionAttempt {
        $request_payload = [];

        $res = $this->seam->request(
            "POST",
            "/workspaces/reset_sandbox",
            json: $request_payload,
            inner_object: "action_attempt"
        );

        if (!$wait_for_action_attempt) {
            return ActionAttempt::from_json($res);
        }

        $action_attempt = $this->seam->action_attempts->poll_until_ready(
            $res->action_attempt_id
        );

        return $action_attempt;
    }
}
