<?php

namespace Seam\Routes;

use Seam\Resources\InstantKey;
use Seam\SeamClient;

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
