<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Resources\CameraLiveViewAnswer;
use Seam\Resources\CameraLiveViewSession;

class CamerasLiveViewsClient
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
     * Creates a short-lived live view session for a single camera. Pass the returned session ID and token to `/cameras/live_views/offer` to start a WebRTC stream, and to `/cameras/live_views/stop` to end the session.
     *
     * Camera live view is in beta. To enable it for your workspace, contact Seam support. To check whether a camera supports live view, use `device.can_stream_live_video`.
     *
     * @param string $device_id ID of the camera to view.
     * @param int $duration_seconds Number of seconds for which the live view session is valid, up to 600.
     * @param bool $include_audio Indicates whether to include the camera's audio.
     * @return CameraLiveViewSession OK
     */
    public function create(
        string $device_id,
        ?int $duration_seconds = null,
        ?bool $include_audio = null,
    ): CameraLiveViewSession {
        $request_payload = [];

        $request_payload["device_id"] = $device_id;
        if ($duration_seconds !== null) {
            $request_payload["duration_seconds"] = $duration_seconds;
        }
        if ($include_audio !== null) {
            $request_payload["include_audio"] = $include_audio;
        }

        $res = Body::decode(
            $this->client->request("POST", "/cameras/live_views/create", [
                "json" => (object) $request_payload,
            ]),
        );

        return CameraLiveViewSession::from_json(
            Body::read(
                $res,
                "camera_live_view_session",
                "/cameras/live_views/create",
            ),
        );
    }

    /**
     * Exchanges a WebRTC SDP offer for an SDP answer that starts streaming video from the camera, for a live view session that you created using `/cameras/live_views/create`.
     *
     * Camera live view is in beta. To enable it for your workspace, contact Seam support.
     *
     * @param string $camera_live_view_session_id ID of the camera live view session.
     * @param string $sdp_offer WebRTC SDP offer from the viewer, limited to 64 KiB of UTF-8 data.
     * @param string $token Token returned when the camera live view session was created.
     * @return CameraLiveViewAnswer OK
     */
    public function offer(
        string $camera_live_view_session_id,
        string $sdp_offer,
        string $token,
    ): CameraLiveViewAnswer {
        $request_payload = [];

        $request_payload[
            "camera_live_view_session_id"
        ] = $camera_live_view_session_id;
        $request_payload["sdp_offer"] = $sdp_offer;
        $request_payload["token"] = $token;

        $res = Body::decode(
            $this->client->request("POST", "/cameras/live_views/offer", [
                "json" => (object) $request_payload,
            ]),
        );

        return CameraLiveViewAnswer::from_json(
            Body::read(
                $res,
                "camera_live_view_answer",
                "/cameras/live_views/offer",
            ),
        );
    }

    /**
     * Stops a camera live view session that the current client session owns.
     *
     * Camera live view is in beta. To enable it for your workspace, contact Seam support.
     *
     * @param string $camera_live_view_session_id ID of the camera live view session.
     * @param string $token Token returned when the camera live view session was created.
     * @return void OK
     */
    public function stop(
        string $camera_live_view_session_id,
        string $token,
    ): void {
        $request_payload = [];

        $request_payload[
            "camera_live_view_session_id"
        ] = $camera_live_view_session_id;
        $request_payload["token"] = $token;

        $this->client->request("POST", "/cameras/live_views/stop", [
            "json" => (object) $request_payload,
        ]);
    }
}
