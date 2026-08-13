<?php

namespace Seam\Resources {
    /**
     * Represents a [client session](https://docs.seam.co/core-concepts/authentication/client-session-tokens). If you want to restrict your users' access to their own devices, use client sessions.
     *
     * You create each client session with a custom `user_identifier_key`. Normally, the `user_identifier_key` is a user ID that your application provides.
     *
     * When calling the Seam API from your backend using an API key, you can pass the `user_identifier_key` as a parameter to limit results to the associated client session. For example, `/devices/list?user_identifier_key=123` only returns devices associated with the client session created with the `user_identifier_key` `123`.
     *
     * A client session has a token that you can use with the Seam JavaScript SDK to make requests from the client (browser) directly to the Seam API. The token restricts the user's access to only the devices that they own.
     *
     * See also [Get Started with React](https://docs.seam.co/ui-components/overview/getting-started-with-seam-components/get-started-with-react-components-and-client-session-tokens).
     */
    class ClientSession
    {
        public static function from_json(mixed $json): ClientSession|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                client_session_id: $json->client_session_id ?? null,
                connect_webview_ids: $json->connect_webview_ids ?? null,
                connected_account_ids: $json->connected_account_ids ?? null,
                created_at: $json->created_at ?? null,
                customer_key: $json->customer_key ?? null,
                device_count: $json->device_count ?? null,
                expires_at: $json->expires_at ?? null,
                token: $json->token ?? null,
                user_identifier_key: $json->user_identifier_key ?? null,
                user_identity_id: $json->user_identity_id ?? null,
                user_identity_ids: $json->user_identity_ids ?? null,
                workspace_id: $json->workspace_id ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the client session.
             */
            public string|null $client_session_id,
            /**
             * IDs of the [Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews) associated with the [client session](https://docs.seam.co/core-concepts/authentication/client-session-tokens).
             */
            public array|null $connect_webview_ids,
            /**
             * IDs of the [connected accounts](https://docs.seam.co/core-concepts/connected-accounts) associated with the [client session](https://docs.seam.co/core-concepts/authentication/client-session-tokens).
             */
            public array|null $connected_account_ids,
            /**
             * Date and time at which the [client session](https://docs.seam.co/core-concepts/authentication/client-session-tokens) was created.
             */
            public string|null $created_at,
            /**
             * Customer key associated with the [client session](https://docs.seam.co/core-concepts/authentication/client-session-tokens).
             */
            public string|null $customer_key = null,
            /**
             * Number of devices associated with the [client session](https://docs.seam.co/core-concepts/authentication/client-session-tokens).
             */
            public float|null $device_count,
            /**
             * Date and time at which the [client session](https://docs.seam.co/core-concepts/authentication/client-session-tokens) expires.
             */
            public string|null $expires_at,
            /**
             * Client session token associated with the [client session](https://docs.seam.co/core-concepts/authentication/client-session-tokens).
             */
            public string|null $token,
            /**
             * Your user ID for the user associated with the [client session](https://docs.seam.co/core-concepts/authentication/client-session-tokens).
             */
            public string|null $user_identifier_key,
            /**
             * ID of the [user identity](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) associated with the client session.
             */
            public string|null $user_identity_id = null,
            /**
             * IDs of the [user identities](https://docs.seam.co/capability-guides/mobile-access/managing-mobile-app-user-accounts-with-user-identities#what-is-a-user-identity) associated with the client session.
             *
             * @deprecated Use `user_identity_id` instead.
             */
            public array|null $user_identity_ids,
            /**
             * ID of the workspace associated with the client session.
             */
            public string|null $workspace_id,
        ) {}
    }
}
