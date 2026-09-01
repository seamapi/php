<?php

namespace Seam;

/**
 * Total conversion helpers shared by the generated resource classes.
 *
 * Seam adds event types, action types, and error codes between SDK releases, so
 * reading a response must never fail on the shape of the payload. A value the
 * API sends in an unexpected shape degrades rather than raising, so one
 * surprising field cannot cost the caller the whole response.
 */
final class Parse
{
    /**
     * Convert a list of objects, reading anything that is not a list as empty.
     *
     * array_map raises a TypeError when handed a scalar, which would fail the
     * whole response over a single field.
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
