<?php

namespace Seam\Routes\Objects;

class DeviceSchlageMetadata
{
  public static function from_json(mixed $json): DeviceSchlageMetadata|null
  {
    if (!$json) {
      return null;
    }
    return new self(
      device_id: $json->device_id,
      device_name: $json->device_name,
      model: $json->model ?? null,
      access_code_length: $json->access_code_length ?? null
    );
  }

  public function __construct(
    public string $device_id,
    public string $device_name,
    public string|null $model,
    public float|null $access_code_length
  ) {}
}
