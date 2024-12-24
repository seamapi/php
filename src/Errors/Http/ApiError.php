<?php

namespace Seam\Errors\Http;

class ApiError extends \Exception
{
  public string $errorCode;
  public int $statusCode;
  public string $requestId;
  public ?object $data;

  public function __construct(object $error, int $statusCode, string $requestId)
  {
    $message = $error->message ?? 'Unknown error';
    parent::__construct($message);
    $this->errorCode = $error->type ?? 'unknown';
    $this->statusCode = $statusCode;
    $this->requestId = $requestId;
    $this->data = $error->data ?? null;
  }

  public function getErrorCode(): string
  {
    return $this->getErrorCode;
  }

  public function getStatusCode(): int
  {
    return $this->statusCode;
  }

  public function getRequestId(): int
  {
    return $this->requestId;
  }

  public function getData(): mixed
  {
    return $this->data;
  }
}
