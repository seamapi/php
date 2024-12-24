<?php

namespace Seam\Errors\ActionAttempt;

use Seam\Objects\ActionAttempt;

class SeamActionAttemptError extends \Exception
{
  public ActionAttempt $actionAttempt;

  public function __construct(string $message, ActionAttempt $actionAttempt)
  {
    parent::__construct($message);
    $this->name = get_class($this);
    $this->actionAttempt = $actionAttempt;
  }
}
