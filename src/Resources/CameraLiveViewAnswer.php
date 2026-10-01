<?php

namespace Seam\Resources {
    /**
     * Represents the WebRTC SDP answer that starts streaming video from a camera for a live view session.
     */
    class CameraLiveViewAnswer
    {
        public static function from_json(mixed $json): CameraLiveViewAnswer|null
        {
            if (!$json) {
                return null;
            }
            return new self(sdp_answer: $json->sdp_answer ?? null);
        }

        public function __construct(
            /**
             * WebRTC SDP answer for the offer, limited to 64 KiB of UTF-8 data.
             */
            public string|null $sdp_answer,
        ) {}
    }
}
