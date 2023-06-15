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
      model: $json->model,
    );
  }

  public function __construct(
    string | null $name,
    string | null $manufacturer,
    string | null $image_url,
    string | null $image_alt_text,
    mixed $model,
  ) {
  }
}
