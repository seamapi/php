<?php

namespace Seam;

class SeamError {
  public string $error_type;
  public string $message;
}

class DeviceProperties {
  public bool $online;
  public bool $locked;
  public bool $door_open;
  public float $battery_level;

  public mixed $august_metadata;
  public mixed $schlage_metadata;
  public mixed $smartthings_metadata;
}

class Device {
  public string $device_id;
  public string $workspace_id;

  public string $device_type;

  public DeviceProperties $properties;
  public mixed $location;
  public string $created_at;

  /* @var SeamError[] */
  public array $errors;
}

class Workspace {
  public string $workspace_id;
  public string $name;
  public bool $is_sandbox;
  public string $created_at;
}

class UserIdentifier {
  public string $email;
  public string $phone;
}

class ConnectedAccount {
  public string $connected_account_id;
  public string $workspace_id;
  public UserIdentifier $user_identifier;
  public string $created_at;
}

class AccessCode {
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

class ActionAttempt {
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