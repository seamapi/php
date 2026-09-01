<?php

namespace Seam;

use Seam\Resources\ActionAttempt;

/**
 * Raised when an action attempt reports a status this SDK version does not know.
 *
 * Waiting can neither return it as a success nor call it a failure. Read the
 * action attempt to inspect the status directly.
 */
class ActionAttemptUnknownStatusError extends ActionAttemptError
{
    private string $status;

    public function __construct(ActionAttempt $actionAttempt, string $status)
    {
        parent::__construct(
            "Action attempt reported an unknown status \"{$status}\". " .
                "This SDK version may predate it; upgrade or read the action attempt directly.",
            $actionAttempt,
        );
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
