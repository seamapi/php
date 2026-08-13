<?php

namespace Seam;

use Seam\Resources\ActionAttempt;

/**
 * Raised when an action attempt finishes in the error state.
 */
class ActionAttemptFailedError extends ActionAttemptError
{
    private string $errorCode;

    public function __construct(ActionAttempt $actionAttempt)
    {
        parent::__construct(
            $actionAttempt->error->message ?? "Action attempt failed",
            $actionAttempt,
        );
        $this->errorCode = $actionAttempt->error->type ?? "unknown_error";
    }

    /**
     * The action attempt error type.
     *
     * Named `getErrorCode` rather than `getCode` because `Exception::getCode`
     * is final and returns an int.
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
