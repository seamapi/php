<?php

namespace Seam;

class HttpUnauthorizedError extends HttpApiError
{
    public function __construct(string $requestId)
    {
        $error = (object) [
            "type" => "unauthorized",
            "message" => "Unauthorized",
        ];
        parent::__construct($error, 401, $requestId);
    }
}
