<?php

namespace Seam;

class SeamHttpUnauthorizedError extends SeamHttpApiError
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
