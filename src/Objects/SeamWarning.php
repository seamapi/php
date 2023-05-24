<?php

namespace Seam\Objects;

class SeamWarning
{
  public static function from_json(mixed $json): SeamWarning|null
  {
    if (!$json) {
      return null;
    }

    return new self(
      warning_code: $json?->warning_code ?? null,
      message: $json?->message ?? null,
      created_at: $json?->created_at ?? null,
    );
  }

  public function __construct(
    public string $warning_code,
    public string $message,
    public string|null $created_at
  ) {
  }
}
