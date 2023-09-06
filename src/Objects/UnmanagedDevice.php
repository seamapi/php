<?php

namespace Seam\Objects;

class UnmanagedDevice
{
  public static function from_json(mixed $json): UnmanagedDevice|null
  {
    if (!$json) {
      return null;
    }
    return new self(
      device_id: $json->device_id,
      device_type: $json->device_type ?? null,
      connected_account_id: $json->connected_account_id,
      workspace_id: $json->workspace_id,
      created_at: $json->created_at,
      properties: UnmanagedDeviceProperties::from_json($json->properties),
      is_managed: $json->is_managed,
      capabilities_supported: $json->capabilities_supported,
      errors: array_map(
        fn ($e) => SeamError::from_json($e),
        $json->errors ?? []
      ),
      warnings: array_map(
        fn ($e) => SeamWarning::from_json($e),
        $json->warnings ?? []
      ),
    );
  }

  public function __construct(
    public string $device_id,
    public string | null $device_type,
    public string $connected_account_id,
    public string $workspace_id,
    public string $created_at,
    public UnmanagedDeviceProperties $properties,
    public bool $is_managed,
    public array $capabilities_supported,

    /** @var SeamError[] */
    public array $errors,

    /** @var SeamWarning[] */
    public array $warnings
  ) {
  }

  public function to_json(): mixed
  {
    return json_encode($this);
  }
}
