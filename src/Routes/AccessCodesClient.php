<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\NullValue;
use Seam\Resources\AccessCode;

class AccessCodesClient
{
    private ClientInterface $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;
    public AccessCodesSimulateClient $simulate;
    public AccessCodesUnmanagedClient $unmanaged;
    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(ClientInterface $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
        $this->simulate = new AccessCodesSimulateClient($client, $defaults);
        $this->unmanaged = new AccessCodesUnmanagedClient($client, $defaults);
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

        $request_payload["device_id"] = $device_id;
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

        $res = Body::decode(
            $this->client->request("POST", "/access_codes/create", [
                "json" => (object) $request_payload,
            ]),
        );

        return AccessCode::from_json(
            Body::read($res, "access_code", "/access_codes/create"),
        );
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

        $request_payload["device_ids"] = $device_ids;
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

        $res = Body::decode(
            $this->client->request("PUT", "/access_codes/create_multiple", [
                "json" => (object) $request_payload,
            ]),
        );

        return array_map(
            fn($r) => AccessCode::from_json($r),
            Body::read_list(
                $res,
                "access_codes",
                "/access_codes/create_multiple",
            ),
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

        $request_payload["access_code_id"] = $access_code_id;
        if ($device_id !== null) {
            $request_payload["device_id"] = $device_id;
        }

        $this->client->request("DELETE", "/access_codes/delete", [
            "query" => $request_payload,
        ]);
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

        $request_payload["device_id"] = $device_id;

        $res = Body::decode(
            $this->client->request("GET", "/access_codes/generate_code", [
                "query" => $request_payload,
            ]),
        );

        return AccessCode::from_json(
            Body::read($res, "generated_code", "/access_codes/generate_code"),
        );
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
        if ($access_code_id === null && $code === null && $device_id === null) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /access_codes/get",
            );
        }
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

        $res = Body::decode(
            $this->client->request("GET", "/access_codes/get", [
                "query" => $request_payload,
            ]),
        );

        return AccessCode::from_json(
            Body::read($res, "access_code", "/access_codes/get"),
        );
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
     * @param string|NullValue $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned access codes to include all records that satisfy a partial match using `name`, `code` or `access_code_id`.
     * @param string $user_identifier_key Your user ID for the user by which to filter access codes.
     * @param callable|null $on_response Called with the raw response envelope, used by the paginator to read the pagination metadata.
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
        string|NullValue|null $page_cursor = null,
        ?string $search = null,
        ?string $user_identifier_key = null,
        ?callable $on_response = null,
    ): array {
        if (
            $access_code_ids === null &&
            $access_grant_id === null &&
            $access_grant_key === null &&
            $access_method_id === null &&
            $customer_key === null &&
            $device_id === null &&
            $limit === null &&
            $page_cursor === null &&
            $search === null &&
            $user_identifier_key === null
        ) {
            throw new \InvalidArgumentException(
                "At least one parameter is required for /access_codes/list",
            );
        }
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

        $res = Body::decode(
            $this->client->request("GET", "/access_codes/list", [
                "query" => $request_payload,
            ]),
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => AccessCode::from_json($r),
            Body::read_list($res, "access_codes", "/access_codes/list"),
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

        $request_payload["access_code_id"] = $access_code_id;

        $res = Body::decode(
            $this->client->request(
                "POST",
                "/access_codes/pull_backup_access_code",
                ["json" => (object) $request_payload],
            ),
        );

        return AccessCode::from_json(
            Body::read(
                $res,
                "access_code",
                "/access_codes/pull_backup_access_code",
            ),
        );
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

        $request_payload["device_id"] = $device_id;
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

        $this->client->request(
            "POST",
            "/access_codes/report_device_constraints",
            ["json" => (object) $request_payload],
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
     * @param string $name Name of the new access code. Enables administrators and users to identify the access code easily, especially when there are numerous access codes.

Note that the name provided on Seam is used to identify the code on Seam and is not necessarily the name that will appear in the lock provider's app or on the device. This is because lock providers may have constraints on names, such as length, uniqueness, or characters that can be used. In addition, some lock providers may break down names into components such as `first_name` and `last_name`.

To provide a consistent experience, Seam identifies the code on Seam by its name but may modify the name that appears on the lock provider's app or on the device. For example, Seam may add additional characters or truncate the name to meet provider constraints.

To help your users identify codes set by Seam, Seam provides the name exactly as it appears on the lock provider's app or on the device as a separate property called `appearance`. This is an object with a `name` property and, optionally, `first_name` and `last_name` properties (for providers that break down a name into components).
     * @param string $starts_at Date and time at which the validity of the new access code starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
     * @param string $type Type to which you want to convert the access code. To convert a time-bound access code to an ongoing access code, set `type` to `ongoing`. See also [Changing a time-bound access code to permanent access](https://docs.seam.co/low-level-apis/smart-locks/access-codes/modifying-access-codes#special-case-2-changing-a-time-bound-access-code-to-permanent-access).
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
        ?string $name = null,
        ?string $starts_at = null,
        ?string $type = null,
    ): void {
        $request_payload = [];

        $request_payload["access_code_id"] = $access_code_id;
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
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }
        if ($type !== null) {
            $request_payload["type"] = $type;
        }

        $this->client->request("PUT", "/access_codes/update", [
            "json" => (object) $request_payload,
        ]);
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

        $request_payload["common_code_key"] = $common_code_key;
        if ($ends_at !== null) {
            $request_payload["ends_at"] = $ends_at;
        }
        if ($name !== null) {
            $request_payload["name"] = $name;
        }
        if ($starts_at !== null) {
            $request_payload["starts_at"] = $starts_at;
        }

        $this->client->request("PATCH", "/access_codes/update_multiple", [
            "json" => (object) $request_payload,
        ]);
    }
}
