<?php

namespace Seam;

/**
 * The explicit null sentinel used by request params.
 *
 * PHP has a single absence value, null, but the Seam API distinguishes an
 * omitted param from a param explicitly set to null. For example, in an
 * update request, an omitted param leaves the current value unchanged,
 * while a null param unsets the current value.
 *
 * Since sending null is rarely intended and unsetting a value cannot be
 * undone, null means the safe option of omitting the param. Sending null is
 * explicit and always spelled NullValue::NULL:
 *
 * ```php
 * use Seam\NullValue;
 * use Seam\UrlSearchParamsSerializer;
 *
 * UrlSearchParamsSerializer::serialize(["name" => NullValue::NULL, "limit" => 20]);
 * // => 'limit=20&name='
 *
 * UrlSearchParamsSerializer::serialize(["name" => null, "limit" => 20]);
 * // => 'limit=20'
 * ```
 *
 * Use it wherever the Seam API documents null as a meaningful value, e.g.,
 * to unset a value in an update request, or to filter by an unset value.
 */
enum NullValue
{
    /**
     * Sentinel for a param explicitly set to null.
     */
    case NULL;

    /**
     * Returns a copy of a value with every NullValue::NULL replaced by null.
     *
     * The sentinel only distinguishes an explicit null from an omitted param
     * within this SDK. Once a request body is being serialized, the param is
     * known to be present, so the sentinel becomes the null that JSON has.
     *
     * Recurses into arrays and stdClass objects without mutating them; every
     * other value is returned unchanged.
     */
    public static function replace(mixed $value): mixed
    {
        if ($value instanceof self) {
            return null;
        }

        if (is_array($value)) {
            return array_map(self::replace(...), $value);
        }

        if ($value instanceof \stdClass) {
            return (object) array_map(
                self::replace(...),
                get_object_vars($value),
            );
        }

        return $value;
    }
}
