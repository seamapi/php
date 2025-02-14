<?php

namespace Seam;

use Seam\Objects\AccessCode;
use Seam\Objects\AcsAccessGroup;
use Seam\Objects\AcsCredential;
use Seam\Objects\AcsCredentialPool;
use Seam\Objects\AcsCredentialProvisioningAutomation;
use Seam\Objects\AcsEncoder;
use Seam\Objects\AcsEntrance;
use Seam\Objects\AcsSystem;
use Seam\Objects\AcsUser;
use Seam\Objects\ActionAttempt;
use Seam\Objects\ClientSession;
use Seam\Objects\ConnectWebview;
use Seam\Objects\ConnectedAccount;
use Seam\Objects\Device;
use Seam\Objects\DeviceProvider;
use Seam\Objects\EnrollmentAutomation;
use Seam\Objects\Event;
use Seam\Objects\Network;
use Seam\Objects\NoiseThreshold;
use Seam\Objects\Phone;
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
    public AcsClient $acs;
    public DevicesClient $devices;
    public ThermostatsClient $thermostats;

    public string $api_key;
    public HTTPClient $client;
    public string $ltsVersion = LTS_VERSION;

    public function __construct(
        $api_key = null,
        $endpoint = "https://connect.getseam.com",
        $throw_http_errors = false
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
        $this->acs = new AcsClient($this);
        $this->devices = new DevicesClient($this);
        $this->thermostats = new ThermostatsClient($this);
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

        if ($status_code >= 400) {
            if ($status_code === 401) {
                throw new HttpUnauthorizedError($request_id);
            }

            if (($res_json->error ?? null) != null) {
                if ($res_json->error->type === "invalid_input") {
                    throw new HttpInvalidInputError(
                        $res_json->error,
                        $status_code,
                        $request_id
                    );
                }

                throw new HttpApiError(
                    $res_json->error,
                    $status_code,
                    $request_id
                );
            }

            throw GuzzleHttpExceptionRequestException::create(
                new GuzzleHttpPsr7Request($method, $path),
                $response
            );
        }

        return $inner_object ? $res_json->$inner_object : $res_json;
    }
}

class AcsAccessGroupsUnmanagedClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function get(string $acs_access_group_id): UnmanagedAcsAccessGroup
    {
        $request_payload = [];

        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/access_groups/unmanaged/get",
            json: (object) $request_payload,
            inner_object: "acs_access_group"
        );

        return UnmanagedAcsAccessGroup::from_json($res);
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
            "/acs/access_groups/unmanaged/list",
            json: (object) $request_payload,
            inner_object: "acs_access_groups"
        );

        return array_map(
            fn($r) => UnmanagedAcsAccessGroup::from_json($r),
            $res
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
            json: (object) $request_payload,
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
            json: (object) $request_payload,
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

    public function create_offline_code(
        string $acs_user_id,
        string $allowed_acs_entrance_id,
        string $ends_at = null,
        bool $is_one_time_use = null,
        string $starts_at = null
    ): AcsCredential {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($allowed_acs_entrance_id !== null) {
            $request_payload[
                "allowed_acs_entrance_id"
            ] = $allowed_acs_entrance_id;
        }
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($is_one_time_use !== null) {
            $request_payload["is_one_time_use"] = $is_one_time_use;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/credentials/create_offline_code",
            json: (object) $request_payload,
            inner_object: "acs_credential"
        );

        return AcsCredential::from_json($res);
    }
}

class AcsCredentialsUnmanagedClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function get(string $acs_credential_id): UnmanagedAcsCredential
    {
        $request_payload = [];

        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/credentials/unmanaged/get",
            json: (object) $request_payload,
            inner_object: "acs_credential"
        );

        return UnmanagedAcsCredential::from_json($res);
    }

    public function list(
        string $acs_user_id = null,
        string $acs_system_id = null,
        string $user_identity_id = null
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

        $res = $this->seam->request(
            "POST",
            "/acs/credentials/unmanaged/list",
            json: (object) $request_payload,
            inner_object: "acs_credentials"
        );

        return array_map(fn($r) => UnmanagedAcsCredential::from_json($r), $res);
    }
}

class AcsUsersUnmanagedClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function get(string $acs_user_id): UnmanagedAcsUser
    {
        $request_payload = [];

        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/users/unmanaged/get",
            json: (object) $request_payload,
            inner_object: "acs_user"
        );

        return UnmanagedAcsUser::from_json($res);
    }

    public function list(
        string $acs_system_id = null,
        float $limit = null,
        string $user_identity_email_address = null,
        string $user_identity_id = null,
        string $user_identity_phone_number = null
    ): array {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
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
            "/acs/users/unmanaged/list",
            json: (object) $request_payload,
            inner_object: "acs_users"
        );

        return array_map(fn($r) => UnmanagedAcsUser::from_json($r), $res);
    }
}

class DevicesClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    public function delete(string $device_id): void
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->request(
            "POST",
            "/devices/delete",
            json: (object) $request_payload
        );
    }
}

class ThermostatsClient
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
            "/thermostats/get",
            json: (object) $request_payload,
            inner_object: "thermostat"
        );

        return Device::from_json($res);
    }
}
