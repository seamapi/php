<?php

namespace Seam;

class HttpApiError extends \Exception
{
    private string $errorCode;
    private int $statusCode;
    private string $requestId;
    private ?object $data = null;

    public function __construct(
        object $error,
        int $statusCode,
        string $requestId
    ) {
        $message = $error->message ?? "Unknown error";
        parent::__construct($message);
        $this->errorCode = $error->type ?? "unknown";
        $this->statusCode = $statusCode;
        $this->requestId = $requestId;
        $this->data = $error->data ?? null;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getRequestId(): string
    {
        return $this->requestId;
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}
