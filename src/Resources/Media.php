<?php

namespace Seam\Resources {
    /**
     * Represents a piece of media, such as a video clip or a thumbnail image, that a device captured for an event. Media is in beta.
     */
    class Media
    {
        public static function from_json(mixed $json): Media|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                content_type: $json->content_type ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                expires_at: $json->expires_at ?? null,
                media_id: $json->media_id ?? null,
                media_type: $json->media_type ?? null,
                status: $json->status ?? null,
                url: $json->url ?? null,
                workspace_id: $json->workspace_id ?? null,
                video_codec: $json->video_codec ?? null,
            );
        }

        public function __construct(
            /**
             * MIME type of the media, such as `video/mp4` or `image/jpeg`.
             */
            public string|null $content_type,
            /**
             * Date and time at which the media was created.
             */
            public string|null $created_at,
            /**
             * ID of the device that captured the media.
             */
            public string|null $device_id,
            /**
             * ID of the event that the media belongs to.
             */
            public string|null $event_id,
            /**
             * Date and time at which the media stops being available. Null when Seam does not know when the media expires.
             */
            public string|null $expires_at,
            /**
             * ID of the media.
             */
            public string|null $media_id,
            /**
             * Type of the media: a video clip or a still image.
             *
             * @var value-of<\Seam\Resources\Media\MediaType>|string|null
             */
            public string|null $media_type,
            /**
             * Status of the media. `pending` means that Seam is still retrieving the media. `available` means that `url` can be used to download it. `unavailable` means that no media exists for the event, and `failed` means that Seam could not retrieve it.
             *
             * @var value-of<\Seam\Resources\Media\Status>|string|null
             */
            public string|null $status,
            /**
             * Short-lived URL from which you can download the media. Null unless `status` is `available`. The URL expires after about five minutes. Call `/media/get` again for a new URL.
             */
            public string|null $url,
            /**
             * ID of the workspace that contains the media.
             */
            public string|null $workspace_id,
            /**
             * Video codec used to encode the media. Only present for video media. `hevc` (H.265) playback support varies by browser and device, so check compatibility before assuming a clip plays inline.
             *
             * @var value-of<\Seam\Resources\Media\VideoCodec>|string|null
             */
            public string|null $video_codec = null,
        ) {}
    }
}

namespace Seam\Resources\Media {
    enum MediaType: string
    {
        case VIDEO = "video";
        case IMAGE = "image";
    }

    enum Status: string
    {
        case PENDING = "pending";
        case AVAILABLE = "available";
        case UNAVAILABLE = "unavailable";
        case FAILED = "failed";
    }

    enum VideoCodec: string
    {
        case H264 = "h264";
        case HEVC = "hevc";
    }
}
