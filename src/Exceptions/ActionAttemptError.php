<?php

namespace Seam;

use Seam\Objects\ActionAttempt;

class ActionAttemptError extends \Exception
{
    private ActionAttempt $actionAttempt;

    public function __construct(string $message, ActionAttempt $actionAttempt)
    {
        parent::__construct($message);
        $this->name = get_class($this);
        $this->actionAttempt = $actionAttempt;
    }

    public function getActionAttempt(): ActionAttempt
    {
        return $this->actionAttempt;
    }
}
