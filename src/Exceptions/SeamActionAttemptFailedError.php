<?php

namespace Seam;

use Seam\Objects\ActionAttempt;

class SeamActionAttemptFailedError extends SeamActionAttemptError
{
  public string $errorCode;

  public function __construct(ActionAttempt $actionAttempt)
  {
    parent::__construct($actionAttempt->error->message, $actionAttempt);
    $this->name = get_class($this);
    $this->errorCode = $actionAttempt->error->type;
  }
}
