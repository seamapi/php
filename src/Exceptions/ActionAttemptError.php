<?php

namespace Seam\Exceptions;

use Seam\Resources\ActionAttempt;

/**
 * Base class for the errors raised while resolving an action attempt.
 */
class ActionAttemptError extends \RuntimeException implements SeamException
{
    private ActionAttempt $actionAttempt;

    public function __construct(string $message, ActionAttempt $actionAttempt)
    {
        parent::__construct($message);
        $this->actionAttempt = $actionAttempt;
    }

    public function getActionAttempt(): ActionAttempt
    {
        return $this->actionAttempt;
    }
}
