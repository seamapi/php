<?php

namespace Seam\Objects;

class Device
{
  public static function from_json(mixed $json): Device|null
  {
    if (!$json) {
      return null;
    }
    return new self(
      device_id: $json->device_id,
      workspace_id: $json->workspace_id,
      connected_account_id: $json->connected_account_id,
      device_type: $json->device_type,
      // device_name: $json->device_name,
      created_at: $json->created_at,
      location: $json->location,
      capabilities_supported: $json->capabilities_supported,
      properties: DeviceProperties::from_json($json->properties),
      errors: array_map(
        fn ($e) => SeamError::from_json($e),
        $json->device_errors ?? []
      )
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

    /** @var string[] */
    public array $capabilities_supported,

    /** @var SeamError[] */
    public array $errors
  ) {
  }

  public function to_json(): mixed
  {
    return json_encode($this);
  }
}
