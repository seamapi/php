<?php

namespace Seam\Routes;

use Seam\Resources\AccessMethod;
use Seam\Resources\ActionAttempt;
use Seam\Resources\Batch;
use Seam\SeamClient;

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
