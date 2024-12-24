<?php

namespace Seam\Errors\Http;

use Seam\Errors\Http\ApiError;

class UnauthorizedError extends ApiError
{
  public function __construct(string $requestId)
  {
    $error = (object)[
      'type' => 'unauthorized',
      'message' => 'Unauthorized'
    ];
    parent::__construct($error, 401, $requestId);
  }
}
