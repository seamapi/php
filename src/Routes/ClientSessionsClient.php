<?php

namespace Seam\Routes;

use Seam\Http\SeamHttpClient;
use Seam\Resources\ClientSession;

class ClientSessionsClient
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

        $res = $this->client->request(
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

        $request_payload["client_session_id"] = $client_session_id;

        $this->client->request(
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

        $res = $this->client->request(
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

        $res = $this->client->request(
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

        $this->client->request(
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

        $res = $this->client->request(
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

        $request_payload["client_session_id"] = $client_session_id;

        $this->client->request(
            "POST",
            "/client_sessions/revoke",
            json: (object) $request_payload,
        );
    }
}
