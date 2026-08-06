<?php

namespace Seam;

/**
 * Raised when the Seam API returns an error response.
 */
class HttpApiError extends \RuntimeException implements SeamException
{
    protected string $errorCode;
    private int $statusCode;
    private ?string $requestId;
    private mixed $data;

    public function __construct(
        object $error,
        int $statusCode,
        ?string $requestId,
    ) {
        parent::__construct($error->message ?? "Unknown error");
        $this->errorCode = $error->type ?? "unknown_error";
        $this->statusCode = $statusCode;
        $this->requestId = $requestId;
        $this->data = $error->data ?? null;
    }

    /**
     * The Seam error type, e.g. `device_not_found`.
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * The `seam-request-id` response header, or null when absent.
     */
    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}
