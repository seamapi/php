<?php

namespace Seam\Objects;

class UnmanagedDeviceProperties
{
  public static function from_json(mixed $json): UnmanagedDeviceProperties|null
  {
    if (!$json) {
      return null;
    }

    return new self(
      name: $json->name ?? null,
      manufacturer: $json->manufacturer ?? null,
      image_url: $json->image_url ?? null,
      image_alt_text: $json->image_alt_text ?? null,
      online_access_codes_enabled: $json->online_access_codes_enabled ?? null,
      model: $json->model,
    );
  }

  public function __construct(
    public string | null $name,
    public string | null $manufacturer,
    public string | null $image_url,
    public string | null $image_alt_text,
    public string | null $online_access_codes_enabled,
    public mixed $model,
  ) {
  }
}
