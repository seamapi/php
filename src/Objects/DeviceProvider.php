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
    public string $device_provider_name,
    public string $display_name,
    public string $image_url,
    public array $provider_categories,
  ) {
  }
}
