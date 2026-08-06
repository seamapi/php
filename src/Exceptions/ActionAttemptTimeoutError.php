<?php

namespace Seam\Exceptions;

use Seam\Resources\ActionAttempt;

/**
 * Raised when an action attempt does not finish within the timeout.
 *
 * The action attempt it carries is the last one observed, which is still
 * pending.
 */
class ActionAttemptTimeoutError extends ActionAttemptError
{
    public function __construct(ActionAttempt $actionAttempt, float $timeout)
    {
        parent::__construct(
            "Timed out waiting for action attempt after {$timeout}s",
            $actionAttempt,
        );
    }
}
