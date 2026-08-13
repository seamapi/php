<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Resources\UnmanagedAccessCode;

class AccessCodesUnmanagedClient
{
    private ClientInterface $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;

    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(ClientInterface $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
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

        $request_payload["access_code_id"] = $access_code_id;
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

        $this->client->request(
            "PATCH",
            "/access_codes/unmanaged/convert_to_managed",
            ["json" => (object) $request_payload],
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

        $request_payload["access_code_id"] = $access_code_id;

        $this->client->request("DELETE", "/access_codes/unmanaged/delete", [
            "json" => (object) $request_payload,
        ]);
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

        $res = Body::decode(
            $this->client->request("GET", "/access_codes/unmanaged/get", [
                "json" => (object) $request_payload,
            ]),
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
     * @param callable|null $on_response Called with the raw response envelope, used by the paginator to read the pagination metadata.
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

        $request_payload["device_id"] = $device_id;
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
            $this->client->request("GET", "/access_codes/unmanaged/list", [
                "json" => (object) $request_payload,
            ]),
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

        $request_payload["access_code_id"] = $access_code_id;
        $request_payload["is_managed"] = $is_managed;
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

        $this->client->request("PATCH", "/access_codes/unmanaged/update", [
            "json" => (object) $request_payload,
        ]);
    }
}
