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
        // The error property only exists on the generated status subclasses,
        // so an attempt with an unknown action_type or status does not have
        // one. Its shape also varies by action type, hence the runtime reads.
        $error = get_object_vars($actionAttempt)["error"] ?? null;
        $message = null;
        $type = null;
        if (is_object($error)) {
            $errorProperties = get_object_vars($error);
            $message = $errorProperties["message"] ?? null;
            $type = $errorProperties["type"] ?? null;
        }
        parent::__construct(
            is_string($message) ? $message : "Action attempt failed",
            $actionAttempt,
        );
        $this->errorCode = is_string($type) ? $type : "unknown_error";
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
