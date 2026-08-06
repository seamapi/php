<?php

namespace Seam;

/**
 * Raised when the Seam API rejects the request credentials.
 */
class HttpUnauthorizedError extends HttpApiError
{
    public function __construct(?string $requestId)
    {
        parent::__construct(
            (object) [
                "type" => "unauthorized",
                "message" => "Unauthorized",
            ],
            401,
            $requestId,
        );
    }
}
