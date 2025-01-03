<?php

namespace Seam;

use Seam\Objects\ActionAttempt;

class SeamActionAttemptFailedError extends SeamActionAttemptError
{
  private string $errorCode;

  public function __construct(ActionAttempt $actionAttempt)
  {
    parent::__construct($actionAttempt->error->message, $actionAttempt);
    $this->name = get_class($this);
    $this->errorCode = $actionAttempt->error->type;
  }

  public function getErrorCode(): string
  {
    return $this->errorCode;
  }
}
