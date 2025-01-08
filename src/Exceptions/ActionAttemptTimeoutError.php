<?php

namespace Seam;

use Seam\Objects\ActionAttempt;

class ActionAttemptTimeoutError extends ActionAttemptError
{
    public function __construct(ActionAttempt $actionAttempt, float $timeout)
    {
        parent::__construct(
            "Timed out waiting for action attempt after {$timeout}s",
            $actionAttempt
        );
        $this->name = get_class($this);
    }
}
