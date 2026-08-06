<?php

namespace Seam\Exceptions;

/**
 * Raised when a token of the wrong kind or format is used to authenticate.
 */
class InvalidTokenError extends \InvalidArgumentException implements
    SeamException
{
    public function __construct(string $message)
    {
        parent::__construct("Seam received an invalid token: " . $message);
    }
}
