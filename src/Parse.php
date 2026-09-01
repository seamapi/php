<?php

namespace Seam;

/**
 * Conversion helpers that degrade rather than raise on an unexpected payload shape.
 */
final class Parse
{
    /**
     * Convert a list of objects, reading anything that is not a list as empty.
     *
     * @template T
     * @param callable(mixed): T $from_json
     * @return array<int, T>
     */
    public static function to_list(mixed $value, callable $from_json): array
    {
        if (!is_array($value)) {
            return [];
        }

        return array_values(array_map($from_json, $value));
    }
}
