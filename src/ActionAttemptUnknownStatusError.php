<?php

namespace Seam;

use Seam\Resources\ActionAttempt;

/**
 * Raised when an action attempt reports a status this SDK version does not know.
 *
 * Waiting promises to return a succeeded attempt or raise, and an unrecognized
 * status supports neither conclusion: reporting success would claim the action
 * completed when the SDK cannot tell, and polling on would block until the
 * timeout and then report a timeout that misdescribes what happened. Read the
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
