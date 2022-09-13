<?php

namespace Seam;

class SeamError
{
  public static function from_json(mixed $json): SeamError | null
  {
    return null;
  }

  public string $error_type;
  public string $message;
}

class DeviceProperties
{
  public static function from_json(mixed $json): DeviceProperties | null
  {
    if (!$json) {
      return null;
    }
    return new DeviceProperties(
      online: $json->online ?? null,
      locked: $json->locked ?? null,
      name: $json->name ?? null,
      door_open: $json->door_open ?? null,
      battery_level: $json->battery_level ?? null,
      schlage_metadata: $json->schlage_metadata ?? null,
      august_metadata: $json->august_metadata ?? null,
      smartthings_metadata: $json->smartthings_metadata ?? null
    );
  }

  public function __construct(
    public bool $online,
    public bool $locked,
    public bool $door_open,
    public float $battery_level,
    public string $name,

    public mixed $august_metadata,
    public mixed $schlage_metadata,
    public mixed $smartthings_metadata
  ) {
  }
}

class Device
{
  public static function from_json(mixed $json): Device | null
  {
    if (!$json) {
      return null;
    }
    return new Device(
      device_id: $json->device_id,
      workspace_id: $json->workspace_id,
      connected_account_id: $json->connected_account_id,
      device_type: $json->device_type,
      // device_name: $json->device_name,
      created_at: $json->created_at,
      location: $json->location,
      properties: DeviceProperties::from_json($json->properties),
      errors: array_map(fn ($e) => SeamError::from_json($e), $json->device_errors ?? []),
    );
  }

  public function __construct(
    public string $device_id,
    public string $workspace_id,
    public string $connected_account_id,
    public string $device_type,

    public DeviceProperties $properties,
    public mixed $location,
    public string $created_at,

    /* @var SeamError[] */
    public array $errors
  ) {
  }

  public function to_json(): mixed
  {
    return json_encode($this);
  }
}

class Workspace
{
  public string $workspace_id;
  public string $name;
  public bool $is_sandbox;
  public string $created_at;
}

class UserIdentifier
{
  public string $email;
  public string $phone;
}

class ConnectedAccount
{
  public string $connected_account_id;
  public string $workspace_id;
  public UserIdentifier $user_identifier;
  public string $created_at;
}

class AccessCode
{
  public string $access_code_id;
  public string $workspace_id;

  /* "time_bound" or "ongoing" */
  public string $type;

  /*
   * The status of an access code on the device.
   * unset -> setting -> set -> unset OR "unknown" if the account is disconnected
   */
  public string $status;

  /* In ISO8601 timestamp format, only for time_bound codes */
  public string $starts_at;

  /* In ISO8601 timestamp format, only for time_bound codes */
  public string $ends_at;

  /*
   * The 4-8 digit code assigned to the device, note that this isn't always
   * immediately available after creating the access code.
   */
  public string $code;
  public string $created_at;
}

class ActionAttempt
{
  public string $action_attempt_id;
  public string $workspace_id;

  /*
   * CREATE_ACCESS_CODE, DELETE_ACCESS_CODE, LOCK_DOOR, UNLOCK_DOOR, etc.
   */
  public string $action_type;

  /*
   * Can be "pending", "success", "error"
   */
  public string $status;
  public string $created_at;
}
