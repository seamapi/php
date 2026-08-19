<?php

namespace Seam;

/**
 * Error thrown when a successful response does not carry the resource the
 * endpoint is defined to return.
 */
class InvalidResponseError extends \UnexpectedValueException implements
    SeamException
{
    public function __construct(
        private string $path,
        private string $key,
        string $reason,
    ) {
        parent::__construct(
            "Seam returned an invalid response for {$path}: expected \"{$key}\", {$reason}",
        );
    }

    /**
     * The endpoint path the response came from, e.g. `/devices/get`.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * The response key that should have carried the resource.
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
