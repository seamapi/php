<?php

namespace Seam\Objects;

class SeamError
{
  public static function from_json(mixed $json): SeamError|null
  {
    if (!$json) {
      return null;
    }

    return new self(
      error_code: $json?->error_code ?? $json?->type ?? null,
      message: $json?->message ?? null,
      created_at: $json?->created_at ?? null,
    );
  }

  public function __construct(
    public string|null $error_code,
    public string|null $message,
    public string|null $created_at
  ) {
  }
}
