<?php

namespace Seam\Errors\Http;

use Seam\Errors\Http\ApiError;

class InvalidInputError extends ApiError
{
  private object $validationErrors;

  public function __construct(object $error, int $statusCode, string $requestId)
  {
    parent::__construct($error, $statusCode, $requestId);
    $this->errorCode = 'invalid_input';
    $this->validationErrors = $error->validation_errors ?? (object)[];
  }

  public function getValidationErrorMessages(string $paramName): array
  {
    return $this->validationErrors->{$paramName}->_errors ?? [];
  }
}
