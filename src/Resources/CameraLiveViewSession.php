<?php

namespace Seam\Resources {
    /**
     * Represents a short-lived live view session for a single camera. Use the session ID and token to start a WebRTC stream and to stop the session.
     */
    class CameraLiveViewSession
    {
        public static function from_json(
            mixed $json,
        ): CameraLiveViewSession|null {
            if (!$json) {
                return null;
            }
            return new self(
                camera_live_view_session_id: $json->camera_live_view_session_id ??
                    null,
                device_id: $json->device_id ?? null,
                expires_at: $json->expires_at ?? null,
                token: $json->token ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the camera live view session.
             */
            public string|null $camera_live_view_session_id,
            /**
             * ID of the camera.
             */
            public string|null $device_id,
            /**
             * Date and time at which the live view session expires.
             */
            public string|null $expires_at,
            /**
             * Token that authorizes the offer and stop requests for this session.
             */
            public string|null $token,
        ) {}
    }
}
