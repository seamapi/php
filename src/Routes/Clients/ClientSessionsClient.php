<?php

namespace Seam\Routes\Clients;

use Seam\Seam;
use Seam\Routes\Objects\ClientSession;

class ClientSessionsClient
{
  private Seam $seam;

  public function __construct(Seam $seam)
  {
    $this->seam = $seam;
  }

  public function create(
    array $connect_webview_ids = null,
    array $connected_account_ids = null,
    string $expires_at = null,
    string $user_identifier_key = null,
    array $user_identity_ids = null
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
    if ($user_identity_ids !== null) {
      $request_payload["user_identity_ids"] = $user_identity_ids;
    }

    $res = $this->seam->request(
      "POST",
      "/client_sessions/create",
      json: (object) $request_payload,
      inner_object: "client_session"
    );

    return ClientSession::from_json($res);
  }

  public function delete(string $client_session_id): void
  {
    $request_payload = [];

    if ($client_session_id !== null) {
      $request_payload["client_session_id"] = $client_session_id;
    }

    $this->seam->request(
      "POST",
      "/client_sessions/delete",
      json: (object) $request_payload
    );
  }

  public function get(
    string $client_session_id = null,
    string $user_identifier_key = null
  ): ClientSession {
    $request_payload = [];

    if ($client_session_id !== null) {
      $request_payload["client_session_id"] = $client_session_id;
    }
    if ($user_identifier_key !== null) {
      $request_payload["user_identifier_key"] = $user_identifier_key;
    }

    $res = $this->seam->request(
      "POST",
      "/client_sessions/get",
      json: (object) $request_payload,
      inner_object: "client_session"
    );

    return ClientSession::from_json($res);
  }

  public function get_or_create(
    array $connect_webview_ids = null,
    array $connected_account_ids = null,
    string $expires_at = null,
    string $user_identifier_key = null,
    array $user_identity_ids = null
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
    if ($user_identity_ids !== null) {
      $request_payload["user_identity_ids"] = $user_identity_ids;
    }

    $res = $this->seam->request(
      "POST",
      "/client_sessions/get_or_create",
      json: (object) $request_payload,
      inner_object: "client_session"
    );

    return ClientSession::from_json($res);
  }

  public function grant_access(
    string $client_session_id = null,
    array $connect_webview_ids = null,
    array $connected_account_ids = null,
    string $user_identifier_key = null,
    array $user_identity_ids = null
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
    if ($user_identity_ids !== null) {
      $request_payload["user_identity_ids"] = $user_identity_ids;
    }

    $this->seam->request(
      "POST",
      "/client_sessions/grant_access",
      json: (object) $request_payload
    );
  }

  public function list(
    string $client_session_id = null,
    string $connect_webview_id = null,
    string $user_identifier_key = null,
    string $user_identity_id = null,
    bool $without_user_identifier_key = null
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
      $request_payload["without_user_identifier_key"] = $without_user_identifier_key;
    }

    $res = $this->seam->request(
      "POST",
      "/client_sessions/list",
      json: (object) $request_payload,
      inner_object: "client_sessions"
    );

    return array_map(fn($r) => ClientSession::from_json($r), $res);
  }

  public function revoke(string $client_session_id): void
  {
    $request_payload = [];

    if ($client_session_id !== null) {
      $request_payload["client_session_id"] = $client_session_id;
    }

    $this->seam->request(
      "POST",
      "/client_sessions/revoke",
      json: (object) $request_payload
    );
  }
}
