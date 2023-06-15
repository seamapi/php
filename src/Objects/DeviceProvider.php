<?php

namespace Seam\Objects;

class DeviceProvider
{
  public static function from_json(mixed $json): DeviceProvider|null
  {
    if (!$json) {
      return null;
    }

    return new self(
      device_provider_name: $json->device_provider_name,
      display_name: $json->display_name,
      image_url: $json->image_url,
      provider_categories: $json->provider_categories,
    );
  }

  public function __construct(
    string $device_provider_name,
    string $display_name,
    string $image_url,
    array $provider_categories,
  ) {
  }
}
