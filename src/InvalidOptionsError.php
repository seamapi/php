<?php

namespace Seam;

/**
 * Raised when incompatible or incomplete options are given to a Seam client,
 * e.g. a personal access token without a workspace id.
 */
class InvalidOptionsError extends \InvalidArgumentException implements
    SeamException
{
    public function __construct(string $message)
    {
        parent::__construct("Seam received invalid options: " . $message);
    }
}
