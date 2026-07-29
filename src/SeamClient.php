<?php

namespace Seam;

use Seam\Resources\AccessCode;
use Seam\Resources\AccessGrant;
use Seam\Resources\AccessMethod;
use Seam\Resources\AcsAccessGroup;
use Seam\Resources\AcsCredential;
use Seam\Resources\AcsEncoder;
use Seam\Resources\AcsEntrance;
use Seam\Resources\AcsSystem;
use Seam\Resources\AcsUser;
use Seam\Resources\ActionAttempt;
use Seam\Resources\Batch;
use Seam\Resources\ClientSession;
use Seam\Resources\ConnectWebview;
use Seam\Resources\ConnectedAccount;
use Seam\Resources\CustomerPortal;
use Seam\Resources\Device;
use Seam\Resources\DeviceProvider;
use Seam\Resources\Event;
use Seam\Resources\InstantKey;
use Seam\Resources\NoiseThreshold;
use Seam\Resources\Phone;
use Seam\Resources\Space;
use Seam\Resources\ThermostatDailyProgram;
use Seam\Resources\ThermostatSchedule;
use Seam\Resources\UnmanagedAccessCode;
use Seam\Resources\UnmanagedAccessGrant;
use Seam\Resources\UnmanagedAccessMethod;
use Seam\Resources\UnmanagedDevice;
use Seam\Resources\UnmanagedUserIdentity;
use Seam\Resources\UserIdentity;
use Seam\Resources\Webhook;
use Seam\Resources\Workspace;
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

    /**
     * Creates a new [access code](https://docs.seam.co/low-level-apis/access-codes). For granting access, we recommend [Access Grants](https://docs.seam.co/use-cases/granting-access) instead: they work across both standalone smart locks and access control systems and manage the underlying codes for you. Use this low-level endpoint only when you need direct control over a code on a single device, such as setting a custom PIN value.
     *
     * @param string $device_id ID of the device for which you want to create the new access code.
     * @param bool $allow_external_modification Indicates whether [external modification](https://docs.seam.co/low-level-apis/smart-locks/access-codes#external-modification) of the code is allowed. Default: `false`.
     * @param bool $attempt_for_offline_device
     * @param string $code Code to be used for access.
     * @param string $common_code_key Key to identify access codes that should have the same code. Any two access codes with the same `common_code_key` are guaranteed to have the same `code`. See also [Creating and Updating Multiple Linked Access Codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes/creating-and-updating-multiple-linked-access-codes).
     * @param string $ends_at Date and time at which the validity of the new access code ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after `starts_at`.
     * @param bool $is_external_modification_allowed Indicates whether [external modification](https://docs.seam.co/low-level-apis/smart-locks/access-codes#external-modification) of the code is allowed. Default: `false`.
     * @param bool $is_offline_access_code Indicates whether the access code is an [offline access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/offline-access-codes).
     * @param bool $is_one_time_use Indicates whether the [offline access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/offline-access-codes) is a single-use access code.
     * @param string $max_time_rounding Maximum rounding adjustment. To create a daily-bound [offline access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/offline-access-codes) for devices that support this feature, set this parameter to `1d`.
     * @param string $name Name of the new access code. Enables administrators and users to identify the access code easily, especially when there are numerous access codes.

Note that the name provided on Seam is used to identify the code on Seam and is not necessarily the name that will appear in the lock provider's app or on the device. This is because lock providers may have constraints on names, such as length, uniqueness, or characters that can be used. In addition, some lock providers may break down names into components such as `first_name` and `last_name`.

To provide a consistent experience, Seam identifies the code on Seam by its name but may modify the name that appears on the lock provider's app or on the device. For example, Seam may add additional characters or truncate the name to meet provider constraints.

To help your users identify codes set by Seam, Seam provides the name exactly as it appears on the lock provider's app or on the device as a separate property called `appearance`. This is an object with a `name` property and, optionally, `first_name` and `last_name` properties (for providers that break down a name into components).
     * @param bool $prefer_native_scheduling Indicates whether [native scheduling](https://docs.seam.co/low-level-apis/smart-locks/access-codes#native-scheduling) should be used for time-bound codes when supported by the provider. Default: `true`.
     * @param float $preferred_code_length Preferred code length. Only applicable if you do not specify a `code`. If the affected device does not support the preferred code length, Seam reverts to using the shortest supported code length.
     * @param string $starts_at Date and time at which the validity of the new access code starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @param bool $use_backup_access_code_pool Indicates whether to use a [backup access code pool](https://docs.seam.co/low-level-apis/smart-locks/access-codes/backup-access-codes) provided by Seam. If `true`, you can use [`/access_codes/pull_backup_access_code`](https://docs.seam.co/api/access_codes/pull_backup_access_code).
     * @param bool $use_offline_access_code
     * @return AccessCode OK
     */
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

    /**
     * Creates new [access codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes) that share a common code across multiple devices.
     *
     * Users with more than one door lock in a property may want to create groups of linked access codes, all of which have the same code (PIN). For example, a short-term rental host may want to provide guests the same PIN for both a front door lock and a back door lock.
     *
     * If you specify a custom code, Seam assigns this custom code to each of the resulting access codes. However, in this case, Seam does not link these access codes together with a `common_code_key`. That is, `common_code_key` remains null for these access codes.
     *
     * If you want to change these access codes that are not linked by a `common_code_key`, you cannot use `/access_codes/update_multiple`. However, you can update each of these access codes individually, using `/access_codes/update`.
     *
     * See also [Creating and Updating Multiple Linked Access Codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes/creating-and-updating-multiple-linked-access-codes).
     *
     * For granting a person access to a space, [Access Grants](https://docs.seam.co/use-cases/granting-access) are the default and recommended approach and work across both standalone smart locks and access systems. Use the lower-level Access Codes API directly only when you specifically need to manage individual PIN codes.
     *
     * @param array $device_ids IDs of the devices for which you want to create the new access codes.
     * @param bool $allow_external_modification Indicates whether [external modification](https://docs.seam.co/low-level-apis/smart-locks/access-codes#external-modification) of the code is allowed. Default: `false`.
     * @param bool $attempt_for_offline_device
     * @param string $behavior_when_code_cannot_be_shared Desired behavior if any device cannot share a code. If `throw` (default), no access codes will be created if any device cannot share a code. If `create_random_code`, a random code will be created on devices that cannot share a code.
     * @param string $code Code to be used for access.
     * @param string $ends_at Date and time at which the validity of the new access code ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after `starts_at`.
     * @param bool $is_external_modification_allowed Indicates whether [external modification](https://docs.seam.co/low-level-apis/smart-locks/access-codes#external-modification) of the code is allowed. Default: `false`.
     * @param string $name Name of the new access code. Enables administrators and users to identify the access code easily, especially when there are numerous access codes.

Note that the name provided on Seam is used to identify the code on Seam and is not necessarily the name that will appear in the lock provider's app or on the device. This is because lock providers may have constraints on names, such as length, uniqueness, or characters that can be used. In addition, some lock providers may break down names into components such as `first_name` and `last_name`.

To provide a consistent experience, Seam identifies the code on Seam by its name but may modify the name that appears on the lock provider's app or on the device. For example, Seam may add additional characters or truncate the name to meet provider constraints.

To help your users identify codes set by Seam, Seam provides the name exactly as it appears on the lock provider's app or on the device as a separate property called `appearance`. This is an object with a `name` property and, optionally, `first_name` and `last_name` properties (for providers that break down a name into components).
     * @param bool $prefer_native_scheduling Indicates whether [native scheduling](https://docs.seam.co/low-level-apis/smart-locks/access-codes#native-scheduling) should be used for time-bound codes when supported by the provider. Default: `true`.
     * @param float $preferred_code_length Preferred code length. If the affected devices do not support the preferred code length, Seam reverts to using the shortest supported code length.
     * @param string $starts_at Date and time at which the validity of the new access code starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @param bool $use_backup_access_code_pool Indicates whether to use a [backup access code pool](https://docs.seam.co/low-level-apis/smart-locks/access-codes/backup-access-codes) provided by Seam. If `true`, you can use [`/access_codes/pull_backup_access_code`](https://docs.seam.co/api/access_codes/pull_backup_access_code).
     * @return array OK
     */
    public function create_multiple(
        array $device_ids,
        ?bool $allow_external_modification = null,
        ?bool $attempt_for_offline_device = null,
        ?string $behavior_when_code_cannot_be_shared = null,
        ?string $code = null,
        ?string $ends_at = null,
        ?bool $is_external_modification_allowed = null,
        ?string $name = null,
        ?bool $prefer_native_scheduling = null,
        ?float $preferred_code_length = null,
        ?string $starts_at = null,
        ?bool $use_backup_access_code_pool = null,
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

    /**
     * Deletes an [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes).
     *
     * @param string $access_code_id ID of the access code that you want to delete.
     * @param string $device_id ID of the device for which you want to delete the access code.
     * @return void OK
     */
    public function delete(
        string $access_code_id,
        ?string $device_id = null,
    ): void {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->seam->request(
            "POST",
            "/access_codes/delete",
            json: (object) $request_payload,
        );
    }

    /**
     * Generates a code for an [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes), given a device ID.
     *
     * @param string $device_id ID of the device for which you want to generate a code.
     * @return AccessCode OK
     */
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

    /**
     * Returns a specified [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes).
     *
     * You must specify either `access_code_id` or both `device_id` and `code`.
     *
     * @param string $access_code_id ID of the access code that you want to get. You must specify either `access_code_id` or both `device_id` and `code`.
     * @param string $code Code of the access code that you want to get. You must specify either `access_code_id` or both `device_id` and `code`.
     * @param string $device_id ID of the device containing the access code that you want to get. You must specify either `access_code_id` or both `device_id` and `code`.
     * @return AccessCode OK
     */
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

    /**
     * Returns a list of all [access codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes).
     *
     * Specify `device_id`, `access_code_ids`, `access_method_id`, `access_grant_id`, or `access_grant_key`.
     *
     * @param array $access_code_ids IDs of the access codes that you want to retrieve. Specify `device_id`, `access_code_ids`, `access_method_id`, `access_grant_id`, or `access_grant_key`.
     * @param string $access_grant_id ID of the access grant for which you want to list access codes. Specify `device_id`, `access_code_ids`, `access_method_id`, `access_grant_id`, or `access_grant_key`.
     * @param string $access_grant_key Key of the access grant for which you want to list access codes. Specify `device_id`, `access_code_ids`, `access_method_id`, `access_grant_id`, or `access_grant_key`.
     * @param string $access_method_id ID of the access method for which you want to list access codes. Specify `device_id`, `access_code_ids`, `access_method_id`, `access_grant_id`, or `access_grant_key`.
     * @param string $customer_key Customer key for which you want to list access codes.
     * @param string $device_id ID of the device for which you want to list access codes. Specify `device_id`, `access_code_ids`, `access_method_id`, `access_grant_id`, or `access_grant_key`.
     * @param float $limit Numerical limit on the number of access codes to return.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned access codes to include all records that satisfy a partial match using `name`, `code` or `access_code_id`.
     * @param string $user_identifier_key Your user ID for the user by which to filter access codes.
     * @return array OK
     */
    public function list(
        ?array $access_code_ids = null,
        ?string $access_grant_id = null,
        ?string $access_grant_key = null,
        ?string $access_method_id = null,
        ?string $customer_key = null,
        ?string $device_id = null,
        ?float $limit = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?string $user_identifier_key = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($access_code_ids !== null) {
            $request_payload["access_code_ids"] = $access_code_ids;
        }
        if ($access_grant_id !== null) {
            $request_payload["access_grant_id"] = $access_grant_id;
        }
        if ($access_grant_key !== null) {
            $request_payload["access_grant_key"] = $access_grant_key;
        }
        if ($access_method_id !== null) {
            $request_payload["access_method_id"] = $access_method_id;
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
        if ($search !== null) {
            $request_payload["search"] = $search;
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

    /**
     * Retrieves a backup access code for an [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes). See also [Managing Backup Access Codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes/backup-access-codes).
     *
     * A backup access code pool is a collection of pre-programmed access codes stored on a device, ready for use. These codes are programmed in addition to the regular access codes on Seam, serving as a safety net for any issues with the primary codes. If there's ever a complication with a primary access code—be it due to intermittent connectivity, manual removal from a device, or provider outages—a backup code can be retrieved. Its end time can then be adjusted to align with the original code, facilitating seamless and uninterrupted access.
     *
     * You can pull a backup access code from the pool at any time. These backup codes are guaranteed to work immediately and automatically programmed to be removed from the device after the access code ends.
     *
     * You can only pull backup access codes for time-bound access codes.
     *
     * Before pulling a backup access code, make sure that the device's `properties.supports_backup_access_code_pool` is `true`. Then, to activate the backup pool, set `use_backup_access_code_pool` to `true` when creating an access code.
     *
     * @param string $access_code_id ID of the access code for which you want to pull a backup access code.
     * @return AccessCode OK
     */
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

    /**
     * Enables you to report access code-related constraints for a device. Currently, supports reporting supported code length constraints for SmartThings devices.
     *
     * Specify either `supported_code_lengths` or `min_code_length`/`max_code_length`.
     *
     * @param string $device_id ID of the device for which you want to report constraints.
     * @param int $max_code_length Maximum supported code length as an integer between 4 and 20, inclusive. You can specify either `min_code_length`/`max_code_length` or `supported_code_lengths`.
     * @param int $min_code_length Minimum supported code length as an integer between 4 and 20, inclusive. You can specify either `min_code_length`/`max_code_length` or `supported_code_lengths`.
     * @param array $supported_code_lengths Array of supported code lengths as integers between 4 and 20, inclusive. You can specify either `supported_code_lengths` or `min_code_length`/`max_code_length`.
     * @return void OK
     */
    public function report_device_constraints(
        string $device_id,
        ?int $max_code_length = null,
        ?int $min_code_length = null,
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

    /**
     * Updates a specified active or upcoming [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes).
     *
     * See also [Modifying Access Codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes/modifying-access-codes).
     *
     * @param string $access_code_id ID of the access code that you want to update.
     * @param bool $allow_external_modification Indicates whether [external modification](https://docs.seam.co/low-level-apis/smart-locks/access-codes#external-modification) of the code is allowed. Default: `false`.
     * @param bool $attempt_for_offline_device
     * @param string $code Code to be used for access.
     * @param string $device_id ID of the device containing the access code that you want to update.
     * @param string $ends_at Date and time at which the validity of the new access code ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after `starts_at`.
     * @param bool $is_external_modification_allowed Indicates whether [external modification](https://docs.seam.co/low-level-apis/smart-locks/access-codes#external-modification) of the code is allowed. Default: `false`.
     * @param bool $is_managed Indicates whether the access code is managed through Seam. Note that to convert an unmanaged access code into a managed access code, use `/access_codes/unmanaged/convert_to_managed`.
     * @param bool $is_offline_access_code Indicates whether the access code is an [offline access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/offline-access-codes).
     * @param bool $is_one_time_use Indicates whether the [offline access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/offline-access-codes) is a single-use access code.
     * @param string $max_time_rounding Maximum rounding adjustment. To create a daily-bound [offline access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/offline-access-codes) for devices that support this feature, set this parameter to `1d`.
     * @param string $name Name of the new access code. Enables administrators and users to identify the access code easily, especially when there are numerous access codes.

Note that the name provided on Seam is used to identify the code on Seam and is not necessarily the name that will appear in the lock provider's app or on the device. This is because lock providers may have constraints on names, such as length, uniqueness, or characters that can be used. In addition, some lock providers may break down names into components such as `first_name` and `last_name`.

To provide a consistent experience, Seam identifies the code on Seam by its name but may modify the name that appears on the lock provider's app or on the device. For example, Seam may add additional characters or truncate the name to meet provider constraints.

To help your users identify codes set by Seam, Seam provides the name exactly as it appears on the lock provider's app or on the device as a separate property called `appearance`. This is an object with a `name` property and, optionally, `first_name` and `last_name` properties (for providers that break down a name into components).
     * @param bool $prefer_native_scheduling Indicates whether [native scheduling](https://docs.seam.co/low-level-apis/smart-locks/access-codes#native-scheduling) should be used for time-bound codes when supported by the provider. Default: `true`.
     * @param float $preferred_code_length Preferred code length. Only applicable if you do not specify a `code`. If the affected device does not support the preferred code length, Seam reverts to using the shortest supported code length.
     * @param string $starts_at Date and time at which the validity of the new access code starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @param string $type Type to which you want to convert the access code. To convert a time-bound access code to an ongoing access code, set `type` to `ongoing`. See also [Changing a time-bound access code to permanent access](https://docs.seam.co/low-level-apis/smart-locks/access-codes/modifying-access-codes#special-case-2-changing-a-time-bound-access-code-to-permanent-access).
     * @param bool $use_backup_access_code_pool Indicates whether to use a [backup access code pool](https://docs.seam.co/low-level-apis/smart-locks/access-codes/backup-access-codes) provided by Seam. If `true`, you can use [`/access_codes/pull_backup_access_code`](https://docs.seam.co/api/access_codes/pull_backup_access_code).
     * @param bool $use_offline_access_code
     * @return void OK
     */
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

    /**
     * Updates [access codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes) that share a common code across multiple devices.
     *
     * Specify the `common_code_key` to identify the set of access codes that you want to update.
     *
     * See also [Update Linked Access Codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes/creating-and-updating-multiple-linked-access-codes#update-linked-access-codes).
     *
     * @param string $common_code_key Key that links the group of access codes, assigned on creation by `/access_codes/create_multiple`.
     * @param string $ends_at Date and time at which the validity of the new access code ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after `starts_at`.
     * @param string $name Name of the new access code. Enables administrators and users to identify the access code easily, especially when there are numerous access codes.

Note that the name provided on Seam is used to identify the code on Seam and is not necessarily the name that will appear in the lock provider's app or on the device. This is because lock providers may have constraints on names, such as length, uniqueness, or characters that can be used. In addition, some lock providers may break down names into components such as `first_name` and `last_name`.

To provide a consistent experience, Seam identifies the code on Seam by its name but may modify the name that appears on the lock provider's app or on the device. For example, Seam may add additional characters or truncate the name to meet provider constraints.

To help your users identify codes set by Seam, Seam provides the name exactly as it appears on the lock provider's app or on the device as a separate property called `appearance`. This is an object with a `name` property and, optionally, `first_name` and `last_name` properties (for providers that break down a name into components).
     * @param string $starts_at Date and time at which the validity of the new access code starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @return void OK
     */
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

    /**
     * Simulates the creation of an [unmanaged access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes) in a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $code Code of the simulated unmanaged access code.
     * @param string $device_id ID of the device for which you want to simulate the creation of an unmanaged access code.
     * @param string $name Name of the simulated unmanaged access code.
     * @return UnmanagedAccessCode OK
     */
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

    /**
     * Converts an [unmanaged access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes) to an [access code managed through Seam](https://docs.seam.co/low-level-apis/smart-locks/access-codes).
     *
     * An unmanaged access code has a limited set of operations that you can perform on it. Once you convert an unmanaged access code to a managed access code, the full set of access code operations and lifecycle events becomes available for it.
     *
     * Note that not all device providers support converting an unmanaged access code to a managed access code.
     *
     * @param string $access_code_id ID of the unmanaged access code that you want to convert to a managed access code.
     * @param bool $allow_external_modification Indicates whether [external modification](https://docs.seam.co/low-level-apis/smart-locks/access-codes#external-modification) of the access code is allowed.
     * @param bool $force Indicates whether to force the access code conversion. To switch management of an access code from one Seam workspace to another, set `force` to `true`.
     * @param bool $is_external_modification_allowed Indicates whether [external modification](https://docs.seam.co/low-level-apis/smart-locks/access-codes#external-modification) of the access code is allowed.
     * @return void OK
     */
    public function convert_to_managed(
        string $access_code_id,
        ?bool $allow_external_modification = null,
        ?bool $force = null,
        ?bool $is_external_modification_allowed = null,
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

        $this->seam->request(
            "POST",
            "/access_codes/unmanaged/convert_to_managed",
            json: (object) $request_payload,
        );
    }

    /**
     * Deletes an [unmanaged access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes).
     *
     * @param string $access_code_id ID of the unmanaged access code that you want to delete.
     * @return void OK
     */
    public function delete(string $access_code_id): void
    {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }

        $this->seam->request(
            "POST",
            "/access_codes/unmanaged/delete",
            json: (object) $request_payload,
        );
    }

    /**
     * Returns a specified [unmanaged access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes).
     *
     * You must specify either `access_code_id` or both `device_id` and `code`.
     *
     * @param string $access_code_id ID of the unmanaged access code that you want to get. You must specify either `access_code_id` or both `device_id` and `code`.
     * @param string $code Code of the unmanaged access code that you want to get. You must specify either `access_code_id` or both `device_id` and `code`.
     * @param string $device_id ID of the device containing the unmanaged access code that you want to get. You must specify either `access_code_id` or both `device_id` and `code`.
     * @return UnmanagedAccessCode OK
     */
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

    /**
     * Returns a list of all [unmanaged access codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes).
     *
     * @param string $device_id ID of the device for which you want to list unmanaged access codes.
     * @param float $limit Numerical limit on the number of unmanaged access codes to return.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned access codes to include all records that satisfy a partial match using `name`, `code` or `access_code_id`.
     * @param string $user_identifier_key Your user ID for the user by which to filter unmanaged access codes.
     * @return array OK
     */
    public function list(
        string $device_id,
        ?float $limit = null,
        ?string $page_cursor = null,
        ?string $search = null,
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
        if ($search !== null) {
            $request_payload["search"] = $search;
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

    /**
     * Updates a specified [unmanaged access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes).
     *
     * @param string $access_code_id ID of the unmanaged access code that you want to update.
     * @param bool $is_managed
     * @param bool $allow_external_modification Indicates whether [external modification](https://docs.seam.co/low-level-apis/smart-locks/access-codes#external-modification) of the code is allowed.
     * @param bool $force Indicates whether to force the unmanaged access code update.
     * @param bool $is_external_modification_allowed Indicates whether [external modification](https://docs.seam.co/low-level-apis/smart-locks/access-codes#external-modification) of the code is allowed.
     * @return void OK
     */
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
    public AccessGrantsUnmanagedClient $unmanaged;
    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
        $this->unmanaged = new AccessGrantsUnmanagedClient($seam);
    }

    /**
     * Creates a new [Access Grant](https://docs.seam.co/use-cases/granting-access/access-grants). Access Grants are the default and recommended way to grant a user access to any physical space, irrespective of the locking hardware. They work with both standalone smart locks (using `device_ids`) and access control systems (using `acs_entrance_ids` or `space_ids`), and can issue PIN codes, key cards, and mobile keys through a single request.
     *
     * @param array $requested_access_methods
     * @param string $user_identity_id ID of user identity for whom access is being granted.
     * @param mixed $user_identity When used, creates a new user identity with the given details, and grants them access.
     * @param string $access_grant_key Unique key for the access grant within the workspace.
     * @param array $acs_entrance_ids Set of IDs of the [entrances](https://docs.seam.co/api/acs/systems/list) to which access is being granted.
     * @param string $customization_profile_id ID of the customization profile to apply to the Access Grant and its access methods.
     * @param array $device_ids Set of IDs of the [devices](https://docs.seam.co/api/devices/list) to which access is being granted.
     * @param string $ends_at Date and time at which the validity of the new grant ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after `starts_at`.
     * @param mixed $location
     * @param array $location_ids
     * @param string $name Name for the access grant.
     * @param string $reservation_key Reservation key for the access grant.
     * @param array $space_ids Set of IDs of existing spaces to which access is being granted.
     * @param array $space_keys Set of keys of existing spaces to which access is being granted.
     * @param string $starts_at Date and time at which the validity of the new grant starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @return AccessGrant OK
     */
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
        ?string $reservation_key = null,
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
        if ($reservation_key !== null) {
            $request_payload["reservation_key"] = $reservation_key;
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

    /**
     * Delete an Access Grant.
     *
     * @param string $access_grant_id ID of Access Grant to delete.
     * @return void OK
     */
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

    /**
     * Get an Access Grant.
     *
     * @param string $access_grant_id ID of Access Grant to get.
     * @param string $access_grant_key Unique key of Access Grant to get.
     * @return AccessGrant OK
     */
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

    /**
     * Gets all related resources for one or more Access Grants.
     *
     * @param array $access_grant_ids IDs of the access grants that you want to get along with their related resources.
     * @param array $access_grant_keys Keys of the access grants that you want to get along with their related resources.
     * @param array $exclude
     * @param array $include
     * @return Batch OK
     */
    public function get_related(
        ?array $access_grant_ids = null,
        ?array $access_grant_keys = null,
        ?array $exclude = null,
        ?array $include = null,
    ): Batch {
        $request_payload = [];

        if ($access_grant_ids !== null) {
            $request_payload["access_grant_ids"] = $access_grant_ids;
        }
        if ($access_grant_keys !== null) {
            $request_payload["access_grant_keys"] = $access_grant_keys;
        }
        if ($exclude !== null) {
            $request_payload["exclude"] = $exclude;
        }
        if ($include !== null) {
            $request_payload["include"] = $include;
        }

        $res = $this->seam->request(
            "POST",
            "/access_grants/get_related",
            json: (object) $request_payload,
        );

        return Batch::from_json($res->batch);
    }

    /**
     * Gets an Access Grant.
     *
     * @param string $access_code_id ID of the access code by which you want to filter the list of Access Grants.
     * @param array $access_grant_ids IDs of the access grants to retrieve.
     * @param string $access_grant_key Filter Access Grants by access_grant_key. Use null to filter for Access Grants without an access_grant_key.
     * @param string $acs_entrance_id ID of the entrance by which you want to filter the list of Access Grants.
     * @param string $acs_system_id ID of the access system by which you want to filter the list of Access Grants.
     * @param string $customer_key Customer key for which you want to list access grants.
     * @param string $device_id ID of the device by which you want to filter the list of Access Grants.
     * @param float $limit Numerical limit on the number of access grants to return.
     * @param string $location_id
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $reservation_key Filter Access Grants by reservation_key.
     * @param string $space_id ID of the space by which you want to filter the list of Access Grants.
     * @param string $user_identity_id ID of user identity by which you want to filter the list of Access Grants.
     * @return array OK
     */
    public function list(
        ?string $access_code_id = null,
        ?array $access_grant_ids = null,
        ?string $access_grant_key = null,
        ?string $acs_entrance_id = null,
        ?string $acs_system_id = null,
        ?string $customer_key = null,
        ?string $device_id = null,
        ?float $limit = null,
        ?string $location_id = null,
        ?string $page_cursor = null,
        ?string $reservation_key = null,
        ?string $space_id = null,
        ?string $user_identity_id = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($access_grant_ids !== null) {
            $request_payload["access_grant_ids"] = $access_grant_ids;
        }
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
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
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
        if ($reservation_key !== null) {
            $request_payload["reservation_key"] = $reservation_key;
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

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => AccessGrant::from_json($r),
            $res->access_grants,
        );
    }

    /**
     * Adds additional requested access methods to an existing Access Grant.
     *
     * @param string $access_grant_id ID of the Access Grant to add access methods to.
     * @param array $requested_access_methods Array of requested access methods to add to the access grant.
     * @return AccessGrant OK
     */
    public function request_access_methods(
        string $access_grant_id,
        array $requested_access_methods,
    ): AccessGrant {
        $request_payload = [];

        if ($access_grant_id !== null) {
            $request_payload["access_grant_id"] = $access_grant_id;
        }
        if ($requested_access_methods !== null) {
            $request_payload[
                "requested_access_methods"
            ] = $requested_access_methods;
        }

        $res = $this->seam->request(
            "POST",
            "/access_grants/request_access_methods",
            json: (object) $request_payload,
        );

        return AccessGrant::from_json($res->access_grant);
    }

    /**
     * Updates an existing Access Grant's time window.
     *
     * @param string $access_grant_id ID of the Access Grant to update. Provide either `access_grant_id` or `access_grant_key`.
     * @param string $access_grant_key Key of the Access Grant to update. Provide either `access_grant_id` or `access_grant_key`.
     * @param string $ends_at Date and time at which the validity of the grant ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after `starts_at`.
     * @param string $name Display name for the access grant.
     * @param string $starts_at Date and time at which the validity of the grant starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @return void OK
     */
    public function update(
        ?string $access_grant_id = null,
        ?string $access_grant_key = null,
        ?string $ends_at = null,
        ?string $name = null,
        ?string $starts_at = null,
    ): void {
        $request_payload = [];

        if ($access_grant_id !== null) {
            $request_payload["access_grant_id"] = $access_grant_id;
        }
        if ($access_grant_key !== null) {
            $request_payload["access_grant_key"] = $access_grant_key;
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

class AccessGrantsUnmanagedClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    /**
     * Get an unmanaged Access Grant (where is_managed = false).
     *
     * @param string $access_grant_id ID of unmanaged Access Grant to get.
     * @return UnmanagedAccessGrant OK
     */
    public function get(string $access_grant_id): UnmanagedAccessGrant
    {
        $request_payload = [];

        if ($access_grant_id !== null) {
            $request_payload["access_grant_id"] = $access_grant_id;
        }

        $res = $this->seam->request(
            "POST",
            "/access_grants/unmanaged/get",
            json: (object) $request_payload,
        );

        return UnmanagedAccessGrant::from_json($res->access_grant);
    }

    /**
     * Gets unmanaged Access Grants (where is_managed = false).
     *
     * @param string $acs_entrance_id ID of the entrance by which you want to filter the list of unmanaged Access Grants.
     * @param string $acs_system_id ID of the access system by which you want to filter the list of unmanaged Access Grants.
     * @param float $limit Numerical limit on the number of unmanaged access grants to return.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $reservation_key Filter unmanaged Access Grants by reservation_key.
     * @param string $user_identity_id ID of user identity by which you want to filter the list of unmanaged Access Grants.
     * @return array OK
     */
    public function list(
        ?string $acs_entrance_id = null,
        ?string $acs_system_id = null,
        ?float $limit = null,
        ?string $page_cursor = null,
        ?string $reservation_key = null,
        ?string $user_identity_id = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($acs_entrance_id !== null) {
            $request_payload["acs_entrance_id"] = $acs_entrance_id;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($reservation_key !== null) {
            $request_payload["reservation_key"] = $reservation_key;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->request(
            "POST",
            "/access_grants/unmanaged/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => UnmanagedAccessGrant::from_json($r),
            $res->access_grants,
        );
    }

    /**
     * Updates an unmanaged Access Grant to make it managed.
     *
     * This endpoint can only be used to convert unmanaged access grants to managed ones by setting `is_managed` to `true`. It cannot be used to convert managed access grants back to unmanaged.
     *
     * When converting an unmanaged access grant to managed, all associated access methods will also be converted to managed.
     *
     * @param string $access_grant_id ID of the unmanaged Access Grant to update.
     * @param bool $is_managed Must be set to true to convert the unmanaged access grant to managed.
     * @param string $access_grant_key Unique key for the access grant. If not provided, the existing key will be preserved.
     * @return void OK
     */
    public function update(
        string $access_grant_id,
        bool $is_managed,
        ?string $access_grant_key = null,
    ): void {
        $request_payload = [];

        if ($access_grant_id !== null) {
            $request_payload["access_grant_id"] = $access_grant_id;
        }
        if ($is_managed !== null) {
            $request_payload["is_managed"] = $is_managed;
        }
        if ($access_grant_key !== null) {
            $request_payload["access_grant_key"] = $access_grant_key;
        }

        $this->seam->request(
            "POST",
            "/access_grants/unmanaged/update",
            json: (object) $request_payload,
        );
    }
}

class AccessMethodsClient
{
    private SeamClient $seam;
    public AccessMethodsUnmanagedClient $unmanaged;
    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
        $this->unmanaged = new AccessMethodsUnmanagedClient($seam);
    }

    /**
     * Assigns a pre-registered card credential, identified by `card_number`, to a card-mode access method. Use this endpoint for access systems that use pre-registered cards, where a physical card must be associated with an access method before it can be used for access. Assigning a card credential also triggers issuance of the access method.
     *
     * @param string $access_method_id ID of the `access_method` to assign the credential to.
     * @param string $card_number Card number of the credential to assign.
     * @return ActionAttempt OK
     */
    public function assign_card(
        string $access_method_id,
        string $card_number,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($access_method_id !== null) {
            $request_payload["access_method_id"] = $access_method_id;
        }
        if ($card_number !== null) {
            $request_payload["card_number"] = $card_number;
        }

        $res = $this->seam->request(
            "POST",
            "/access_methods/assign_card",
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

    /**
     * Deletes an access method.
     *
     * @param string $access_method_id ID of access method to delete.
     * @param string $access_grant_id ID of access grant whose access methods should be deleted.
     * @param string $reservation_key Reservation key of the access grant whose access methods should be deleted.
     * @return void OK
     */
    public function delete(
        ?string $access_method_id = null,
        ?string $access_grant_id = null,
        ?string $reservation_key = null,
    ): void {
        $request_payload = [];

        if ($access_method_id !== null) {
            $request_payload["access_method_id"] = $access_method_id;
        }
        if ($access_grant_id !== null) {
            $request_payload["access_grant_id"] = $access_grant_id;
        }
        if ($reservation_key !== null) {
            $request_payload["reservation_key"] = $reservation_key;
        }

        $this->seam->request(
            "POST",
            "/access_methods/delete",
            json: (object) $request_payload,
        );
    }

    /**
     * Encodes an existing access method onto a plastic card placed on the specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners).
     *
     * @param string $access_method_id ID of the `access_method` to encode onto a card.
     * @param string $acs_encoder_id ID of the `acs_encoder` to use to encode the `access_method`.
     * @return ActionAttempt OK
     */
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

    /**
     * Gets an access method.
     *
     * @param string $access_method_id ID of access method to get.
     * @return AccessMethod OK
     */
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

    /**
     * Gets all related resources for one or more Access Methods.
     *
     * @param array $access_method_ids IDs of the access methods that you want to get along with their related resources.
     * @param array $exclude
     * @param array $include
     * @return Batch OK
     */
    public function get_related(
        array $access_method_ids,
        ?array $exclude = null,
        ?array $include = null,
    ): Batch {
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

        $res = $this->seam->request(
            "POST",
            "/access_methods/get_related",
            json: (object) $request_payload,
        );

        return Batch::from_json($res->batch);
    }

    /**
     * Lists all access methods, usually filtered by Access Grant.
     *
     * @param string $access_code_id ID of the access code by which to filter the returned access methods. Must be combined with `access_grant_id`, `access_grant_key`, or `acs_entrance_id`.
     * @param string $access_grant_id ID of Access Grant to list access methods for.
     * @param string $access_grant_key Key of Access Grant to list access methods for.
     * @param string $acs_entrance_id ID of the entrance for which you want to retrieve all access methods that grant access to it.
     * @param string $device_id ID of the device by which to filter the returned access methods. Must be combined with `access_grant_id`, `access_grant_key`, or `acs_entrance_id`.
     * @param int $limit Maximum number of records to return per page.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $space_id ID of the space by which to filter the returned access methods. Must be combined with `access_grant_id`, `access_grant_key`, or `acs_entrance_id`.
     * @return array OK
     */
    public function list(
        ?string $access_code_id = null,
        ?string $access_grant_id = null,
        ?string $access_grant_key = null,
        ?string $acs_entrance_id = null,
        ?string $device_id = null,
        ?int $limit = null,
        ?string $page_cursor = null,
        ?string $space_id = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($access_grant_id !== null) {
            $request_payload["access_grant_id"] = $access_grant_id;
        }
        if ($access_grant_key !== null) {
            $request_payload["access_grant_key"] = $access_grant_key;
        }
        if ($acs_entrance_id !== null) {
            $request_payload["acs_entrance_id"] = $acs_entrance_id;
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
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }

        $res = $this->seam->request(
            "POST",
            "/access_methods/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => AccessMethod::from_json($r),
            $res->access_methods,
        );
    }

    /**
     * Remotely unlocks a specified [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) using the cloud key credential associated with an access method. Returns an action attempt that tracks the progress of the unlock operation.
     *
     * @param string $access_method_id ID of the cloud_key `access_method` to use for the unlock operation.
     * @param string $acs_entrance_id ID of the entrance to unlock.
     * @return ActionAttempt OK
     */
    public function unlock_door(
        string $access_method_id,
        string $acs_entrance_id,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($access_method_id !== null) {
            $request_payload["access_method_id"] = $access_method_id;
        }
        if ($acs_entrance_id !== null) {
            $request_payload["acs_entrance_id"] = $acs_entrance_id;
        }

        $res = $this->seam->request(
            "POST",
            "/access_methods/unlock_door",
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

class AccessMethodsUnmanagedClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    /**
     * Gets an unmanaged access method (where is_managed = false).
     *
     * @param string $access_method_id ID of unmanaged access method to get.
     * @return UnmanagedAccessMethod OK
     */
    public function get(string $access_method_id): UnmanagedAccessMethod
    {
        $request_payload = [];

        if ($access_method_id !== null) {
            $request_payload["access_method_id"] = $access_method_id;
        }

        $res = $this->seam->request(
            "POST",
            "/access_methods/unmanaged/get",
            json: (object) $request_payload,
        );

        return UnmanagedAccessMethod::from_json($res->access_method);
    }

    /**
     * Lists all unmanaged access methods (where is_managed = false), usually filtered by Access Grant.
     *
     * @param string $access_grant_id ID of Access Grant to list unmanaged access methods for.
     * @param string $acs_entrance_id ID of the entrance for which you want to retrieve all unmanaged access methods.
     * @param string $device_id ID of the device for which you want to retrieve all unmanaged access methods.
     * @param string $space_id ID of the space for which you want to retrieve all unmanaged access methods.
     * @return array OK
     */
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
            "/access_methods/unmanaged/list",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => UnmanagedAccessMethod::from_json($r),
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

    /**
     * Adds a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) to a specified [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_access_group_id ID of the access group to which you want to add an access system user.
     * @param string $acs_user_id ID of the access system user that you want to add to an access group. You can only provide one of acs_user_id or user_identity_id.
     * @param string $user_identity_id ID of the desired user identity that you want to add to an access group. You can only provide one of acs_user_id or user_identity_id. If the ACS system contains an ACS user with the same `email_address` or `phone_number` as the user identity that you specify, they are linked, and the access group membership belongs to the ACS user. If the ACS system does not have a corresponding ACS user, one is created.
     * @return void OK
     */
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

    /**
     * Deletes a specified [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_access_group_id ID of the access group that you want to delete.
     * @return void OK
     */
    public function delete(string $acs_access_group_id): void
    {
        $request_payload = [];

        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }

        $this->seam->request(
            "POST",
            "/acs/access_groups/delete",
            json: (object) $request_payload,
        );
    }

    /**
     * Returns a specified [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_access_group_id ID of the access group that you want to get.
     * @return AcsAccessGroup OK
     */
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

    /**
     * Returns a list of all [access groups](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_system_id ID of the access system for which you want to retrieve all access groups.
     * @param string $acs_user_id ID of the access system user for which you want to retrieve all access groups.
     * @param string $search String for which to search. Filters returned access groups to include all records that satisfy a partial match using `name` or `acs_access_group_id`.
     * @param string $user_identity_id ID of the user identity for which you want to retrieve all access groups.
     * @return array OK
     */
    public function list(
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?string $search = null,
        ?string $user_identity_id = null,
    ): array {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
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

    /**
     * Returns a list of all accessible entrances for a specified [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_access_group_id ID of the access group for which you want to retrieve all accessible entrances.
     * @return array OK
     */
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

    /**
     * Returns a list of all [access system users](https://docs.seam.co/low-level-apis/access-systems/user-management) in an [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_access_group_id ID of the access group for which you want to retrieve all access system users.
     * @return array OK
     */
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

    /**
     * Removes a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) from a specified [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_access_group_id ID of the access group from which you want to remove an access system user.
     * @param string $acs_user_id ID of the access system user that you want to remove from an access group.
     * @param string $user_identity_id ID of the user identity associated with the user that you want to remove from an access group.
     * @return void OK
     */
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

    /**
     * Assigns a specified [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) to a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     *
     * @param string $acs_credential_id ID of the credential that you want to assign to an access system user.
     * @param string $acs_user_id ID of the access system user to whom you want to assign a credential. You can only provide one of acs_user_id or user_identity_id.
     * @param string $user_identity_id ID of the user identity to whom you want to assign a credential. You can only provide one of acs_user_id or user_identity_id. If the ACS system contains an ACS user with the same `email_address` or `phone_number` as the user identity that you specify, they are linked, and the credential belongs to the ACS user. If the ACS system does not have a corresponding ACS user, one is created.
     * @return void OK
     */
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

    /**
     * Creates a new [credential](https://docs.seam.co/low-level-apis/managing-credentials) for a specified [ACS user](https://docs.seam.co/low-level-apis/access-systems/user-management). For granting access, we recommend [Access Grants](https://docs.seam.co/use-cases/granting-access) instead: they create and manage the underlying credentials for you, across access systems and standalone smart locks alike. Use this low-level endpoint only when you need direct control over an individual ACS credential.
     *
     * @param string $access_method Access method for the new credential. Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
     * @param string $acs_system_id ID of the access system to which the new credential belongs. You must provide either `acs_user_id` or the combination of `user_identity_id` and `acs_system_id`.
     * @param string $acs_user_id ID of the access system user to whom the new credential belongs. You must provide either `acs_user_id` or the combination of `user_identity_id` and `acs_system_id`.
     * @param array $allowed_acs_entrance_ids Set of IDs of the [entrances](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) for which the new credential grants access.
     * @param mixed $assa_abloy_vostio_metadata Vostio-specific metadata for the new credential.
     * @param string $code Access (PIN) code for the new credential. There may be manufacturer-specific code restrictions. For details, see the applicable [device or system integration guide](https://docs.seam.co/device-and-system-integration-guides).
     * @param string $credential_manager_acs_system_id ACS system ID of the credential manager for the new credential.
     * @param string $ends_at Date and time at which the validity of the new credential ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after `starts_at`.
     * @param bool $is_multi_phone_sync_credential Indicates whether the new credential is a [multi-phone sync credential](https://docs.seam.co/capability-guides/mobile-access/issuing-mobile-credentials-from-an-access-control-system#what-are-multi-phone-sync-credentials).
     * @param mixed $salto_space_metadata Salto Space-specific metadata for the new credential.
     * @param string $starts_at Date and time at which the validity of the new credential starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @param string $user_identity_id ID of the user identity to whom the new credential belongs. You must provide either `acs_user_id` or the combination of `user_identity_id` and `acs_system_id`. If the access system contains a user with the same `email_address` or `phone_number` as the user identity that you specify, they are linked, and the credential belongs to the access system user. If the access system does not have a corresponding user, one is created.
     * @param mixed $visionline_metadata Visionline-specific metadata for the new credential.
     * @return AcsCredential OK
     */
    public function create(
        string $access_method,
        ?string $acs_system_id = null,
        ?string $acs_user_id = null,
        ?array $allowed_acs_entrance_ids = null,
        mixed $assa_abloy_vostio_metadata = null,
        ?string $code = null,
        ?string $credential_manager_acs_system_id = null,
        ?string $ends_at = null,
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

    /**
     * Deletes a specified [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     *
     * @param string $acs_credential_id ID of the credential that you want to delete.
     * @return void OK
     */
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

    /**
     * Returns a specified [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     *
     * @param string $acs_credential_id ID of the credential that you want to get.
     * @return AcsCredential OK
     */
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

    /**
     * Returns a list of all [credentials](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     *
     * @param string $acs_user_id ID of the access system user for which you want to retrieve all credentials.
     * @param string $acs_system_id ID of the access system for which you want to retrieve all credentials.
     * @param string $user_identity_id ID of the user identity for which you want to retrieve all credentials.
     * @param string $created_before Date and time, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format, before which events to return were created.
     * @param bool $is_multi_phone_sync_credential Indicates whether you want to retrieve only multi-phone sync credentials or non-multi-phone sync credentials.
     * @param float $limit Number of credentials to return.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned credentials to include all records that satisfy a partial match using `display_name`, `code`, `card_number`, `acs_user_id` or `acs_credential_id`.
     * @return array OK
     */
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

    /**
     * Returns a list of all [entrances](https://docs.seam.co/api/acs/entrances) to which a [credential](https://docs.seam.co/api/acs/credentials) grants access.
     *
     * @param string $acs_credential_id ID of the credential for which you want to retrieve all entrances to which the credential grants access.
     * @return array OK
     */
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

    /**
     * Unassigns a specified [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) from a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     *
     * @param string $acs_credential_id ID of the credential that you want to unassign from an access system user.
     * @param string $acs_user_id ID of the access system user from which you want to unassign a credential. You can only provide one of acs_user_id or user_identity_id.
     * @param string $user_identity_id ID of the user identity from which you want to unassign a credential. You can only provide one of acs_user_id or user_identity_id.
     * @return void OK
     */
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

    /**
     * Updates the code and ends at date and time for a specified [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     *
     * @param string $acs_credential_id ID of the credential that you want to update.
     * @param string $code Replacement access (PIN) code for the credential that you want to update.
     * @param string $ends_at Replacement date and time at which the validity of the credential ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after the `starts_at` value that you set when creating the credential.
     * @return void OK
     */
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
    public AcsEncodersSimulateClient $simulate;
    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
        $this->simulate = new AcsEncodersSimulateClient($seam);
    }

    /**
     * Encodes an existing [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) onto a plastic card placed on the specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners). Either provide an `acs_credential_id` or an `access_method_id`
     *
     * @param string $acs_encoder_id ID of the `acs_encoder` to use to encode the `acs_credential`.
     * @param string $access_method_id ID of the `access_method` to encode onto a card.
     * @param string $acs_credential_id ID of the `acs_credential` to encode onto a card.
     * @return ActionAttempt OK
     */
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

    /**
     * Returns a specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners).
     *
     * @param string $acs_encoder_id ID of the encoder that you want to get.
     * @return AcsEncoder OK
     */
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

    /**
     * Returns a list of all [encoders](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners).
     *
     * @param string $acs_system_id ID of the access system for which you want to retrieve all encoders.
     * @param array $acs_system_ids IDs of the access systems for which you want to retrieve all encoders.
     * @param array $acs_encoder_ids IDs of the encoders that you want to retrieve.
     * @param float $limit Number of encoders to return.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @return array OK
     */
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

    /**
     * Scans an encoded [acs_credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) from a plastic card placed on the specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners).
     *
     * @param string $acs_encoder_id ID of the encoder to use for the scan.
     * @param mixed $salto_ks_metadata Salto KS-specific metadata for the scan action.
     * @return ActionAttempt OK
     */
    public function scan_credential(
        string $acs_encoder_id,
        mixed $salto_ks_metadata = null,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }
        if ($salto_ks_metadata !== null) {
            $request_payload["salto_ks_metadata"] = $salto_ks_metadata;
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

    /**
     * Scans a physical card placed on the specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners) and assigns the scanned credential to an ACS user. Provide either an `acs_user_id` or a `user_identity_id`.
     *
     * @param string $acs_encoder_id ID of the `acs_encoder` to use to scan the credential.
     * @param string $acs_user_id ID of the `acs_user` to assign the scanned credential to.
     * @param mixed $salto_ks_metadata Salto KS-specific metadata for the scan action.
     * @param string $user_identity_id ID of the `user_identity` to assign the scanned credential to. If the ACS system contains an ACS user linked to this user identity, it is used. Otherwise, one is created.
     * @return ActionAttempt OK
     */
    public function scan_to_assign_credential(
        string $acs_encoder_id,
        ?string $acs_user_id = null,
        mixed $salto_ks_metadata = null,
        ?string $user_identity_id = null,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
        }
        if ($salto_ks_metadata !== null) {
            $request_payload["salto_ks_metadata"] = $salto_ks_metadata;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/encoders/scan_to_assign_credential",
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

    /**
     * Simulates that the next attempt to encode a [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) using the specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners) will fail. You can only perform this action within a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $acs_encoder_id ID of the `acs_encoder` that will be used in the next request to encode the `acs_credential`.
     * @param string $error_code Code of the error to simulate.
     * @param string $acs_credential_id ID of the `acs_credential` that will fail to be encoded onto a card in the next request.
     * @return void OK
     */
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

    /**
     * Simulates that the next attempt to encode a [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) using the specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners) will succeed. You can only perform this action within a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $acs_encoder_id ID of the `acs_encoder` that will be used in the next request to encode the `acs_credential`.
     * @param string $scenario Scenario to simulate.
     * @return void OK
     */
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

    /**
     * Simulates that the next attempt to scan a [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) using the specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners) will fail. You can only perform this action within a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $acs_encoder_id ID of the `acs_encoder` that will fail to scan the `acs_credential` in the next request.
     * @param string $error_code
     * @param string $acs_credential_id_on_seam
     * @return void OK
     */
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

    /**
     * Simulates that the next attempt to scan a [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) using the specified [encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners) will succeed. You can only perform this action within a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $acs_encoder_id ID of the `acs_encoder` that will be used in the next request to scan the `acs_credential`.
     * @param string $acs_credential_id_on_seam ID of the Seam `acs_credential` that matches the `acs_credential` on the encoder in this simulation.
     * @param string $scenario Scenario to simulate.
     * @return void OK
     */
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

    /**
     * Returns a specified [access system entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     *
     * @param string $acs_entrance_id ID of the entrance that you want to get.
     * @return AcsEntrance OK
     */
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

    /**
     * Grants a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) access to a specified [access system entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     *
     * @param string $acs_entrance_id ID of the entrance to which you want to grant an access system user access.
     * @param string $acs_user_id ID of the access system user to whom you want to grant access to an entrance. You can only provide one of acs_user_id or user_identity_id.
     * @param string $user_identity_id ID of the user identity to whom you want to grant access to an entrance. You can only provide one of acs_user_id or user_identity_id. If the ACS system contains an ACS user with the same `email_address` or `phone_number` as the user identity that you specify, they are linked, and the access group membership belongs to the ACS user. If the ACS system does not have a corresponding ACS user, one is created.
     * @return void OK
     */
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

    /**
     * Returns a list of all [access system entrances](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     *
     * @param string $access_method_id ID of the access method for which you want to retrieve all entrances to which it grants access.
     * @param string $acs_credential_id ID of the credential for which you want to retrieve all entrances.
     * @param array $acs_entrance_ids IDs of the entrances for which you want to retrieve all entrances.
     * @param string $acs_system_id ID of the access system for which you want to retrieve all entrances.
     * @param string $connected_account_id ID of the connected account for which you want to retrieve all entrances.
     * @param string $customer_key Customer key for which you want to list entrances.
     * @param int $limit Maximum number of records to return per page.
     * @param string $location_id
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned entrances to include all records that satisfy a partial match using `display_name`.
     * @param string $space_id ID of the space for which you want to list entrances.
     * @return array OK
     */
    public function list(
        ?string $access_method_id = null,
        ?string $acs_credential_id = null,
        ?array $acs_entrance_ids = null,
        ?string $acs_system_id = null,
        ?string $connected_account_id = null,
        ?string $customer_key = null,
        ?int $limit = null,
        ?string $location_id = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?string $space_id = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($access_method_id !== null) {
            $request_payload["access_method_id"] = $access_method_id;
        }
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

    /**
     * Returns a list of all [credentials](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) with access to a specified [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
     *
     * @param string $acs_entrance_id ID of the entrance for which you want to list all credentials that grant access.
     * @param array $include_if Conditions that credentials must meet to be included in the returned list.
     * @return array OK
     */
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

    /**
     * Remotely unlocks a specified [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) using a cloud_key credential. Returns an action attempt that tracks the progress of the unlock operation.
     *
     * @param string $acs_credential_id ID of the cloud_key credential to use for the unlock operation.
     * @param string $acs_entrance_id ID of the entrance to unlock.
     * @return ActionAttempt OK
     */
    public function unlock(
        string $acs_credential_id,
        string $acs_entrance_id,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }
        if ($acs_entrance_id !== null) {
            $request_payload["acs_entrance_id"] = $acs_entrance_id;
        }

        $res = $this->seam->request(
            "POST",
            "/acs/entrances/unlock",
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

class AcsSystemsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    /**
     * Returns a specified [access system](https://docs.seam.co/low-level-apis/access-systems).
     *
     * @param string $acs_system_id ID of the access system that you want to get.
     * @return AcsSystem OK
     */
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

    /**
     * Returns a list of all [access systems](https://docs.seam.co/low-level-apis/access-systems).
     *
     * To filter the list of returned access systems by a specific connected account ID, include the `connected_account_id` in the request body. If you omit the `connected_account_id` parameter, the response includes all access systems connected to your workspace.
     *
     * @param string $connected_account_id ID of the connected account by which you want to filter the list of access systems.
     * @param string $customer_key Customer key for which you want to list access systems.
     * @param string $search String for which to search. Filters returned access systems to include all records that satisfy a partial match using `name` or `acs_system_id`.
     * @return array OK
     */
    public function list(
        ?string $connected_account_id = null,
        ?string $customer_key = null,
        ?string $search = null,
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

        $res = $this->seam->request(
            "POST",
            "/acs/systems/list",
            json: (object) $request_payload,
        );

        return array_map(fn($r) => AcsSystem::from_json($r), $res->acs_systems);
    }

    /**
     * Returns a list of all credential manager systems that are compatible with a specified [access system](https://docs.seam.co/low-level-apis/access-systems).
     *
     * Specify the access system for which you want to retrieve all compatible credential manager systems by including the corresponding `acs_system_id` in the request body.
     *
     * @param string $acs_system_id ID of the access system for which you want to retrieve all compatible credential manager systems.
     * @return array OK
     */
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

    /**
     * Reports ACS system device status including encoders and entrances.
     *
     * @param string $acs_system_id ID of the ACS system to report resources for
     * @param array $acs_encoders Array of ACS encoders to report
     * @param array $acs_entrances Array of ACS entrances to report
     * @return void OK
     */
    public function report_devices(
        string $acs_system_id,
        ?array $acs_encoders = null,
        ?array $acs_entrances = null,
    ): void {
        $request_payload = [];

        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_encoders !== null) {
            $request_payload["acs_encoders"] = $acs_encoders;
        }
        if ($acs_entrances !== null) {
            $request_payload["acs_entrances"] = $acs_entrances;
        }

        $this->seam->request(
            "POST",
            "/acs/systems/report_devices",
            json: (object) $request_payload,
        );
    }
}

class AcsUsersClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    /**
     * Adds a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) to a specified [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_access_group_id ID of the access group to which you want to add an access system user.
     * @param string $acs_user_id ID of the access system user that you want to add to an access group.
     * @return void OK
     */
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

    /**
     * Creates a new [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     *
     * @param string $acs_system_id ID of the access system to which you want to add the new access system user.
     * @param string $full_name Full name of the new access system user.
     * @param mixed $access_schedule `starts_at` and `ends_at` timestamps for the new access system user's access. If you specify an `access_schedule`, you may include both `starts_at` and `ends_at`. If you omit `starts_at`, it defaults to the current time. `ends_at` is optional and must be a time in the future and after `starts_at`.
     * @param array $acs_access_group_ids Array of access group IDs to indicate the access groups to which you want to add the new access system user.
     * @param string $email
     * @param string $email_address Email address of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     * @param string $phone_number Phone number of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) in E.164 format (for example, `+15555550100`).
     * @param string $user_identity_id ID of the user identity with which you want to associate the new access system user.
     * @return AcsUser OK
     */
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

    /**
     * Deletes a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) and invalidates the access system user's [credentials](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     *
     * @param string $acs_system_id ID of the access system that you want to delete. You must provide acs_system_id with user_identity_id.
     * @param string $acs_user_id ID of the access system user that you want to delete. You must provide either acs_user_id or user_identity_id
     * @param string $user_identity_id ID of the user identity that you want to delete. You must provide either acs_user_id or user_identity_id. If you provide user_identity_id, you must also provide acs_system_id.
     * @return void OK
     */
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

    /**
     * Returns a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     *
     * @param string $acs_user_id ID of the access system user that you want to get. You can only provide acs_user_id or user_identity_id.
     * @param string $acs_system_id ID of the access system that you want to get. You can only provide acs_user_id or user_identity_id.
     * @param string $user_identity_id ID of the user identity that you want to get. You can only provide acs_user_id or user_identity_id.
     * @return AcsUser OK
     */
    public function get(
        ?string $acs_user_id = null,
        ?string $acs_system_id = null,
        ?string $user_identity_id = null,
    ): AcsUser {
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
            "/acs/users/get",
            json: (object) $request_payload,
        );

        return AcsUser::from_json($res->acs_user);
    }

    /**
     * Returns a list of all [access system users](https://docs.seam.co/low-level-apis/access-systems/user-management).
     *
     * @param string $acs_system_id ID of the `acs_system` for which you want to retrieve all access system users.
     * @param string $created_before Timestamp by which to limit returned access system users. Returns users created before this timestamp.
     * @param int $limit Maximum number of records to return per page.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned access system users to include all records that satisfy a partial match using `full_name`, `phone_number`, `email_address`, `acs_user_id`, `user_identity_id`, `user_identity_full_name` or `user_identity_phone_number`.
     * @param string $user_identity_email_address Email address of the user identity for which you want to retrieve all access system users.
     * @param string $user_identity_id ID of the user identity for which you want to retrieve all access system users.
     * @param string $user_identity_phone_number Phone number of the user identity for which you want to retrieve all access system users, in [E.164 format](https://www.itu.int/rec/T-REC-E.164/en) (for example, `+15555550100`).
     * @return array OK
     */
    public function list(
        ?string $acs_system_id = null,
        ?string $created_before = null,
        ?int $limit = null,
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

    /**
     * Lists the [entrances](https://docs.seam.co/api/acs/entrances) to which a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) has access.
     *
     * @param string $acs_system_id ID of the access system for which you want to list accessible entrances. You can only provide acs_system_id with user_identity_id.
     * @param string $acs_user_id ID of the access system user for whom you want to list accessible entrances. You can only provide acs_user_id or user_identity_id.
     * @param string $user_identity_id ID of the user identity for whom you want to list accessible entrances. You can only provide acs_user_id or user_identity_id.
     * @return array OK
     */
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

    /**
     * Removes a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) from a specified [access group](https://docs.seam.co/low-level-apis/access-systems/user-management/assigning-users-to-access-groups).
     *
     * @param string $acs_access_group_id ID of the access group from which you want to remove an access system user.
     * @param string $acs_user_id ID of the access system user that you want to remove from an access group. You can only provide acs_user_id or user_identity_id.
     * @param string $user_identity_id ID of the user identity that you want to remove from an access group. You can only provide acs_user_id or user_identity_id.
     * @return void OK
     */
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

    /**
     * Revokes access to all [entrances](https://docs.seam.co/api/acs/entrances) for a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     *
     * @param string $acs_system_id ID of the access system for which you want to revoke access. You can only provide acs_system_id with user_identity_id.
     * @param string $acs_user_id ID of the access system user for whom you want to revoke access. You can only provide acs_user_id or user_identity_id.
     * @param string $user_identity_id ID of the user identity for whom you want to revoke access. You can only provide acs_user_id or user_identity_id.
     * @return void OK
     */
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

    /**
     * [Suspends](https://docs.seam.co/low-level-apis/access-systems/user-management/suspending-and-unsuspending-users#suspend-an-acs-user) a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management). Suspending an access system user revokes their access temporarily. To restore an access system user's access, you can [unsuspend](https://docs.seam.co/api/acs/users/unsuspend) them.
     *
     * @param string $acs_system_id ID of the access system that you want to suspend. You can only provide acs_user_id or the combination of acs_system_id and user_identity_id.
     * @param string $acs_user_id ID of the access system user that you want to suspend. You can only provide acs_user_id or the combination of acs_system_id and user_identity_id.
     * @param string $user_identity_id ID of the user identity that you want to suspend. You can only provide acs_user_id or the combination of acs_system_id and user_identity_id.
     * @return void OK
     */
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

    /**
     * [Unsuspends](https://docs.seam.co/low-level-apis/access-systems/user-management/suspending-and-unsuspending-users#unsuspend-an-acs-user) a specified suspended [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management). While [suspending an access system user](https://docs.seam.co/api/acs/users/suspend) revokes their access temporarily, unsuspending the access system user restores their access.
     *
     * @param string $acs_system_id ID of the access system of the user that you want to unsuspend. You can only provide acs_system_id with user_identity_id.
     * @param string $acs_user_id ID of the access system user that you want to unsuspend. You can only provide acs_user_id or the combination of acs_system_id and user_identity_id.
     * @param string $user_identity_id ID of the user identity that you want to unsuspend. You can only provide acs_user_id or the combination of acs_system_id and user_identity_id.
     * @return void OK
     */
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

    /**
     * Updates the properties of a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     *
     * @param mixed $access_schedule `starts_at` and `ends_at` timestamps for the access system user's access. If you specify an `access_schedule`, you may include both `starts_at` and `ends_at`. If you omit `starts_at`, it defaults to the current time. `ends_at` is optional and must be a time in the future and after `starts_at`.
     * @param string $acs_system_id ID of the access system that you want to update. You can only provide acs_system_id with user_identity_id.
     * @param string $acs_user_id ID of the access system user that you want to update. You can only provide acs_user_id or user_identity_id.
     * @param string $email
     * @param string $email_address Email address of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     * @param string $full_name Full name of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management).
     * @param string $hid_acs_system_id ID of the HID access control system associated with the user.
     * @param string $phone_number Phone number of the [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) in E.164 format (for example, `+15555550100`).
     * @param string $user_identity_id ID of the user identity that you want to update. You can only provide acs_user_id or user_identity_id. If you provide user_identity_id, you must also provide acs_system_id.
     * @return void OK
     */
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

    /**
     * Returns a specified [action attempt](https://docs.seam.co/core-concepts/action-attempts).
     *
     * @param string $action_attempt_id ID of the action attempt that you want to get.
     * @return ActionAttempt OK
     */
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

    /**
     * Returns a list of the [action attempts](https://docs.seam.co/core-concepts/action-attempts) that you specify as an array of `action_attempt_id`s.
     *
     * @param array $action_attempt_ids IDs of the action attempts that you want to retrieve.
     * @param string $device_id ID of the device to filter action attempts by.
     * @param int $limit Maximum number of records to return per page.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @return array OK
     */
    public function list(
        ?array $action_attempt_ids = null,
        ?string $device_id = null,
        ?int $limit = null,
        ?string $page_cursor = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($action_attempt_ids !== null) {
            $request_payload["action_attempt_ids"] = $action_attempt_ids;
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

        $res = $this->seam->request(
            "POST",
            "/action_attempts/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

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

    /**
     * Creates a new [client session](https://docs.seam.co/core-concepts/authentication/client-session-tokens).
     *
     * @param array $connect_webview_ids IDs of the [Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews) for which you want to create a client session.
     * @param array $connected_account_ids IDs of the [connected accounts](https://docs.seam.co/core-concepts/connected-accounts) for which you want to create a client session.
     * @param string $customer_id Customer ID that you want to associate with the new client session.
     * @param string $customer_key Customer key that you want to associate with the new client session.
     * @param string $expires_at Date and time at which the client session should expire, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @param string $user_identifier_key Your user ID for the user for whom you want to create a client session.
     * @param string $user_identity_id ID of the [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) for which you want to create a client session.
     * @param array $user_identity_ids IDs of the [user identities](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) that you want to associate with the client session.
     * @return ClientSession OK
     */
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

    /**
     * Deletes a [client session](https://docs.seam.co/core-concepts/authentication/client-session-tokens).
     *
     * @param string $client_session_id ID of the client session that you want to delete.
     * @return void OK
     */
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

    /**
     * Returns a specified [client session](https://docs.seam.co/core-concepts/authentication/client-session-tokens).
     *
     * @param string $client_session_id ID of the client session that you want to get.
     * @param string $user_identifier_key User identifier key associated with the client session that you want to get.
     * @return ClientSession OK
     */
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

    /**
     * Returns a [client session](https://docs.seam.co/core-concepts/authentication/client-session-tokens) with specific characteristics or creates a new client session with these characteristics if it does not yet exist.
     *
     * @param array $connect_webview_ids IDs of the [Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews) that you want to associate with the client session (or that are already associated with the existing client session).
     * @param array $connected_account_ids IDs of the [connected accounts](https://docs.seam.co/api/connected_accounts) that you want to associate with the client session (or that are already associated with the existing client session).
     * @param string $expires_at Date and time at which the client session should expire in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. If the client session already exists, this will update the expiration before returning it.
     * @param string $user_identifier_key Your user ID for the user that you want to associate with the client session (or that is already associated with the existing client session).
     * @param string $user_identity_id ID of the [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) that you want to associate with the client session (or that are already associated with the existing client session).
     * @param array $user_identity_ids IDs of the [user identities](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) that you want to associate with the client session.
     * @return ClientSession OK
     */
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

    /**
     * Grants a [client session](https://docs.seam.co/core-concepts/authentication/client-session-tokens) access to one or more resources, such as [Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews), [user identities](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity), and so on.
     *
     * @param string $client_session_id ID of the client session to which you want to grant access to resources.
     * @param array $connect_webview_ids IDs of the [Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews) that you want to associate with the client session.
     * @param array $connected_account_ids IDs of the [connected accounts](https://docs.seam.co/core-concepts/connected-accounts) that you want to associate with the client session.
     * @param string $user_identifier_key Your user ID for the user that you want to associate with the client session.
     * @param string $user_identity_id ID of the [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) that you want to associate with the client session.
     * @param array $user_identity_ids IDs of the [user identities](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) that you want to associate with the client session.
     * @return void OK
     */
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

    /**
     * Returns a list of all [client sessions](https://docs.seam.co/core-concepts/authentication/client-session-tokens).
     *
     * @param string $client_session_id ID of the client session that you want to retrieve.
     * @param string $connect_webview_id ID of the [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews) for which you want to retrieve client sessions.
     * @param string $user_identifier_key Your user ID for the user by which you want to filter client sessions.
     * @param string $user_identity_id ID of the [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) for which you want to retrieve client sessions.
     * @param bool $without_user_identifier_key Indicates whether to retrieve only client sessions without associated user identifier keys.
     * @return array OK
     */
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

    /**
     * Revokes a [client session](https://docs.seam.co/core-concepts/authentication/client-session-tokens).
     *
     * Note that [deleting a client session](https://docs.seam.co/api/client_sessions/delete) is a separate action.
     *
     * @param string $client_session_id ID of the client session that you want to revoke.
     * @return void OK
     */
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

    /**
     * Creates a new [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews).
     *
     * To enable a user to connect their devices or systems to Seam, they must sign in to their device or system account. To enable a user to sign in, you create a `connect_webview`. After creating the Connect Webview, you receive a URL that you can use to display the visual component of this Connect Webview for your user. You can open an iframe or new window to display the Connect Webview.
     *
     * You should make a new `connect_webview` for each unique login request. Each `connect_webview` tracks the user that signed in with it. You receive an error if you reuse a Connect Webview for the same user twice or if you use the same Connect Webview for multiple users.
     *
     * See also: [Connect Webview Process](https://docs.seam.co/core-concepts/connect-webviews/connect-webview-process).
     *
     * @param array $accepted_capabilities List of accepted device capabilities that restrict the types of devices that can be connected through the Connect Webview. If not provided, defaults will be determined based on the accepted providers.
     * @param array $accepted_providers Accepted device provider keys as an alternative to `provider_category`. Use this parameter to specify accepted providers explicitly. See [Customize the Brands to Display in Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-brands-to-display-in-your-connect-webviews). To list all provider keys, use [`/devices/list_device_providers`](https://docs.seam.co/api/devices/list_device_providers) with no filters.
     * @param bool $automatically_manage_new_devices Indicates whether newly-added devices should appear as [managed devices](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices). See also: [Customize the Behavior Settings of Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-behavior-settings-of-your-connect-webviews).
     * @param mixed $custom_metadata Custom metadata that you want to associate with the Connect Webview. Supports up to 50 JSON key:value pairs. [Adding custom metadata to a Connect Webview](https://docs.seam.co/core-concepts/connect-webviews/attaching-custom-data-to-the-connect-webview) enables you to store custom information, like customer details or internal IDs from your application. The custom metadata is then transferred to any [connected accounts](https://docs.seam.co/core-concepts/connected-accounts) that were connected using the Connect Webview, making it easy to find and filter these resources in your [workspace](https://docs.seam.co/core-concepts/workspaces). You can also [filter Connect Webviews by custom metadata](https://docs.seam.co/core-concepts/connect-webviews/filtering-connect-webviews-by-custom-metadata).
     * @param string $custom_redirect_failure_url Alternative URL that you want to redirect the user to on an error. If you do not set this parameter, the Connect Webview falls back to the `custom_redirect_url`.
     * @param string $custom_redirect_url URL that you want to redirect the user to after the provider login is complete.
     * @param string $customer_key Associate the Connect Webview, the connected account, and all resources under the connected account with a customer. If the connected account already exists, it will be associated with the customer. If the connected account already exists, but is already associated with a customer, the Connect Webview will show an error.
     * @param array $excluded_providers List of provider keys to exclude from the Connect Webview. These providers will not be shown when the user tries to connect an account.
     * @param string $provider_category Specifies the category of providers that you want to include. To list all providers within a category, use [`/devices/list_device_providers`](https://docs.seam.co/api/devices/list_device_providers) with the desired `provider_category` filter.
     * @param bool $wait_for_device_creation Indicates whether Seam should finish syncing all devices in a newly-connected account before completing the associated Connect Webview. See also: [Customize the Behavior Settings of Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-behavior-settings-of-your-connect-webviews).
     * @return ConnectWebview OK
     */
    public function create(
        ?array $accepted_capabilities = null,
        ?array $accepted_providers = null,
        ?bool $automatically_manage_new_devices = null,
        mixed $custom_metadata = null,
        ?string $custom_redirect_failure_url = null,
        ?string $custom_redirect_url = null,
        ?string $customer_key = null,
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

    /**
     * Deletes a [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews).
     *
     * You do not need to delete a Connect Webview once a user completes it. Instead, you can simply ignore completed Connect Webviews.
     *
     * @param string $connect_webview_id ID of the Connect Webview that you want to delete.
     * @return void OK
     */
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

    /**
     * Returns a specified [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews).
     *
     * Unless you're using a `custom_redirect_url`, you should poll a newly-created `connect_webview` to find out if the user has signed in or to get details about what devices they've connected.
     *
     * @param string $connect_webview_id ID of the Connect Webview that you want to get.
     * @return ConnectWebview OK
     */
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

    /**
     * Returns a list of all [Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews).
     *
     * @param mixed $custom_metadata_has Custom metadata pairs by which you want to [filter Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/filtering-connect-webviews-by-custom-metadata). Returns Connect Webviews with `custom_metadata` that contains all of the provided key:value pairs.
     * @param string $customer_key Customer key for which you want to list connect webviews.
     * @param float $limit Maximum number of records to return per page.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned Connect Webviews to include all records that satisfy a partial match using `connect_webview_id`, `accepted_providers`, `custom_metadata`, or `customer_key`.
     * @param string $user_identifier_key Your user ID for the user by which you want to filter Connect Webviews.
     * @return array OK
     */
    public function list(
        mixed $custom_metadata_has = null,
        ?string $customer_key = null,
        ?float $limit = null,
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
    public ConnectedAccountsSimulateClient $simulate;
    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
        $this->simulate = new ConnectedAccountsSimulateClient($seam);
    }

    /**
     * Deletes a specified [connected account](https://docs.seam.co/core-concepts/connected-accounts).
     *
     * Deleting a connected account triggers a `connected_account.deleted` event and removes the connected account and all data associated with the connected account from Seam, including devices, events, access codes, and so on. For every deleted resource, Seam sends a corresponding deleted event, but the resource is not deleted from the provider.
     *
     * For example, if you delete a connected account with a device that has an access code, Seam sends a `connected_account.deleted` event, a `device.deleted` event, and an `access_code.deleted` event, but Seam does not remove the access code from the device.
     *
     * @param string $connected_account_id ID of the connected account that you want to delete.
     * @return void OK
     */
    public function delete(string $connected_account_id): void
    {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }

        $this->seam->request(
            "POST",
            "/connected_accounts/delete",
            json: (object) $request_payload,
        );
    }

    /**
     * Returns a specified [connected account](https://docs.seam.co/core-concepts/connected-accounts).
     *
     * @param string $connected_account_id ID of the connected account that you want to get.
     * @param string $email Email address associated with the connected account that you want to get.
     * @return ConnectedAccount OK
     */
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

    /**
     * Returns a list of all [connected accounts](https://docs.seam.co/core-concepts/connected-accounts).
     *
     * @param mixed $custom_metadata_has Custom metadata pairs by which you want to filter connected accounts. Returns connected accounts with `custom_metadata` that contains all of the provided key:value pairs.
     * @param string $customer_key Customer key by which you want to filter connected accounts.
     * @param int $limit Maximum number of records to return per page.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned connected accounts to include all records that satisfy a partial match using `connected_account_id`, `account_type`, `customer_key`, `custom_metadata`, `user_identifier.username`, `user_identifier.email` or `user_identifier.phone`.
     * @param string $space_id ID of the space by which you want to filter connected accounts.
     * @param string $user_identifier_key Your user ID for the user by which you want to filter connected accounts.
     * @return array OK
     */
    public function list(
        mixed $custom_metadata_has = null,
        ?string $customer_key = null,
        ?int $limit = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?string $space_id = null,
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
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
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

    /**
     * Request a [connected account](https://docs.seam.co/core-concepts/connected-accounts) sync attempt for the specified `connected_account_id`.
     *
     * @param string $connected_account_id ID of the connected account that you want to sync.
     * @return void OK
     */
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

    /**
     * Updates a [connected account](https://docs.seam.co/core-concepts/connected-accounts).
     *
     * @param string $connected_account_id ID of the connected account that you want to update.
     * @param array $accepted_capabilities List of accepted device capabilities that restrict the types of devices that can be connected through this connected account. Valid values are `lock`, `thermostat`, `noise_sensor`, and `access_control`.
     * @param bool $automatically_manage_new_devices Indicates whether newly-added devices should appear as [managed devices](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices).
     * @param mixed $custom_metadata Custom metadata that you want to associate with the connected account. Entirely replaces the existing custom metadata object. If a new Connect Webview contains custom metadata and is used to reconnect a connected account, the custom metadata from the Connect Webview will entirely replace the entire custom metadata object on the connected account. Supports up to 50 JSON key:value pairs. [Adding custom metadata to a connected account](https://docs.seam.co/core-concepts/connected-accounts/adding-custom-metadata-to-a-connected-account) enables you to store custom information, like customer details or internal IDs from your application. Then, you can [filter connected accounts by the desired metadata](https://docs.seam.co/core-concepts/connected-accounts/filtering-connected-accounts-by-custom-metadata).
     * @param string $customer_key The customer key to associate with this connected account. If provided, the connected account and all resources under the connected account will be moved to this customer. May only be provided if the connected account is not already associated with a customer.
     * @param string $display_name Human-readable name for the connected account, shown in the dashboard. For example, `Booking from Airbnb House 1`.
     * @return void OK
     */
    public function update(
        string $connected_account_id,
        ?array $accepted_capabilities = null,
        ?bool $automatically_manage_new_devices = null,
        mixed $custom_metadata = null,
        ?string $customer_key = null,
        ?string $display_name = null,
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
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($display_name !== null) {
            $request_payload["display_name"] = $display_name;
        }

        $this->seam->request(
            "POST",
            "/connected_accounts/update",
            json: (object) $request_payload,
        );
    }
}

class ConnectedAccountsSimulateClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    /**
     * Simulates a connected account becoming disconnected from Seam. Only applicable for [sandbox workspaces](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $connected_account_id ID of the connected account you want to simulate as disconnected.
     * @return void OK
     */
    public function disconnect(string $connected_account_id): void
    {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }

        $this->seam->request(
            "POST",
            "/connected_accounts/simulate/disconnect",
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

    /**
     * Creates a new customer portal magic link with configurable features.
     *
     * @param array $customer_resources_filters Filter configuration for resources based on their custom_metadata. Each filter specifies a field, operation, and value to match against resource custom_metadata.
     * @param string $customization_profile_id The ID of the customization profile to use for the portal.
     * @param mixed $deep_link Deep link target resource for initial redirect. When set, the portal will navigate directly to the specified resource.
     * @param bool $exclude_locale_picker Whether to exclude the option to select a locale within the portal UI.
     * @param mixed $features
     * @param bool $is_embedded Whether the portal is embedded in another application.
     * @param mixed $landing_page Configuration for the landing page when the portal loads.
     * @param string $locale The locale to use for the portal.
     * @param string $navigation_mode Navigation mode for the portal. 'restricted' tells frontend to hide navigation UI, typically used for embedded deep links.
     * @param bool $read_only Whether the portal is read-only. When true, the customer can browse the portal but cannot perform any mutating action; write requests made with the portal's client session are rejected.
     * @param mixed $customer_data
     * @return CustomerPortal OK
     */
    public function create_portal(
        ?array $customer_resources_filters = null,
        ?string $customization_profile_id = null,
        mixed $deep_link = null,
        ?bool $exclude_locale_picker = null,
        mixed $features = null,
        ?bool $is_embedded = null,
        mixed $landing_page = null,
        ?string $locale = null,
        ?string $navigation_mode = null,
        ?bool $read_only = null,
        mixed $customer_data = null,
    ): CustomerPortal {
        $request_payload = [];

        if ($customer_resources_filters !== null) {
            $request_payload[
                "customer_resources_filters"
            ] = $customer_resources_filters;
        }
        if ($customization_profile_id !== null) {
            $request_payload[
                "customization_profile_id"
            ] = $customization_profile_id;
        }
        if ($deep_link !== null) {
            $request_payload["deep_link"] = $deep_link;
        }
        if ($exclude_locale_picker !== null) {
            $request_payload["exclude_locale_picker"] = $exclude_locale_picker;
        }
        if ($features !== null) {
            $request_payload["features"] = $features;
        }
        if ($is_embedded !== null) {
            $request_payload["is_embedded"] = $is_embedded;
        }
        if ($landing_page !== null) {
            $request_payload["landing_page"] = $landing_page;
        }
        if ($locale !== null) {
            $request_payload["locale"] = $locale;
        }
        if ($navigation_mode !== null) {
            $request_payload["navigation_mode"] = $navigation_mode;
        }
        if ($read_only !== null) {
            $request_payload["read_only"] = $read_only;
        }
        if ($customer_data !== null) {
            $request_payload["customer_data"] = $customer_data;
        }

        $res = $this->seam->request(
            "POST",
            "/customers/create_portal",
            json: (object) $request_payload,
        );

        return CustomerPortal::from_json($res->customer_portal);
    }

    /**
     * Deletes customer data including resources like spaces, properties, rooms, users, etc.
     * This will delete the partner resources and any related Seam resources (user identities, access grants, spaces).
     *
     * @param array $access_grant_keys List of access grant keys to delete.
     * @param array $booking_keys List of booking keys to delete.
     * @param array $building_keys List of building keys to delete.
     * @param array $common_area_keys List of common area keys to delete.
     * @param array $customer_keys List of customer keys to delete all data for.
     * @param array $facility_keys List of facility keys to delete.
     * @param array $guest_keys List of guest keys to delete.
     * @param array $listing_keys List of listing keys to delete.
     * @param array $property_keys List of property keys to delete.
     * @param array $property_listing_keys List of property listing keys to delete.
     * @param array $reservation_keys List of reservation keys to delete.
     * @param array $resident_keys List of resident keys to delete.
     * @param array $room_keys List of room keys to delete.
     * @param array $space_keys List of space keys to delete.
     * @param array $staff_member_keys List of staff member keys to delete.
     * @param array $tenant_keys List of tenant keys to delete.
     * @param array $unit_keys List of unit keys to delete.
     * @param array $user_identity_keys List of user identity keys to delete.
     * @param array $user_keys List of user keys to delete.
     * @return void OK
     */
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
        ?array $staff_member_keys = null,
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
        if ($staff_member_keys !== null) {
            $request_payload["staff_member_keys"] = $staff_member_keys;
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

    /**
     * Pushes customer data including resources like spaces, properties, rooms, users, etc.
     *
     * @param string $customer_key Your unique identifier for the customer.
     * @param array $access_grants List of access grants.
     * @param array $bookings List of bookings.
     * @param array $buildings List of buildings.
     * @param array $common_areas List of shared common areas.
     * @param array $facilities List of gym or fitness facilities.
     * @param array $guests List of guests.
     * @param array $listings List of property listings.
     * @param array $properties List of short-term rental properties.
     * @param array $property_listings List of property listings.
     * @param array $reservations List of reservations.
     * @param array $residents List of residents.
     * @param array $rooms List of hotel or hospitality rooms.
     * @param array $sites List of general sites or areas.
     * @param array $spaces List of general spaces or areas.
     * @param array $staff_members List of staff members.
     * @param array $tenants List of tenants.
     * @param array $units List of multi-family residential units.
     * @param array $user_identities List of user identities.
     * @param array $users List of users.
     * @return void OK
     */
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
        ?array $sites = null,
        ?array $spaces = null,
        ?array $staff_members = null,
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
        if ($sites !== null) {
            $request_payload["sites"] = $sites;
        }
        if ($spaces !== null) {
            $request_payload["spaces"] = $spaces;
        }
        if ($staff_members !== null) {
            $request_payload["staff_members"] = $staff_members;
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

    /**
     * Returns a specified [device](https://docs.seam.co/core-concepts/devices).
     *
     * You must specify either `device_id` or `name`.
     *
     * @param string $device_id ID of the device that you want to get.
     * @param string $name Name of the device that you want to get.
     * @return Device OK
     */
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

    /**
     * Returns a list of all [devices](https://docs.seam.co/core-concepts/devices).
     *
     * @param string $connect_webview_id ID of the Connect Webview for which you want to list devices.
     * @param string $connected_account_id ID of the connected account for which you want to list devices.
     * @param array $connected_account_ids Array of IDs of the connected accounts for which you want to list devices.
     * @param string $created_before Timestamp by which to limit returned devices. Returns devices created before this timestamp.
     * @param mixed $custom_metadata_has Set of key:value [custom metadata](https://docs.seam.co/core-concepts/devices/adding-custom-metadata-to-a-device) pairs for which you want to list devices.
     * @param string $customer_key Customer key for which you want to list devices.
     * @param array $device_ids Array of device IDs for which you want to list devices.
     * @param string $device_type Device type for which you want to list devices.
     * @param array $device_types Array of device types for which you want to list devices.
     * @param float $limit Numerical limit on the number of devices to return.
     * @param string $manufacturer Manufacturer for which you want to list devices.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned devices to include all records that satisfy a partial match using `device_id` (full or partial UUID prefix, minimum 4 characters), `connected_account_id`, `display_name`, `custom_metadata` or `location.location_name`.
     * @param string $space_id ID of the space for which you want to list devices.
     * @param string $unstable_location_id
     * @param string $user_identifier_key Your own internal user ID for the user for which you want to list devices.
     * @return array OK
     */
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

    /**
     * Returns a list of all device providers.
     *
     * The information that this endpoint returns for each provider includes a set of [capability flags](https://docs.seam.co/capability-guides/device-and-system-capabilities#capability-flags), such as `device_provider.can_remotely_unlock`. If at least one supported device from a provider has a specific capability, the corresponding capability flag is `true`.
     *
     * When you create a [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews), you can customize the providers—that is, the brands—that it displays. In the `/connect_webviews/create` request, include the desired set of device provider keys in the `accepted_providers` parameter. See also [Customize the Brands to Display in Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-brands-to-display-in-your-connect-webviews).
     *
     * @param string $provider_category Category for which you want to list providers.
     * @return array OK
     */
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

    /**
     * Updates provider-specific metadata for devices.
     *
     * @param array $devices Array of devices with provider metadata to update
     * @return void OK
     */
    public function report_provider_metadata(array $devices): void
    {
        $request_payload = [];

        if ($devices !== null) {
            $request_payload["devices"] = $devices;
        }

        $this->seam->request(
            "POST",
            "/devices/report_provider_metadata",
            json: (object) $request_payload,
        );
    }

    /**
     * Updates a specified [device](https://docs.seam.co/core-concepts/devices).
     *
     * You can add or change [custom metadata](https://docs.seam.co/core-concepts/devices/adding-custom-metadata-to-a-device) for a device, change the device's name, or [convert a managed device to unmanaged](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices).
     *
     * @param string $device_id ID of the device that you want to update.
     * @param bool $backup_access_code_pool_enabled Indicates whether the device's [backup access code pool](https://docs.seam.co/low-level-apis/smart-locks/access-codes/backup-access-codes) is enabled. Set to `false` to disable the pool: Seam stops refilling it and removes any backup codes that have not yet been pulled into active use.
     * @param mixed $custom_metadata Custom metadata that you want to associate with the device. Supports up to 50 JSON key:value pairs. [Adding custom metadata to a device](https://docs.seam.co/core-concepts/devices/adding-custom-metadata-to-a-device) enables you to store custom information, like customer details or internal IDs from your application. Then, you can [filter devices by the desired metadata](https://docs.seam.co/core-concepts/devices/filtering-devices-by-custom-metadata).
     * @param bool $is_managed Indicates whether the device is managed. To unmanage a device, set `is_managed` to `false`.
     * @param string $name Name for the device.
     * @param mixed $properties
     * @return void OK
     */
    public function update(
        string $device_id,
        ?bool $backup_access_code_pool_enabled = null,
        mixed $custom_metadata = null,
        ?bool $is_managed = null,
        ?string $name = null,
        mixed $properties = null,
    ): void {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($backup_access_code_pool_enabled !== null) {
            $request_payload[
                "backup_access_code_pool_enabled"
            ] = $backup_access_code_pool_enabled;
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

    /**
     * Simulates connecting a device to Seam. Only applicable for [sandbox devices](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces). See also [Testing Your App Against Device Disconnection and Removal](https://docs.seam.co/core-concepts/devices/testing-your-app-against-device-disconnection-and-removal).
     *
     * @param string $device_id ID of the device that you want to simulate connecting to Seam.
     * @return void OK
     */
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

    /**
     * Simulates bringing the Wi‑Fi hub (bridge) back online for a device.
     * Only applicable for sandbox workspaces and currently
     * implemented for August and TTLock locks.
     * This will clear the `hub_disconnected` error on the device.
     *
     * @param string $device_id ID of the device whose hub you want to reconnect.
     * @return void OK
     */
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

    /**
     * Simulates disconnecting a device from Seam. Only applicable for [sandbox devices](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces). See also [Testing Your App Against Device Disconnection and Removal](https://docs.seam.co/core-concepts/devices/testing-your-app-against-device-disconnection-and-removal).
     *
     * @param string $device_id ID of the device that you want to simulate disconnecting from Seam.
     * @return void OK
     */
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

    /**
     * Simulates taking the Wi‑Fi hub (bridge) offline for a device.
     * Only applicable for sandbox workspaces and currently
     * implemented for August, TTLock, and IglooHome devices.
     * This will set the `hub_disconnected` error on the device, or mark the
     * IglooHome bridge offline in sandbox.
     *
     * @param string $device_id ID of the device whose hub you want to disconnect.
     * @return void OK
     */
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

    /**
     * Toggle the simulated Nuki Smart Hosting subscription for a device (sandbox only).
     * Send `is_expired: true` to simulate an expired subscription, or `false` to simulate an active subscription.
     * The actual device error is created/cleared by the poller after this state change.
     *
     * @param string $device_id
     * @param bool $is_expired
     * @return void OK
     */
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

    /**
     * Simulates removing a device from Seam. Only applicable for [sandbox devices](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces). See also [Testing Your App Against Device Disconnection and Removal](https://docs.seam.co/core-concepts/devices/testing-your-app-against-device-disconnection-and-removal).
     *
     * @param string $device_id ID of the device that you want to simulate removing from Seam.
     * @return void OK
     */
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

    /**
     * Returns a specified [unmanaged device](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices).
     *
     * An unmanaged device has a limited set of visible properties and a subset of supported events. You cannot control an unmanaged device. Any [access codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes) on an unmanaged device are unmanaged. To control an unmanaged device with Seam, [convert it to a managed device](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices#convert-an-unmanaged-device-to-managed).
     *
     * You must specify either `device_id` or `name`.
     *
     * @param string $device_id ID of the unmanaged device that you want to get.
     * @param string $name Name of the unmanaged device that you want to get.
     * @return UnmanagedDevice OK
     */
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

    /**
     * Returns a list of all [unmanaged devices](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices).
     *
     * An unmanaged device has a limited set of visible properties and a subset of supported events. You cannot control an unmanaged device. Any [access codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes) on an unmanaged device are unmanaged. To control an unmanaged device with Seam, [convert it to a managed device](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices#convert-an-unmanaged-device-to-managed).
     *
     * @param string $connect_webview_id ID of the Connect Webview for which you want to list devices.
     * @param string $connected_account_id ID of the connected account for which you want to list devices.
     * @param array $connected_account_ids Array of IDs of the connected accounts for which you want to list devices.
     * @param string $created_before Timestamp by which to limit returned devices. Returns devices created before this timestamp.
     * @param mixed $custom_metadata_has Set of key:value [custom metadata](https://docs.seam.co/core-concepts/devices/adding-custom-metadata-to-a-device) pairs for which you want to list devices.
     * @param string $customer_key Customer key for which you want to list devices.
     * @param array $device_ids Array of device IDs for which you want to list devices.
     * @param string $device_type Device type for which you want to list devices.
     * @param array $device_types Array of device types for which you want to list devices.
     * @param float $limit Numerical limit on the number of devices to return.
     * @param string $manufacturer Manufacturer for which you want to list devices.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned devices to include all records that satisfy a partial match using `device_id` (full or partial UUID prefix, minimum 4 characters), `connected_account_id`, `display_name`, `custom_metadata` or `location.location_name`.
     * @param string $space_id ID of the space for which you want to list devices.
     * @param string $unstable_location_id
     * @param string $user_identifier_key Your own internal user ID for the user for which you want to list devices.
     * @return array OK
     */
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

    /**
     * Updates a specified [unmanaged device](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices). To convert an unmanaged device to managed, set `is_managed` to `true`.
     *
     * An unmanaged device has a limited set of visible properties and a subset of supported events. You cannot control an unmanaged device. Any [access codes](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes) on an unmanaged device are unmanaged. To control an unmanaged device with Seam, [convert it to a managed device](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices#convert-an-unmanaged-device-to-managed).
     *
     * @param string $device_id ID of the unmanaged device that you want to update.
     * @param mixed $custom_metadata Custom metadata that you want to associate with the device. Supports up to 50 JSON key:value pairs.
     * @param bool $is_managed Indicates whether the device is managed. Set this parameter to `true` to convert an unmanaged device to managed.
     * @return void OK
     */
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

    /**
     * Returns a specified event. This endpoint returns the same event that would be sent to a [webhook](https://docs.seam.co/developer-tools/webhooks), but it enables you to retrieve an event that already took place.
     *
     * @param string $event_id Unique identifier for the event that you want to get.
     * @param string $device_id Unique identifier for the device that triggered the event that you want to get.
     * @param string $event_type Type of the event that you want to get.
     * @return Event OK
     */
    public function get(
        ?string $event_id = null,
        ?string $device_id = null,
        ?string $event_type = null,
    ): Event {
        $request_payload = [];

        if ($event_id !== null) {
            $request_payload["event_id"] = $event_id;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
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

    /**
     * Returns a list of all events. This endpoint returns the same events that would be sent to a [webhook](https://docs.seam.co/developer-tools/webhooks), but it enables you to filter or see events that already took place.
     *
     * @param string $access_code_id ID of the access code for which you want to list events.
     * @param array $access_code_ids IDs of the access codes for which you want to list events.
     * @param string $access_grant_id ID of the access grant for which you want to list events.
     * @param array $access_grant_ids IDs of the access grants for which you want to list events.
     * @param string $access_method_id ID of the access method for which you want to list events.
     * @param array $access_method_ids IDs of the access methods for which you want to list events.
     * @param string $acs_access_group_id ID of the ACS access group for which you want to list events.
     * @param string $acs_credential_id ID of the ACS credential for which you want to list events.
     * @param string $acs_encoder_id ID of the ACS encoder for which you want to list events.
     * @param string $acs_entrance_id ID of the ACS entrance for which you want to list events.
     * @param string $acs_system_id ID of the access system for which you want to list events.
     * @param array $acs_system_ids IDs of the access systems for which you want to list events.
     * @param string $acs_user_id ID of the ACS user for which you want to list events.
     * @param array $between Lower and upper timestamps to define an exclusive interval containing the events that you want to list. You must include `since` or `between`.
     * @param string $connect_webview_id ID of the Connect Webview for which you want to list events.
     * @param string $connected_account_id ID of the connected account for which you want to list events.
     * @param string $customer_key Customer key for which you want to list events.
     * @param string $device_id ID of the device for which you want to list events.
     * @param array $device_ids IDs of the devices for which you want to list events.
     * @param array $event_ids IDs of the events that you want to list.
     * @param string $event_type Type of the events that you want to list.
     * @param array $event_types Types of the events that you want to list.
     * @param float $limit Numerical limit on the number of events to return.
     * @param string $since Timestamp to indicate the beginning generation time for the events that you want to list. You must include `since` or `between`.
     * @param string $space_id ID of the space for which you want to list events.
     * @param array $space_ids IDs of the spaces for which you want to list events.
     * @param float $unstable_offset Offset for the events that you want to list.
     * @param string $user_identity_id ID of the user identity for which you want to list events.
     * @return array OK
     */
    public function list(
        ?string $access_code_id = null,
        ?array $access_code_ids = null,
        ?string $access_grant_id = null,
        ?array $access_grant_ids = null,
        ?string $access_method_id = null,
        ?array $access_method_ids = null,
        ?string $acs_access_group_id = null,
        ?string $acs_credential_id = null,
        ?string $acs_encoder_id = null,
        ?string $acs_entrance_id = null,
        ?string $acs_system_id = null,
        ?array $acs_system_ids = null,
        ?string $acs_user_id = null,
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
        ?string $space_id = null,
        ?array $space_ids = null,
        ?float $unstable_offset = null,
        ?string $user_identity_id = null,
    ): array {
        $request_payload = [];

        if ($access_code_id !== null) {
            $request_payload["access_code_id"] = $access_code_id;
        }
        if ($access_code_ids !== null) {
            $request_payload["access_code_ids"] = $access_code_ids;
        }
        if ($access_grant_id !== null) {
            $request_payload["access_grant_id"] = $access_grant_id;
        }
        if ($access_grant_ids !== null) {
            $request_payload["access_grant_ids"] = $access_grant_ids;
        }
        if ($access_method_id !== null) {
            $request_payload["access_method_id"] = $access_method_id;
        }
        if ($access_method_ids !== null) {
            $request_payload["access_method_ids"] = $access_method_ids;
        }
        if ($acs_access_group_id !== null) {
            $request_payload["acs_access_group_id"] = $acs_access_group_id;
        }
        if ($acs_credential_id !== null) {
            $request_payload["acs_credential_id"] = $acs_credential_id;
        }
        if ($acs_encoder_id !== null) {
            $request_payload["acs_encoder_id"] = $acs_encoder_id;
        }
        if ($acs_entrance_id !== null) {
            $request_payload["acs_entrance_id"] = $acs_entrance_id;
        }
        if ($acs_system_id !== null) {
            $request_payload["acs_system_id"] = $acs_system_id;
        }
        if ($acs_system_ids !== null) {
            $request_payload["acs_system_ids"] = $acs_system_ids;
        }
        if ($acs_user_id !== null) {
            $request_payload["acs_user_id"] = $acs_user_id;
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
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }
        if ($space_ids !== null) {
            $request_payload["space_ids"] = $space_ids;
        }
        if ($unstable_offset !== null) {
            $request_payload["unstable_offset"] = $unstable_offset;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
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

    /**
     * Deletes a specified [Instant Key](https://docs.seam.co/capability-guides/instant-keys).
     *
     * @param string $instant_key_id ID of the Instant Key that you want to delete.
     * @return void OK
     */
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

    /**
     * Gets an [instant key](https://docs.seam.co/capability-guides/instant-keys).
     *
     * @param string $instant_key_id ID of the instant key to get.
     * @param string $instant_key_url URL of the instant key to get.
     * @return InstantKey OK
     */
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

    /**
     * Returns a list of all [instant keys](https://docs.seam.co/capability-guides/instant-keys).
     *
     * @param string $user_identity_id ID of the user identity by which you want to filter the list of Instant Keys.
     * @return array OK
     */
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

    /**
     * Configures the auto-lock setting for a specified [lock](https://docs.seam.co/low-level-apis/smart-locks).
     *
     * @param bool $auto_lock_enabled Whether to enable or disable auto-lock.
     * @param string $device_id ID of the lock for which you want to configure the auto-lock.
     * @param float $auto_lock_delay_seconds Delay in seconds before the lock automatically locks. Required when enabling auto-lock. Must be between 1 and 60.
     * @return ActionAttempt OK
     */
    public function configure_auto_lock(
        bool $auto_lock_enabled,
        string $device_id,
        ?float $auto_lock_delay_seconds = null,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($auto_lock_enabled !== null) {
            $request_payload["auto_lock_enabled"] = $auto_lock_enabled;
        }
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($auto_lock_delay_seconds !== null) {
            $request_payload[
                "auto_lock_delay_seconds"
            ] = $auto_lock_delay_seconds;
        }

        $res = $this->seam->request(
            "POST",
            "/locks/configure_auto_lock",
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

    /**
     * Returns a specified [lock](https://docs.seam.co/low-level-apis/smart-locks).
     *
     * @param string $device_id ID of the lock that you want to get.
     * @param string $name Name of the lock that you want to get.
     * @return Device OK
     * @deprecated Use `/devices/get` instead.
     */
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

    /**
     * Returns a list of all [locks](https://docs.seam.co/low-level-apis/smart-locks).
     *
     * @param string $connect_webview_id ID of the Connect Webview for which you want to list devices.
     * @param string $connected_account_id ID of the connected account for which you want to list devices.
     * @param array $connected_account_ids Array of IDs of the connected accounts for which you want to list devices.
     * @param string $created_before Timestamp by which to limit returned devices. Returns devices created before this timestamp.
     * @param mixed $custom_metadata_has Set of key:value [custom metadata](https://docs.seam.co/core-concepts/devices/adding-custom-metadata-to-a-device) pairs for which you want to list devices.
     * @param string $customer_key Customer key for which you want to list devices.
     * @param array $device_ids Array of device IDs for which you want to list devices.
     * @param string $device_type Device type of the locks that you want to list.
     * @param array $device_types Device types of the locks that you want to list.
     * @param float $limit Numerical limit on the number of devices to return.
     * @param string $manufacturer Manufacturer of the locks that you want to list.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned devices to include all records that satisfy a partial match using `device_id` (full or partial UUID prefix, minimum 4 characters), `connected_account_id`, `display_name`, `custom_metadata` or `location.location_name`.
     * @param string $space_id ID of the space for which you want to list devices.
     * @param string $unstable_location_id
     * @param string $user_identifier_key Your own internal user ID for the user for which you want to list devices.
     * @return array OK
     */
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

    /**
     * Locks a [lock](https://docs.seam.co/low-level-apis/smart-locks). See also [Locking and Unlocking Smart Locks](https://docs.seam.co/low-level-apis/smart-locks/lock-and-unlock).
     *
     * @param string $device_id ID of the lock that you want to lock.
     * @return ActionAttempt OK
     */
    public function lock_door(
        string $device_id,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
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

    /**
     * Unlocks a [lock](https://docs.seam.co/low-level-apis/smart-locks). See also [Locking and Unlocking Smart Locks](https://docs.seam.co/low-level-apis/smart-locks/lock-and-unlock).
     *
     * @param string $device_id ID of the lock that you want to unlock.
     * @return ActionAttempt OK
     */
    public function unlock_door(
        string $device_id,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
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

    /**
     * Simulates the entry of a code on a keypad. You can only perform this action for [August](https://docs.seam.co/device-and-system-integration-guides/august-locks) devices within [sandbox workspaces](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $code Code that you want to simulate entering on a keypad.
     * @param string $device_id ID of the device for which you want to simulate a keypad code entry.
     * @return ActionAttempt OK
     */
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

    /**
     * Simulates a manual lock action using a keypad. You can only perform this action for [August](https://docs.seam.co/device-and-system-integration-guides/august-locks) devices within [sandbox workspaces](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $device_id ID of the device for which you want to simulate a manual lock action using a keypad.
     * @return ActionAttempt OK
     */
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

    /**
     * Returns a list of all [noise sensors](https://docs.seam.co/capability-guides/noise-sensors).
     *
     * @param string $connect_webview_id ID of the Connect Webview for which you want to list devices.
     * @param string $connected_account_id ID of the connected account for which you want to list devices.
     * @param array $connected_account_ids Array of IDs of the connected accounts for which you want to list devices.
     * @param string $created_before Timestamp by which to limit returned devices. Returns devices created before this timestamp.
     * @param mixed $custom_metadata_has Set of key:value [custom metadata](https://docs.seam.co/core-concepts/devices/adding-custom-metadata-to-a-device) pairs for which you want to list devices.
     * @param string $customer_key Customer key for which you want to list devices.
     * @param array $device_ids Array of device IDs for which you want to list devices.
     * @param string $device_type Device type of the noise sensors that you want to list.
     * @param array $device_types Device types of the noise sensors that you want to list.
     * @param float $limit Numerical limit on the number of devices to return.
     * @param string $manufacturer Manufacturers of the noise sensors that you want to list.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned devices to include all records that satisfy a partial match using `device_id` (full or partial UUID prefix, minimum 4 characters), `connected_account_id`, `display_name`, `custom_metadata` or `location.location_name`.
     * @param string $space_id ID of the space for which you want to list devices.
     * @param string $unstable_location_id
     * @param string $user_identifier_key Your own internal user ID for the user for which you want to list devices.
     * @return array OK
     */
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

    /**
     * Creates a new [noise threshold](https://docs.seam.co/capability-guides/noise-sensors/configure-noise-threshold-settings) for a [noise sensor](https://docs.seam.co/capability-guides/noise-sensors). Thresholds represent the limits of noise tolerated at a property, which can be customized for each hour of the day. Each device has its own default thresholds, but you can use the Seam API to modify them.
     *
     * @param string $device_id ID of the device for which you want to create a noise threshold.
     * @param string $ends_daily_at Time at which the new noise threshold should become inactive daily.
     * @param string $starts_daily_at Time at which the new noise threshold should become active daily.
     * @param string $name Name of the new noise threshold.
     * @param float $noise_threshold_decibels Noise level in decibels for the new noise threshold.
     * @param float $noise_threshold_nrs Noise level in Noiseaware Noise Risk Score (NRS) for the new noise threshold. This parameter is only relevant for [Noiseaware sensors](https://docs.seam.co/device-and-system-integration-guides/noiseaware-sensors).
     * @return NoiseThreshold OK
     */
    public function create(
        string $device_id,
        string $ends_daily_at,
        string $starts_daily_at,
        ?string $name = null,
        ?float $noise_threshold_decibels = null,
        ?float $noise_threshold_nrs = null,
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

        $res = $this->seam->request(
            "POST",
            "/noise_sensors/noise_thresholds/create",
            json: (object) $request_payload,
        );

        return NoiseThreshold::from_json($res->noise_threshold);
    }

    /**
     * Deletes a [noise threshold](https://docs.seam.co/capability-guides/noise-sensors/configure-noise-threshold-settings) from a [noise sensor](https://docs.seam.co/capability-guides/noise-sensors).
     *
     * @param string $device_id ID of the device that contains the noise threshold that you want to delete.
     * @param string $noise_threshold_id ID of the noise threshold that you want to delete.
     * @return void OK
     */
    public function delete(string $device_id, string $noise_threshold_id): void
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }
        if ($noise_threshold_id !== null) {
            $request_payload["noise_threshold_id"] = $noise_threshold_id;
        }

        $this->seam->request(
            "POST",
            "/noise_sensors/noise_thresholds/delete",
            json: (object) $request_payload,
        );
    }

    /**
     * Returns a specified [noise threshold](https://docs.seam.co/capability-guides/noise-sensors/configure-noise-threshold-settings) for a [noise sensor](https://docs.seam.co/capability-guides/noise-sensors).
     *
     * @param string $noise_threshold_id ID of the noise threshold that you want to get.
     * @return NoiseThreshold OK
     */
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

    /**
     * Returns a list of all [noise thresholds](https://docs.seam.co/capability-guides/noise-sensors/configure-noise-threshold-settings) for a [noise sensor](https://docs.seam.co/capability-guides/noise-sensors).
     *
     * @param string $device_id ID of the device for which you want to list noise thresholds.
     * @return array OK
     */
    public function list(string $device_id): array
    {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
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

    /**
     * Updates a [noise threshold](https://docs.seam.co/capability-guides/noise-sensors/configure-noise-threshold-settings) for a [noise sensor](https://docs.seam.co/capability-guides/noise-sensors).
     *
     * @param string $device_id ID of the device that contains the noise threshold that you want to update.
     * @param string $noise_threshold_id ID of the noise threshold that you want to update.
     * @param string $ends_daily_at Time at which the noise threshold should become inactive daily.
     * @param string $name Name of the noise threshold that you want to update.
     * @param float $noise_threshold_decibels Noise level in decibels for the noise threshold.
     * @param float $noise_threshold_nrs Noise level in Noiseaware Noise Risk Score (NRS) for the noise threshold. This parameter is only relevant for [Noiseaware sensors](https://docs.seam.co/device-and-system-integration-guides/noiseaware-sensors).
     * @param string $starts_daily_at Time at which the noise threshold should become active daily.
     * @return void OK
     */
    public function update(
        string $device_id,
        string $noise_threshold_id,
        ?string $ends_daily_at = null,
        ?string $name = null,
        ?float $noise_threshold_decibels = null,
        ?float $noise_threshold_nrs = null,
        ?string $starts_daily_at = null,
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

    /**
     * Simulates the triggering of a [noise threshold](https://docs.seam.co/capability-guides/noise-sensors/configure-noise-threshold-settings) for a [noise sensor](https://docs.seam.co/capability-guides/noise-sensors) in a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     *
     * @param string $device_id ID of the device for which you want to simulate the triggering of a noise threshold.
     * @return void OK
     */
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

    /**
     * Deactivates a phone, which is useful, for example, if a user has lost their phone. For more information, see [App User Lost Phone Process](https://docs.seam.co/capability-guides/mobile-access/managing-phones-for-a-user-identity#app-user-lost-phone-process).
     *
     * @param string $device_id Device ID of the phone that you want to deactivate.
     * @return void OK
     */
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

    /**
     * Returns a specified [phone](https://docs.seam.co/capability-guides/mobile-access/managing-phones-for-a-user-identity).
     *
     * @param string $device_id Device ID of the phone that you want to get.
     * @return Phone OK
     */
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

    /**
     * Returns a list of all [phones](https://docs.seam.co/capability-guides/mobile-access/managing-phones-for-a-user-identity). To filter the list of returned phones by a specific owner user identity or credential, include the `owner_user_identity_id` or `acs_credential_id`, respectively, in the request body.
     *
     * @param string $acs_credential_id ID of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) by which you want to filter the list of returned phones.
     * @param string $owner_user_identity_id ID of the user identity that represents the owner by which you want to filter the list of returned phones.
     * @return array OK
     */
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

    /**
     * Creates a new simulated phone in a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces). See also [Creating a Simulated Phone for a User Identity](https://docs.seam.co/capability-guides/mobile-access/developing-in-a-sandbox-workspace#creating-a-simulated-phone-for-a-user-identity).
     *
     * @param string $user_identity_id ID of the user identity that you want to associate with the simulated phone.
     * @param mixed $assa_abloy_metadata ASSA ABLOY metadata that you want to associate with the simulated phone.
     * @param string $custom_sdk_installation_id ID of the custom SDK installation that you want to use for the simulated phone.
     * @param mixed $phone_metadata Metadata that you want to associate with the simulated phone.
     * @return Phone OK
     */
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

    /**
     * Adds [entrances](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) to a specific space.
     *
     * @param array $acs_entrance_ids IDs of the entrances that you want to add to the space.
     * @param string $space_id ID of the space to which you want to add entrances.
     * @return void OK
     */
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

    /**
     * Adds a [connected account](https://docs.seam.co/core-concepts/connected-accounts) to a specific space.
     *
     * @param string $connected_account_id ID of the connected account that you want to add to the space.
     * @param string $space_id ID of the space to which you want to add the connected account.
     * @return void OK
     */
    public function add_connected_account(
        string $connected_account_id,
        string $space_id,
    ): void {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }

        $this->seam->request(
            "POST",
            "/spaces/add_connected_account",
            json: (object) $request_payload,
        );
    }

    /**
     * Adds devices to a specific space.
     *
     * @param array $device_ids IDs of the devices that you want to add to the space.
     * @param string $space_id ID of the space to which you want to add devices.
     * @return void OK
     */
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

    /**
     * Creates a new space.
     *
     * @param string $name Name of the space that you want to create.
     * @param array $acs_entrance_ids IDs of the entrances that you want to add to the new space.
     * @param array $connected_account_ids IDs of connected accounts to associate with the new space. Persisted on seam.location_third_party_account so the UI can show which provider account(s) a space came from.
     * @param mixed $customer_data Reservation/stay-related defaults for the space.
     * @param string $customer_key Customer key for which you want to create the space.
     * @param array $device_ids IDs of the devices that you want to add to the new space.
     * @param string $space_key Unique key for the space within the workspace.
     * @return Space OK
     */
    public function create(
        string $name,
        ?array $acs_entrance_ids = null,
        ?array $connected_account_ids = null,
        mixed $customer_data = null,
        ?string $customer_key = null,
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
        if ($connected_account_ids !== null) {
            $request_payload["connected_account_ids"] = $connected_account_ids;
        }
        if ($customer_data !== null) {
            $request_payload["customer_data"] = $customer_data;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
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

    /**
     * Deletes a space.
     *
     * @param string $space_id ID of the space that you want to delete.
     * @return void OK
     */
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

    /**
     * Gets a space.
     *
     * @param string $space_id ID of the space that you want to get.
     * @param string $space_key Unique key of the space that you want to get.
     * @return Space OK
     */
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

    /**
     * Gets all related resources for one or more Spaces.
     *
     * @param array $exclude
     * @param array $include
     * @param array $space_ids IDs of the spaces that you want to get along with their related resources.
     * @param array $space_keys Keys of the spaces that you want to get along with their related resources.
     * @return Batch OK
     */
    public function get_related(
        ?array $exclude = null,
        ?array $include = null,
        ?array $space_ids = null,
        ?array $space_keys = null,
    ): Batch {
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

        $res = $this->seam->request(
            "POST",
            "/spaces/get_related",
            json: (object) $request_payload,
        );

        return Batch::from_json($res->batch);
    }

    /**
     * Returns a list of all spaces.
     *
     * @param string $customer_key Customer key for which you want to list spaces.
     * @param float $limit Maximum number of records to return per page.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned spaces to include all records that satisfy a partial match using `name`, `space_key`, or `customer_key`.
     * @param string $space_key Filter spaces by space_key.
     * @return array OK
     */
    public function list(
        ?string $customer_key = null,
        ?float $limit = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?string $space_key = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

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
        if ($space_key !== null) {
            $request_payload["space_key"] = $space_key;
        }

        $res = $this->seam->request(
            "POST",
            "/spaces/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(fn($r) => Space::from_json($r), $res->spaces);
    }

    /**
     * Removes [entrances](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) from a specific space.
     *
     * @param array $acs_entrance_ids IDs of the entrances that you want to remove from the space.
     * @param string $space_id ID of the space from which you want to remove entrances.
     * @return void OK
     */
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

    /**
     * Removes a [connected account](https://docs.seam.co/core-concepts/connected-accounts) from a specific space.
     *
     * @param string $connected_account_id ID of the connected account that you want to remove from the space.
     * @param string $space_id ID of the space from which you want to remove the connected account.
     * @return void OK
     */
    public function remove_connected_account(
        string $connected_account_id,
        string $space_id,
    ): void {
        $request_payload = [];

        if ($connected_account_id !== null) {
            $request_payload["connected_account_id"] = $connected_account_id;
        }
        if ($space_id !== null) {
            $request_payload["space_id"] = $space_id;
        }

        $this->seam->request(
            "POST",
            "/spaces/remove_connected_account",
            json: (object) $request_payload,
        );
    }

    /**
     * Removes devices from a specific space.
     *
     * @param array $device_ids IDs of the devices that you want to remove from the space.
     * @param string $space_id ID of the space from which you want to remove devices.
     * @return void OK
     */
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

    /**
     * Updates an existing space.
     *
     * @param array $acs_entrance_ids IDs of the entrances that you want to set for the space. If specified, this will replace all existing entrances.
     * @param mixed $customer_data Reservation/stay-related defaults for the space. Only the keys you provide are updated; omit a key to leave it unchanged. Pass null on a key to clear it.
     * @param array $device_ids IDs of the devices that you want to set for the space. If specified, this will replace all existing devices.
     * @param string $name Name of the space.
     * @param string $space_id ID of the space that you want to update.
     * @param string $space_key Unique key of the space that you want to update.
     * @return Space OK
     */
    public function update(
        ?array $acs_entrance_ids = null,
        mixed $customer_data = null,
        ?array $device_ids = null,
        ?string $name = null,
        ?string $space_id = null,
        ?string $space_key = null,
    ): Space {
        $request_payload = [];

        if ($acs_entrance_ids !== null) {
            $request_payload["acs_entrance_ids"] = $acs_entrance_ids;
        }
        if ($customer_data !== null) {
            $request_payload["customer_data"] = $customer_data;
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

    /**
     * Activates a specified [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $climate_preset_key Climate preset key of the climate preset that you want to activate.
     * @param string $device_id ID of the thermostat device for which you want to activate a climate preset.
     * @return ActionAttempt OK
     */
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

    /**
     * Sets a specified [thermostat](https://docs.seam.co/capability-guides/thermostats) to [cool mode](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings).
     *
     * @param string $device_id ID of the thermostat device that you want to set to cool mode.
     * @param float $cooling_set_point_celsius [Cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °C that you want to set for the thermostat. You must set one of the `cooling_set_point` parameters.
     * @param float $cooling_set_point_fahrenheit [Cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °F that you want to set for the thermostat. You must set one of the `cooling_set_point` parameters.
     * @return ActionAttempt OK
     */
    public function cool(
        string $device_id,
        ?float $cooling_set_point_celsius = null,
        ?float $cooling_set_point_fahrenheit = null,
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

    /**
     * Creates a [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $climate_preset_key Unique key to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
     * @param string $device_id ID of the thermostat device for which you want create a climate preset.
     * @param string $climate_preset_mode The climate preset mode for the thermostat, based on the available climate preset modes reported by the device.
     * @param float $cooling_set_point_celsius Temperature to which the thermostat should cool (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     * @param float $cooling_set_point_fahrenheit Temperature to which the thermostat should cool (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     * @param mixed $ecobee_metadata Metadata specific to the Ecobee climate, if applicable.
     * @param string $fan_mode_setting Desired [fan mode setting](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings#fan-mode-settings), such as `on`, `auto`, or `circulate`.
     * @param float $heating_set_point_celsius Temperature to which the thermostat should heat (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     * @param float $heating_set_point_fahrenheit Temperature to which the thermostat should heat (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     * @param string $hvac_mode_setting Desired [HVAC mode](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/hvac-mode) setting, such as `heat`, `cool`, `heat_cool`, or `off`.
     * @param bool $manual_override_allowed Indicates whether a person at the thermostat or using the API can change the thermostat's settings.
     * @param string $name User-friendly name to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
     * @return void OK
     */
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

    /**
     * Deletes a specified [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $climate_preset_key Climate preset key of the climate preset that you want to delete.
     * @param string $device_id ID of the thermostat device for which you want to delete a climate preset.
     * @return void OK
     */
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

    /**
     * Sets a specified [thermostat](https://docs.seam.co/capability-guides/thermostats) to [heat mode](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings).
     *
     * @param string $device_id ID of the thermostat device that you want to set to heat mode.
     * @param float $heating_set_point_celsius [Heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °C that you want to set for the thermostat. You must set one of the `heating_set_point` parameters.
     * @param float $heating_set_point_fahrenheit [Heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °F that you want to set for the thermostat. You must set one of the `heating_set_point` parameters.
     * @return ActionAttempt OK
     */
    public function heat(
        string $device_id,
        ?float $heating_set_point_celsius = null,
        ?float $heating_set_point_fahrenheit = null,
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

    /**
     * Sets a specified [thermostat](https://docs.seam.co/capability-guides/thermostats) to [heat-cool ("auto") mode](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings).
     *
     * @param string $device_id ID of the thermostat device that you want to set to heat-cool mode.
     * @param float $cooling_set_point_celsius [Cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °C that you want to set for the thermostat. You must set one of the `cooling_set_point` parameters.
     * @param float $cooling_set_point_fahrenheit [Cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °F that you want to set for the thermostat. You must set one of the `cooling_set_point` parameters.
     * @param float $heating_set_point_celsius [Heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °C that you want to set for the thermostat. You must set one of the `heating_set_point` parameters.
     * @param float $heating_set_point_fahrenheit [Heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °F that you want to set for the thermostat. You must set one of the `heating_set_point` parameters.
     * @return ActionAttempt OK
     */
    public function heat_cool(
        string $device_id,
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

    /**
     * Returns a list of all [thermostats](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $connect_webview_id ID of the Connect Webview for which you want to list devices.
     * @param string $connected_account_id ID of the connected account for which you want to list devices.
     * @param array $connected_account_ids Array of IDs of the connected accounts for which you want to list devices.
     * @param string $created_before Timestamp by which to limit returned devices. Returns devices created before this timestamp.
     * @param mixed $custom_metadata_has Set of key:value [custom metadata](https://docs.seam.co/core-concepts/devices/adding-custom-metadata-to-a-device) pairs for which you want to list devices.
     * @param string $customer_key Customer key for which you want to list devices.
     * @param array $device_ids Array of device IDs for which you want to list devices.
     * @param string $device_type Device type by which you want to filter thermostat devices.
     * @param array $device_types Array of device types by which you want to filter thermostat devices.
     * @param float $limit Numerical limit on the number of devices to return.
     * @param string $manufacturer Manufacturer by which you want to filter thermostat devices.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned devices to include all records that satisfy a partial match using `device_id` (full or partial UUID prefix, minimum 4 characters), `connected_account_id`, `display_name`, `custom_metadata` or `location.location_name`.
     * @param string $space_id ID of the space for which you want to list devices.
     * @param string $unstable_location_id
     * @param string $user_identifier_key Your own internal user ID for the user for which you want to list devices.
     * @return array OK
     */
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

    /**
     * Sets a specified [thermostat](https://docs.seam.co/capability-guides/thermostats) to ["off" mode](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings).
     *
     * @param string $device_id ID of the thermostat device that you want to set to off mode.
     * @return ActionAttempt OK
     */
    public function off(
        string $device_id,
        bool $wait_for_action_attempt = true,
    ): ActionAttempt {
        $request_payload = [];

        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
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

    /**
     * Sets a specified [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) as the ["fallback"](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets/setting-the-fallback-climate-preset) preset for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $climate_preset_key Climate preset key of the climate preset that you want to set as the fallback climate preset.
     * @param string $device_id ID of the thermostat device for which you want to set the fallback climate preset.
     * @return void OK
     */
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

    /**
     * Sets the [fan mode setting](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings#fan-mode-settings) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $device_id ID of the thermostat device for which you want to set the fan mode.
     * @param string $fan_mode Fan mode setting for the thermostat, such as `auto`, `on`, or `circulate`.
     * @param string $fan_mode_setting [Fan mode setting](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings#fan-mode-settings) that you want to set for the thermostat.
     * @return ActionAttempt OK
     */
    public function set_fan_mode(
        string $device_id,
        ?string $fan_mode = null,
        ?string $fan_mode_setting = null,
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

    /**
     * Sets the [HVAC mode](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $device_id ID of the thermostat device for which you want to set the HVAC mode.
     * @param string $hvac_mode_setting
     * @param float $cooling_set_point_celsius [Cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °C that you want to set for the thermostat. You must set one of the `cooling_set_point` parameters.
     * @param float $cooling_set_point_fahrenheit [Cooling set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °F that you want to set for the thermostat. You must set one of the `cooling_set_point` parameters.
     * @param float $heating_set_point_celsius [Heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °C that you want to set for the thermostat. You must set one of the `heating_set_point` parameters.
     * @param float $heating_set_point_fahrenheit [Heating set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °F that you want to set for the thermostat. You must set one of the `heating_set_point` parameters.
     * @return ActionAttempt OK
     */
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

    /**
     * Sets a [temperature threshold](https://docs.seam.co/capability-guides/thermostats/setting-and-monitoring-temperature-thresholds) for a specified thermostat. Seam emits a `thermostat.temperature_threshold_exceeded` event and adds a warning on a thermostat if it reports a temperature outside the threshold range.
     *
     * @param string $device_id ID of the thermostat device for which you want to set a temperature threshold.
     * @param float $lower_limit_celsius Lower temperature limit in in °C. Seam alerts you if the reported temperature is lower than this value. You can specify either `lower_limit` but not both.
     * @param float $lower_limit_fahrenheit Lower temperature limit in in °F. Seam alerts you if the reported temperature is lower than this value. You can specify either `lower_limit` but not both.
     * @param float $upper_limit_celsius Upper temperature limit in in °C. Seam alerts you if the reported temperature is higher than this value. You can specify either `upper_limit` but not both.
     * @param float $upper_limit_fahrenheit Upper temperature limit in in °C. Seam alerts you if the reported temperature is higher than this value. You can specify either `upper_limit` but not both.
     * @return void OK
     */
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

    /**
     * Updates a specified [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $climate_preset_key Unique key to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
     * @param string $device_id ID of the thermostat device for which you want to update a climate preset.
     * @param string $climate_preset_mode The climate preset mode for the thermostat, based on the available climate preset modes reported by the device.
     * @param float $cooling_set_point_celsius Temperature to which the thermostat should cool (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     * @param float $cooling_set_point_fahrenheit Temperature to which the thermostat should cool (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     * @param mixed $ecobee_metadata Metadata specific to the Ecobee climate, if applicable.
     * @param string $fan_mode_setting Desired [fan mode setting](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings#fan-mode-settings), such as `on`, `auto`, or `circulate`.
     * @param float $heating_set_point_celsius Temperature to which the thermostat should heat (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     * @param float $heating_set_point_fahrenheit Temperature to which the thermostat should heat (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     * @param string $hvac_mode_setting Desired [HVAC mode](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/hvac-mode) setting, such as `heat`, `cool`, `heat_cool`, or `off`.
     * @param bool $manual_override_allowed Indicates whether a person at the thermostat can change the thermostat's settings. See [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
     * @param string $name User-friendly name to identify the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets).
     * @return void OK
     */
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

    /**
     * Updates the thermostat weekly program for a thermostat device. To configure a weekly program, specify the ID of the daily program that you want to use for each day of the week. When you update a weekly program, the set of programs that you specify overwrites any previous weekly program for the thermostat.
     *
     * @param string $device_id ID of the thermostat device for which you want to update the weekly program.
     * @param string $friday_program_id ID of the thermostat daily program to run on Fridays.
     * @param string $monday_program_id ID of the thermostat daily program to run on Mondays.
     * @param string $saturday_program_id ID of the thermostat daily program to run on Saturdays.
     * @param string $sunday_program_id ID of the thermostat daily program to run on Sundays.
     * @param string $thursday_program_id ID of the thermostat daily program to run on Thursdays.
     * @param string $tuesday_program_id ID of the thermostat daily program to run on Tuesdays.
     * @param string $wednesday_program_id ID of the thermostat daily program to run on Wednesdays.
     * @return ActionAttempt OK
     */
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

    /**
     * Creates a new thermostat daily program. A daily program consists of a set of periods, where each period includes a start time and the key of a configured climate preset. Once you have defined a daily program, you can assign it to one or more days within a weekly program.
     *
     * @param string $device_id ID of the thermostat device for which you want to create a daily program.
     * @param string $name Name of the thermostat daily program.
     * @param array $periods Array of thermostat daily program periods.
     * @return ThermostatDailyProgram OK
     */
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

    /**
     * Deletes a thermostat daily program.
     *
     * @param string $thermostat_daily_program_id ID of the thermostat daily program that you want to delete.
     * @return void OK
     */
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

    /**
     * Updates a specified thermostat daily program. The periods that you specify overwrite any existing periods for the daily program.
     *
     * @param string $name Name of the thermostat daily program that you want to update.
     * @param array $periods Array of thermostat daily program periods. The periods that you specify overwrite any existing periods for the daily program.
     * @param string $thermostat_daily_program_id ID of the thermostat daily program that you want to update.
     * @return ActionAttempt OK
     */
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

    /**
     * Creates a new [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $climate_preset_key Key of the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) to use for the new thermostat schedule.
     * @param string $device_id ID of the thermostat device for which you want to create a schedule.
     * @param string $ends_at Date and time at which the new thermostat schedule ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @param string $starts_at Date and time at which the new thermostat schedule starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @param bool $is_override_allowed Indicates whether a person at the thermostat or using the API can change the thermostat's settings while the new schedule is active. See also [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
     * @param int $max_override_period_minutes Number of minutes for which a person at the thermostat or using the API can change the thermostat's settings after the activation of the scheduled climate preset. See also [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
     * @param string $name Name of the thermostat schedule.
     * @return ThermostatSchedule OK
     */
    public function create(
        string $climate_preset_key,
        string $device_id,
        string $ends_at,
        string $starts_at,
        ?bool $is_override_allowed = null,
        ?int $max_override_period_minutes = null,
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

    /**
     * Deletes a [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $thermostat_schedule_id ID of the thermostat schedule that you want to delete.
     * @return void OK
     */
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

    /**
     * Returns a specified [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
     *
     * @param string $thermostat_schedule_id ID of the thermostat schedule that you want to get.
     * @return ThermostatSchedule OK
     */
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

    /**
     * Returns a list of all [thermostat schedules](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules) for a specified [thermostat](https://docs.seam.co/capability-guides/thermostats).
     *
     * @param string $device_id ID of the thermostat device for which you want to list schedules.
     * @param string $user_identifier_key User identifier key by which to filter the list of returned thermostat schedules.
     * @return array OK
     */
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

    /**
     * Updates a specified [thermostat schedule](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules).
     *
     * @param string $thermostat_schedule_id ID of the thermostat schedule that you want to update.
     * @param string $climate_preset_key Key of the [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) to use for the thermostat schedule.
     * @param string $ends_at Date and time at which the thermostat schedule ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @param bool $is_override_allowed Indicates whether a person at the thermostat or using the API can change the thermostat's settings while the schedule is active. See also [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
     * @param int $max_override_period_minutes Number of minutes for which a person at the thermostat or using the API can change the thermostat's settings after the activation of the scheduled climate preset. See also [Specifying Manual Override Permissions](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-thermostat-schedules#specifying-manual-override-permissions).
     * @param string $name Name of the thermostat schedule.
     * @param string $starts_at Date and time at which the thermostat schedule starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @return void OK
     */
    public function update(
        string $thermostat_schedule_id,
        ?string $climate_preset_key = null,
        ?string $ends_at = null,
        ?bool $is_override_allowed = null,
        ?int $max_override_period_minutes = null,
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

    /**
     * Simulates having adjusted the [HVAC mode](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/hvac-mode) for a [thermostat](https://docs.seam.co/capability-guides/thermostats). Only applicable for [sandbox devices](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces). See also [Testing Your Thermostat App with Simulate Endpoints](https://docs.seam.co/capability-guides/thermostats/testing-your-thermostat-app-with-simulate-endpoints).
     *
     * @param string $device_id ID of the thermostat device for which you want to simulate having adjusted the HVAC mode.
     * @param string $hvac_mode HVAC mode that you want to simulate.
     * @param float $cooling_set_point_celsius Cooling [set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °C that you want to simulate. You must set `cooling_set_point_celsius` or `cooling_set_point_fahrenheit`.
     * @param float $cooling_set_point_fahrenheit Cooling [set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °F that you want to simulate. You must set `cooling_set_point_fahrenheit` or `cooling_set_point_celsius`.
     * @param float $heating_set_point_celsius Heating [set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °C that you want to simulate. You must set `heating_set_point_celsius` or `heating_set_point_fahrenheit`.
     * @param float $heating_set_point_fahrenheit Heating [set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points) in °F that you want to simulate. You must set `heating_set_point_fahrenheit` or `heating_set_point_celsius`.
     * @return void OK
     */
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

    /**
     * Simulates a [thermostat](https://docs.seam.co/capability-guides/thermostats) reaching a specified temperature. Only applicable for [sandbox devices](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces). See also [Testing Your Thermostat App with Simulate Endpoints](https://docs.seam.co/capability-guides/thermostats/testing-your-thermostat-app-with-simulate-endpoints).
     *
     * @param string $device_id ID of the thermostat device that you want to simulate reaching a specified temperature.
     * @param float $temperature_celsius Temperature in °C that you want simulate the thermostat reaching. You must set `temperature_celsius` or `temperature_fahrenheit`.
     * @param float $temperature_fahrenheit Temperature in °F that you want simulate the thermostat reaching. You must set `temperature_fahrenheit` or `temperature_celsius`.
     * @return void OK
     */
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
    public UserIdentitiesUnmanagedClient $unmanaged;
    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
        $this->unmanaged = new UserIdentitiesUnmanagedClient($seam);
    }

    /**
     * Adds a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) to a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * You must specify either `user_identity_id` or `user_identity_key` to identify the user identity.
     *
     * If `user_identity_key` is provided, but the user identity doesn't exist, a new user identity will be created automatically using information from the ACS user.
     *
     * @param string $acs_user_id ID of the access system user that you want to add to the user identity.
     * @param string $user_identity_id ID of the user identity to which you want to add an access system user.
     * @param string $user_identity_key Key of the user identity to which you want to add an access system user.
     * @return void OK
     */
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

    /**
     * Creates a new [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param array $acs_system_ids List of access system IDs to associate with the new user identity through access system users. If there's no user with the same email address or phone number in the specified access systems, a new access system user is created. If there is an existing user with the same email or phone number in the specified access systems, the user is linked to the user identity.
     * @param string $email_address Unique email address for the new user identity.
     * @param string $full_name Full name of the user associated with the new user identity.
     * @param string $phone_number Unique phone number for the new user identity in E.164 format (for example, +15555550100).
     * @param string $user_identity_key Unique key for the new user identity.
     * @return UserIdentity OK
     */
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

    /**
     * Deletes a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity). This deletes the user identity and all associated resources, including any [credentials](https://docs.seam.co/api/acs/credentials), [acs users](https://docs.seam.co/api/acs/users) and [client sessions](https://docs.seam.co/api/client_sessions).
     *
     * @param string $user_identity_id ID of the user identity that you want to delete.
     * @return void OK
     */
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

    /**
     * Generates a new [instant key](https://docs.seam.co/capability-guides/instant-keys) for a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param string $user_identity_id ID of the user identity for which you want to generate an instant key.
     * @param string $customization_profile_id
     * @param float $max_use_count Maximum number of times the instant key can be used. Default: 1.
     * @return InstantKey OK
     */
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

    /**
     * Returns a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param string $user_identity_id ID of the user identity that you want to get.
     * @param string $user_identity_key
     * @return UserIdentity OK
     */
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

    /**
     * Grants a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) access to a specified [device](https://docs.seam.co/core-concepts/devices/).
     *
     * @param string $device_id ID of the managed device to which you want to grant access to the user identity.
     * @param string $user_identity_id ID of the user identity that you want to grant access to a device.
     * @return void OK
     */
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

    /**
     * Returns a list of all [user identities](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param string $created_before Timestamp by which to limit returned user identities. Returns user identities created before this timestamp.
     * @param string $credential_manager_acs_system_id `acs_system_id` of the credential manager by which you want to filter the list of user identities.
     * @param int $limit Maximum number of records to return per page.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned user identities to include all records that satisfy a partial match using `full_name`, `phone_number`, `email_address` or `user_identity_id`.
     * @param array $user_identity_ids Array of user identity IDs by which to filter the list of user identities.
     * @return array OK
     */
    public function list(
        ?string $created_before = null,
        ?string $credential_manager_acs_system_id = null,
        ?int $limit = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?array $user_identity_ids = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($created_before !== null) {
            $request_payload["created_before"] = $created_before;
        }
        if ($credential_manager_acs_system_id !== null) {
            $request_payload[
                "credential_manager_acs_system_id"
            ] = $credential_manager_acs_system_id;
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
        if ($user_identity_ids !== null) {
            $request_payload["user_identity_ids"] = $user_identity_ids;
        }

        $res = $this->seam->request(
            "POST",
            "/user_identities/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => UserIdentity::from_json($r),
            $res->user_identities,
        );
    }

    /**
     * Returns a list of all [devices](https://docs.seam.co/core-concepts/devices) associated with a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity). This includes devices derived from the access grants assigned to the user identity and devices directly linked to the user identity.
     *
     * @param string $user_identity_id ID of the user identity for which you want to retrieve all accessible devices.
     * @return array OK
     */
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

    /**
     * Returns a list of all [ACS entrances](https://docs.seam.co/api/acs/entrances) accessible to a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity). This includes entrances derived from the access grants assigned to the user identity and entrances accessible through ACS users linked to the user identity.
     *
     * @param string $user_identity_id ID of the user identity for which you want to retrieve all accessible entrances.
     * @return array OK
     */
    public function list_accessible_entrances(string $user_identity_id): array
    {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->request(
            "POST",
            "/user_identities/list_accessible_entrances",
            json: (object) $request_payload,
        );

        return array_map(
            fn($r) => AcsEntrance::from_json($r),
            $res->acs_entrances,
        );
    }

    /**
     * Returns a list of all [access systems](https://docs.seam.co/low-level-apis/access-systems) associated with a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param string $user_identity_id ID of the user identity for which you want to retrieve all access systems.
     * @return array OK
     */
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

    /**
     * Returns a list of all [access system users](https://docs.seam.co/low-level-apis/access-systems/user-management) assigned to a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param string $user_identity_id ID of the user identity for which you want to retrieve all access system users.
     * @return array OK
     */
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

    /**
     * Removes a specified [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) from a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param string $acs_user_id ID of the access system user that you want to remove from the user identity..
     * @param string $user_identity_id ID of the user identity from which you want to remove an access system user.
     * @return void OK
     */
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

    /**
     * Revokes access to a specified [device](https://docs.seam.co/core-concepts/devices/) from a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param string $device_id ID of the managed device to which you want to revoke access from the user identity.
     * @param string $user_identity_id ID of the user identity from which you want to revoke access to a device.
     * @return void OK
     */
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

    /**
     * Updates a specified [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity).
     *
     * @param string $user_identity_id ID of the user identity that you want to update.
     * @param string $email_address Unique email address for the user identity.
     * @param string $full_name Full name of the user associated with the user identity.
     * @param string $phone_number Unique phone number for the user identity.
     * @param string $user_identity_key Unique key for the user identity.
     * @return void OK
     */
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

class UserIdentitiesUnmanagedClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    /**
     * Returns a specified unmanaged [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) (where is_managed = false).
     *
     * @param string $user_identity_id ID of the unmanaged user identity that you want to get.
     * @return UnmanagedUserIdentity OK
     */
    public function get(string $user_identity_id): UnmanagedUserIdentity
    {
        $request_payload = [];

        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }

        $res = $this->seam->request(
            "POST",
            "/user_identities/unmanaged/get",
            json: (object) $request_payload,
        );

        return UnmanagedUserIdentity::from_json($res->user_identity);
    }

    /**
     * Returns a list of all unmanaged [user identities](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) (where is_managed = false).
     *
     * @param string $created_before Timestamp by which to limit returned unmanaged user identities. Returns user identities created before this timestamp.
     * @param int $limit Maximum number of records to return per page.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned unmanaged user identities to include all records that satisfy a partial match using `full_name`, `phone_number`, `email_address`,  `user_identity_id` or `acs_system_id`.
     * @return array OK
     */
    public function list(
        ?string $created_before = null,
        ?int $limit = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

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

        $res = $this->seam->request(
            "POST",
            "/user_identities/unmanaged/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => UnmanagedUserIdentity::from_json($r),
            $res->user_identities,
        );
    }

    /**
     * Updates an unmanaged [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) to make it managed.
     *
     * This endpoint can only be used to convert unmanaged user identities to managed ones by setting `is_managed` to `true`. It cannot be used to convert managed user identities back to unmanaged.
     *
     * @param bool $is_managed Must be set to true to convert the unmanaged user identity to managed.
     * @param string $user_identity_id ID of the unmanaged user identity that you want to update.
     * @param string $user_identity_key Unique key for the user identity. If not provided, the existing key will be preserved.
     * @return void OK
     */
    public function update(
        bool $is_managed,
        string $user_identity_id,
        ?string $user_identity_key = null,
    ): void {
        $request_payload = [];

        if ($is_managed !== null) {
            $request_payload["is_managed"] = $is_managed;
        }
        if ($user_identity_id !== null) {
            $request_payload["user_identity_id"] = $user_identity_id;
        }
        if ($user_identity_key !== null) {
            $request_payload["user_identity_key"] = $user_identity_key;
        }

        $this->seam->request(
            "POST",
            "/user_identities/unmanaged/update",
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

    /**
     * Creates a new [webhook](https://docs.seam.co/developer-tools/webhooks).
     *
     * @param string $url URL for the new webhook.
     * @param array $event_types Types of events that you want the new webhook to receive.
     * @return Webhook OK
     */
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

    /**
     * Deletes a specified [webhook](https://docs.seam.co/developer-tools/webhooks).
     *
     * @param string $webhook_id ID of the webhook that you want to delete.
     * @return void OK
     */
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

    /**
     * Gets a specified [webhook](https://docs.seam.co/developer-tools/webhooks).
     *
     * @param string $webhook_id ID of the webhook that you want to get.
     * @return Webhook OK
     */
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

    /**
     * Returns a list of all [webhooks](https://docs.seam.co/developer-tools/webhooks).
     *
     * @return array OK
     */
    public function list(): array
    {
        $res = $this->seam->request("POST", "/webhooks/list");

        return array_map(fn($r) => Webhook::from_json($r), $res->webhooks);
    }

    /**
     * Updates a specified [webhook](https://docs.seam.co/developer-tools/webhooks).
     *
     * @param array $event_types Types of events that you want the webhook to receive.
     * @param string $webhook_id ID of the webhook that you want to update.
     * @return void OK
     */
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

    /**
     * Creates a new [workspace](https://docs.seam.co/core-concepts/workspaces).
     *
     * @param string $name Name of the new workspace.
     * @param string $company_name Company name for the new workspace.
     * @param string $connect_partner_name Connect partner name for the new workspace.
     * @param mixed $connect_webview_customization [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews) customizations for the new workspace. See also [Customize the Look and Feel of Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-look-and-feel-of-your-connect-webviews).
     * @param bool $is_sandbox Indicates whether the new workspace is a [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces).
     * @param string $organization_id ID of the organization to associate with the new workspace.
     * @param string $webview_logo_shape
     * @param string $webview_primary_button_color
     * @param string $webview_primary_button_text_color
     * @param string $webview_success_message
     * @return Workspace OK
     */
    public function create(
        string $name,
        ?string $company_name = null,
        ?string $connect_partner_name = null,
        mixed $connect_webview_customization = null,
        ?bool $is_sandbox = null,
        ?string $organization_id = null,
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
        if ($organization_id !== null) {
            $request_payload["organization_id"] = $organization_id;
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

    /**
     * Returns the [workspace](https://docs.seam.co/core-concepts/workspaces) associated with the authentication value.
     *
     * @return Workspace OK
     */
    public function get(): Workspace
    {
        $res = $this->seam->request("POST", "/workspaces/get");

        return Workspace::from_json($res->workspace);
    }

    /**
     * Returns a list of [workspaces](https://docs.seam.co/core-concepts/workspaces) associated with the authentication value.
     *
     * @return array OK
     */
    public function list(): array
    {
        $res = $this->seam->request("POST", "/workspaces/list");

        return array_map(fn($r) => Workspace::from_json($r), $res->workspaces);
    }

    /**
     * Resets the [sandbox workspace](https://docs.seam.co/core-concepts/workspaces#sandbox-workspaces) associated with the authentication value. Note that this endpoint is only available for sandbox workspaces.
     *
     * @return ActionAttempt OK
     */
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

    /**
     * Updates the [workspace](https://docs.seam.co/core-concepts/workspaces) associated with the authentication value.
     *
     * @param string $connect_partner_name Connect partner name for the workspace.
     * @param mixed $connect_webview_customization [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews) customizations for the workspace. See also [Customize the Look and Feel of Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-look-and-feel-of-your-connect-webviews).
     * @param bool $is_publishable_key_auth_enabled Indicates whether publishable key authentication is enabled for this workspace.
     * @param bool $is_suspended Indicates whether the workspace is suspended.
     * @param string $name Name of the workspace.
     * @param string $organization_id ID of the organization to assign the workspace to. The authenticated user must be the owner of the workspace and an admin of the target organization.
     * @return void OK
     */
    public function update(
        ?string $connect_partner_name = null,
        mixed $connect_webview_customization = null,
        ?bool $is_publishable_key_auth_enabled = null,
        ?bool $is_suspended = null,
        ?string $name = null,
        ?string $organization_id = null,
    ): void {
        $request_payload = [];

        if ($connect_partner_name !== null) {
            $request_payload["connect_partner_name"] = $connect_partner_name;
        }
        if ($connect_webview_customization !== null) {
            $request_payload[
                "connect_webview_customization"
            ] = $connect_webview_customization;
        }
        if ($is_publishable_key_auth_enabled !== null) {
            $request_payload[
                "is_publishable_key_auth_enabled"
            ] = $is_publishable_key_auth_enabled;
        }
        if ($is_suspended !== null) {
            $request_payload["is_suspended"] = $is_suspended;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($organization_id !== null) {
            $request_payload["organization_id"] = $organization_id;
        }

        $this->seam->request(
            "POST",
            "/workspaces/update",
            json: (object) $request_payload,
        );
    }
}
