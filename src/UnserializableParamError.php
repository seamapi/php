<?php

namespace Seam;

/**
 * Error thrown when a request param could not be serialized, before any
 * request is sent.
 */
class UnserializableParamError extends \InvalidArgumentException
{
    public function __construct(private string $name, string $message)
    {
        parent::__construct(
            "Could not serialize parameter: '{$name}' {$message}",
        );
    }

    /**
     * The name of the param that could not be serialized, e.g. `foo.bar` for
     * a nested param.
     */
    public function getName(): string
    {
        return $this->name;
    }
}
