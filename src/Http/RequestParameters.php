<?php

namespace Seam\Http;

final class RequestParameters
{
    /**
     * @param array<string, mixed> $parameters
     * @param list<string> $parameterNames Non-pagination parameters that can satisfy the requirement.
     */
    public static function requireAtLeastOne(
        string $path,
        array $parameters,
        array $parameterNames,
    ): void {
        foreach ($parameterNames as $name) {
            if (($parameters[$name] ?? null) !== null) {
                return;
            }
        }

        throw new \InvalidArgumentException(
            "At least one parameter is required for $path",
        );
    }
}
