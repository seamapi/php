<?php

namespace Seam;

/**
 * Serializes PHP values to URL search params.
 *
 * This is a PHP port of the @seamapi/url-search-params-serializer
 * (https://github.com/seamapi/url-search-params-serializer) reference
 * implementation, which defines the standard for how the Seam SDKs and other
 * Seam API consumers serialize objects to URL search params in HTTP GET
 * requests.
 *
 * Output is byte-for-byte identical to the reference implementation: values
 * are encoded with the application/x-www-form-urlencoded serializer, params
 * are sorted by name, and numbers are formatted using the ECMAScript
 * Number::toString algorithm.
 *
 * Type mapping between the reference implementation and this port:
 *
 * - JavaScript undefined is null, or simply an absent key.
 * - JavaScript null is NullValue::NULL. PHP has a single absence value, so
 *   null means the safe option of omitting the param and sending null is
 *   always explicit.
 * - JavaScript string is string.
 * - JavaScript boolean is bool.
 * - JavaScript number is float or int.
 * - JavaScript bigint is int, which is always serialized in full without
 *   exponent notation.
 * - JavaScript Date and Temporal.Instant are DateTimeInterface. A
 *   DateTimeInterface always carries a timezone, so there is no naive value
 *   to interpret; it is converted to UTC. Since Date has millisecond
 *   precision, microseconds are truncated.
 * - A JavaScript plain object is a stdClass or a PHP array with string keys.
 * - A JavaScript Array is a PHP list. PHP has one type for both, so an
 *   array is a plain object when it has string keys and an array when its
 *   keys are sequential integers. The empty array is the empty JavaScript
 *   Array, which serializes to a single pair with an empty value; an empty
 *   plain object, which serializes to nothing, is spelled `new \stdClass()`.
 */
final class UrlSearchParamsSerializer
{
    /**
     * Serializes params to a URL search param query string, without a
     * leading `?`.
     *
     * @param array<string, mixed>|\stdClass $params
     *
     * @throws UnserializableParamError If any param could not be serialized
     */
    public static function serialize(array|\stdClass $params): string
    {
        $search_params = new UrlSearchParams();
        self::update($search_params, $params);

        return $search_params->to_string();
    }

    /**
     * Updates existing URL search params with serialized params.
     *
     * Existing params are preserved unless overwritten by a serialized
     * param. All params are sorted by name.
     *
     * @param array<string, mixed>|\stdClass $params
     *
     * @throws UnserializableParamError If any param could not be serialized
     */
    public static function update(
        UrlSearchParams $search_params,
        array|\stdClass $params,
    ): void {
        self::nested_update($search_params, $params, []);
        $search_params->sort();
    }

    /**
     * @param array<array-key, mixed>|\stdClass $params
     * @param list<string> $path
     */
    private static function nested_update(
        UrlSearchParams $search_params,
        array|\stdClass $params,
        array $path,
    ): void {
        $entries =
            $params instanceof \stdClass ? get_object_vars($params) : $params;

        foreach ($entries as $key => $value) {
            // PHP silently casts a numeric-string key to an integer, so a
            // non-string key cannot be told apart from one; both are
            // rejected rather than serialized ambiguously.
            if (!is_string($key)) {
                throw new UnserializableParamError(
                    (string) $key,
                    "is a " .
                        get_debug_type($key) .
                        " which is unsupported as a parameter name",
                );
            }

            if (str_contains($key, ".")) {
                throw new UnserializableParamError(
                    $key,
                    'contains one or more dots "." in its name which is unsupported',
                );
            }

            $current_path = [...$path, $key];

            if (self::is_plain_object($value)) {
                /** @var array<array-key, mixed>|\stdClass $value */
                self::nested_update($search_params, $value, $current_path);
                continue;
            }

            $name = implode(".", $current_path);

            if ($value === null) {
                continue;
            }

            if ($value === "") {
                continue;
            }

            if (is_array($value)) {
                self::update_from_array($search_params, $name, $value);
                continue;
            }

            $search_params->set($name, self::serialize_value($name, $value));
        }
    }

    /**
     * An array is a plain object when its keys are not the sequential
     * integers of a list. The empty array is a list: it is the empty
     * JavaScript Array, not an empty plain object.
     */
    private static function is_plain_object(mixed $value): bool
    {
        if ($value instanceof \stdClass) {
            return true;
        }

        return is_array($value) && $value !== [] && !array_is_list($value);
    }

    /**
     * @param list<mixed> $values
     */
    private static function update_from_array(
        UrlSearchParams $search_params,
        string $name,
        array $values,
    ): void {
        if ($values === []) {
            // The one case where an empty value is meaningful: the parser
            // reads `name=` as the empty array.
            $search_params->set($name, "");
            return;
        }

        if (count($values) === 1 && $values[0] === "") {
            throw new UnserializableParamError(
                $name,
                "is a single element array containing the empty string which is unsupported",
            );
        }

        if (in_array("", $values, true)) {
            throw new UnserializableParamError(
                $name,
                "is an array containing the empty string which is unsupported",
            );
        }

        foreach ($values as $value) {
            if ($value === null || $value instanceof NullValue) {
                throw new UnserializableParamError(
                    $name,
                    "is an array containing null or undefined values which is unsupported",
                );
            }
        }

        foreach ($values as $value) {
            $search_params->append($name, self::serialize_value($name, $value));
        }
    }

    /**
     * @throws UnserializableParamError If the value could not be serialized
     */
    private static function serialize_value(string $name, mixed $value): string
    {
        if ($value instanceof NullValue) {
            return "";
        }

        if (is_string($value)) {
            return $value;
        }

        if (is_bool($value)) {
            return $value ? "true" : "false";
        }

        if (is_int($value)) {
            return (string) $value;
        }

        if (is_float($value)) {
            return self::format_number($name, $value);
        }

        if ($value instanceof \DateTimeInterface) {
            return self::format_datetime($value);
        }

        throw new UnserializableParamError(
            $name,
            "is a " . get_debug_type($value),
        );
    }

    /**
     * Formats an instant as JavaScript's Date.prototype.toISOString does:
     * always UTC, always exactly three fractional digits, always a literal
     * `Z`. Sub-millisecond precision is truncated, not rounded.
     */
    private static function format_datetime(\DateTimeInterface $value): string
    {
        $utc = \DateTimeImmutable::createFromInterface($value)->setTimezone(
            new \DateTimeZone("UTC"),
        );
        $milliseconds = intdiv((int) $utc->format("u"), 1000);

        return sprintf(
            "%04d-%s.%03dZ",
            (int) $utc->format("Y"),
            $utc->format("m-d\TH:i:s"),
            $milliseconds,
        );
    }

    /**
     * Formats a float with the ECMAScript Number::toString algorithm.
     *
     * PHP's own float formatting differs from it in several ways: an
     * integral float renders as `1.0` rather than `1`, the exponent
     * threshold is not at 1e21 and 1e-7, and exponents are spelled `E+21`
     * rather than `e+21`.
     */
    private static function format_number(string $name, float $value): string
    {
        if (is_nan($value)) {
            throw new UnserializableParamError($name, "is NaN");
        }

        if (is_infinite($value)) {
            throw new UnserializableParamError(
                $name,
                $value > 0 ? "is Infinity" : "is -Infinity",
            );
        }

        if ($value === 0.0) {
            return "0";
        }

        $sign = $value < 0 ? "-" : "";
        [$digits, $point] = self::shortest_digits(abs($value));

        return $sign . self::format_digits($digits, $point);
    }

    /**
     * Returns the shortest digit string that round-trips to the float, with
     * the position of the decimal point relative to those digits, as the
     * ECMAScript Number::toString algorithm requires.
     *
     * @return array{string, int}
     */
    private static function shortest_digits(float $value): array
    {
        // At -1, PHP's float-to-string conversion produces the shortest
        // string that round-trips. Restored because the setting also affects
        // the caller's own serialize() and json_encode() calls.
        $precision = ini_set("serialize_precision", "-1");

        try {
            $repr = var_export($value, true);
        } finally {
            if ($precision !== false) {
                ini_set("serialize_precision", $precision);
            }
        }

        if (
            preg_match(
                '/^(\d+)(?:\.(\d+))?(?:E([+-]?\d+))?$/i',
                $repr,
                $matches,
            ) !== 1
        ) {
            throw new \RuntimeException(
                "Could not parse the PHP float representation: {$repr}",
            );
        }

        $digits = $matches[1] . ($matches[2] ?? "");
        $point = strlen($matches[1]) + (int) ($matches[3] ?? "0");

        $stripped = ltrim($digits, "0");
        $point -= strlen($digits) - strlen($stripped);
        $digits = rtrim($stripped, "0");

        return [$digits, $point];
    }

    /**
     * Formats digits and a decimal point position per ECMAScript
     * Number::toString. The four branches and the constants 21 and -6 are
     * the specification.
     *
     * @param string $digits Significant digits, without trailing zeros
     * @param int $point Position of the decimal point relative to the digits
     */
    private static function format_digits(string $digits, int $point): string
    {
        $count = strlen($digits);

        if ($count <= $point && $point <= 21) {
            return $digits . str_repeat("0", $point - $count);
        }

        if (0 < $point && $point <= 21) {
            return substr($digits, 0, $point) . "." . substr($digits, $point);
        }

        if (-6 < $point && $point <= 0) {
            return "0." . str_repeat("0", -$point) . $digits;
        }

        $exponent = $point - 1;
        $exponent_sign = $exponent >= 0 ? "+" : "-";
        $mantissa =
            $count === 1 ? $digits : $digits[0] . "." . substr($digits, 1);

        return $mantissa . "e" . $exponent_sign . abs($exponent);
    }
}
