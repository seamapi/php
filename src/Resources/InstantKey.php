<?php

namespace Seam\Resources {
    /**
     * Represents a Seam Instant Key. For issuing Bluetooth mobile keys, Instant Keys are the fastest way to share access. With a single API call, you can create a mobile key and send it through text or email or embed it in your own app.
     *
     * There’s no app to install, nor account to create. Your user just taps a link and gets a lightweight, native-feeling experience using iOS App Clip or Instant Apps on Android. Further, Instant Keys work offline, so even in areas with poor cellular or Wi-Fi, like elevator banks or concrete-walled hallways, the Instant Keys still work.
     */
    class InstantKey
    {
        public static function from_json(mixed $json): InstantKey|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                client_session_id: $json->client_session_id ?? null,
                created_at: $json->created_at ?? null,
                expires_at: $json->expires_at ?? null,
                instant_key_id: $json->instant_key_id ?? null,
                instant_key_url: $json->instant_key_url ?? null,
                user_identity_id: $json->user_identity_id ?? null,
                workspace_id: $json->workspace_id ?? null,
                customization: isset($json->customization)
                    ? \Seam\Resources\InstantKey\Customization::from_json(
                        $json->customization,
                    )
                    : null,
                customization_profile_id: $json->customization_profile_id ??
                    null,
            );
        }

        public function __construct(
            /**
             * ID of the client session associated with the Instant Key.
             */
            public string|null $client_session_id,
            /**
             * Date and time at which the Instant Key was created.
             */
            public string|null $created_at,
            /**
             * Date and time at which the Instant Key expires.
             */
            public string|null $expires_at,
            /**
             * ID of the Instant Key.
             */
            public string|null $instant_key_id,
            /**
             * Shareable URL for the Instant Key. Use the URL to deliver the Instant Key to your user through a link in a text message or email or by embedding it in your web app.
             */
            public string|null $instant_key_url,
            /**
             * ID of the user identity associated with the Instant Key.
             */
            public string|null $user_identity_id,
            /**
             * ID of the workspace that contains the Instant Key.
             */
            public string|null $workspace_id,
            /**
             * Customization applied to the Instant Key UI.
             */
            public \Seam\Resources\InstantKey\Customization|null $customization = null,
            /**
             * ID of the customization profile associated with the Instant Key.
             */
            public string|null $customization_profile_id = null,
        ) {}
    }
}

namespace Seam\Resources\InstantKey {
    /**
     * Customization applied to the Instant Key UI.
     */
    class Customization
    {
        public static function from_json(mixed $json): Customization|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                logo_url: $json->logo_url ?? null,
                primary_color: $json->primary_color ?? null,
                secondary_color: $json->secondary_color ?? null,
            );
        }

        public function __construct(
            /**
             * URL of the logo displayed on the Instant Key.
             */
            public string|null $logo_url = null,
            /**
             * Primary color used in the Instant Key UI.
             */
            public string|null $primary_color = null,
            /**
             * Secondary color used in the Instant Key UI.
             */
            public string|null $secondary_color = null,
        ) {}
    }
}
