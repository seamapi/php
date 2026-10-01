<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Resources\Media;

class MediaClient
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
     * Returns a specified piece of media, such as a video clip or thumbnail image captured for a camera event, with a short-lived URL from which you can download it. Camera events list their media in `media_ids`. This endpoint is in beta.
     *
     * @param string $media_id ID of the media that you want to get.
     * @param string $format Response format. `json` returns the media object. `redirect` responds with a `302` redirect to the media's download URL, so you can use this endpoint directly as the source of an image or video.
     * @return Media OK
     */
    public function get(string $media_id, ?string $format = null): Media
    {
        $request_payload = [];

        $request_payload["media_id"] = $media_id;
        if ($format !== null) {
            $request_payload["format"] = $format;
        }

        $res = Body::decode(
            $this->client->request("GET", "/media/get", [
                "query" => $request_payload,
            ]),
        );

        return Media::from_json(Body::read($res, "media", "/media/get"));
    }
}
