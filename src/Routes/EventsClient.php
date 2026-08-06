<?php

namespace Seam\Routes;

use Seam\Http\SeamHttpClient;
use Seam\Resources\Event;

class EventsClient
{
    private SeamHttpClient $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;

    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(SeamHttpClient $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
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

        $res = $this->client->request(
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

        $res = $this->client->request(
            "POST",
            "/events/list",
            json: (object) $request_payload,
        );

        return array_map(fn($r) => Event::from_json($r), $res->events);
    }
}
