<?php

namespace Seam;

/**
 * A request parameter that failed validation and its error messages.
 */
final class ValidationError
{
    /**
     * @param string[] $error_messages
     */
    public function __construct(
        public readonly string $parameter_name,
        public readonly array $error_messages,
    ) {}
}
