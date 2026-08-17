<?php

namespace Seam;

/**
 * A mutable collection of URL search params.
 *
 * Implements the parts of the URLSearchParams interface
 * (https://developer.mozilla.org/en-US/docs/Web/API/URLSearchParams)
 * needed to serialize params to a query string. Unlike a PHP array, a name
 * may appear more than once, which is how arrays are serialized.
 *
 * @implements \IteratorAggregate<int, array{string, string}>
 */
class UrlSearchParams implements \Countable, \IteratorAggregate
{
    /** @var list<array{string, string}> */
    private array $pairs = [];

    /**
     * @param string|array<string, mixed>|list<array{string, string}>|null $init
     *        A query string, a map of names to values, or a list of
     *        name-value pairs
     *
     * In the map form a value is a string, int, bool, null, or a list of
     * those, which becomes one pair per element. Anything the serialization
     * standard renders specially, such as a float or a date, belongs in
     * UrlSearchParamsSerializer rather than here.
     *
     * @throws UnserializableParamError If a map value cannot be rendered
     */
    public function __construct(string|array|null $init = null)
    {
        if ($init === null) {
            return;
        }

        if (is_string($init)) {
            $query = str_starts_with($init, "?") ? substr($init, 1) : $init;

            foreach (explode("&", $query) as $pair) {
                if ($pair === "") {
                    continue;
                }

                $parts = explode("=", $pair, 2);
                $this->pairs[] = [
                    urldecode($parts[0]),
                    urldecode($parts[1] ?? ""),
                ];
            }

            return;
        }

        foreach ($init as $name => $value) {
            if (is_array($value) && is_int($name)) {
                $this->pairs[] = [(string) $value[0], (string) $value[1]];
                continue;
            }

            // A name may repeat, so an array value becomes one pair per
            // element rather than the string "Array".
            if (is_array($value)) {
                foreach ($value as $element) {
                    $this->pairs[] = [
                        (string) $name,
                        self::stringify((string) $name, $element),
                    ];
                }
                continue;
            }

            $this->pairs[] = [
                (string) $name,
                self::stringify((string) $name, $value),
            ];
        }
    }

    /**
     * Renders a map value as a search param value.
     *
     * Only the values the standard renders the same way are accepted. A
     * float needs the ECMAScript number formatting, and a date the exact
     * ISO shape, both of which live in UrlSearchParamsSerializer; rendering
     * them here with a plain cast would quietly disagree with it, so they
     * are rejected and the caller is pointed at the serializer instead.
     *
     * @throws UnserializableParamError If the value has no string form here
     */
    private static function stringify(string $name, mixed $value): string
    {
        if ($value === null || $value instanceof NullValue) {
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

        throw new UnserializableParamError(
            $name,
            "is a " .
                get_debug_type($value) .
                ", which UrlSearchParams cannot render; serialize it with UrlSearchParamsSerializer first",
        );
    }

    /**
     * Appends a name-value pair, keeping any existing pairs with this name.
     */
    public function append(string $name, string $value): void
    {
        $this->pairs[] = [$name, $value];
    }

    /**
     * Sets the value associated with a name.
     *
     * Replaces the first pair with this name and removes any others, so the
     * pair keeps its position. Appends a new pair if no pair with this name
     * exists.
     */
    public function set(string $name, string $value): void
    {
        if (!$this->has($name)) {
            $this->append($name, $value);
            return;
        }

        $pairs = [];
        $is_set = false;

        foreach ($this->pairs as $pair) {
            if ($pair[0] !== $name) {
                $pairs[] = $pair;
            } elseif (!$is_set) {
                $pairs[] = [$name, $value];
                $is_set = true;
            }
        }

        $this->pairs = $pairs;
    }

    /**
     * Returns the value of the first pair with this name, or null if no pair
     * with this name exists.
     */
    public function get(string $name): ?string
    {
        foreach ($this->pairs as [$existing_name, $value]) {
            if ($existing_name === $name) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Returns the values of all pairs with this name, in insertion order.
     *
     * @return list<string>
     */
    public function get_all(string $name): array
    {
        $values = [];

        foreach ($this->pairs as [$existing_name, $value]) {
            if ($existing_name === $name) {
                $values[] = $value;
            }
        }

        return $values;
    }

    /**
     * Returns whether a pair with this name exists.
     */
    public function has(string $name): bool
    {
        foreach ($this->pairs as [$existing_name, $_]) {
            if ($existing_name === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Removes all pairs with this name.
     */
    public function delete(string $name): void
    {
        $this->pairs = array_values(
            array_filter($this->pairs, fn(array $pair) => $pair[0] !== $name),
        );
    }

    /**
     * Sorts all pairs by name, comparing bytes.
     *
     * Sorting is stable, so the relative order of pairs with the same name
     * is preserved, which is what keeps array element order. Byte order
     * matches URLSearchParams.sort() for ASCII names; a name beyond the
     * Basic Multilingual Plane may sort differently than in JavaScript.
     */
    public function sort(): void
    {
        usort($this->pairs, fn(array $a, array $b) => strcmp($a[0], $b[0]));
    }

    /**
     * Serializes all pairs to a query string, without a leading `?`.
     *
     * Every pair gets an `=`, including empty values, e.g. `name=`.
     */
    public function to_string(): string
    {
        return implode(
            "&",
            array_map(
                fn(array $pair) => self::encode_form_component($pair[0]) .
                    "=" .
                    self::encode_form_component($pair[1]),
                $this->pairs,
            ),
        );
    }

    public function __toString(): string
    {
        return $this->to_string();
    }

    #[\Override]
    public function count(): int
    {
        return count($this->pairs);
    }

    /**
     * @return \ArrayIterator<int<0, max>, array{string, string}>
     */
    #[\Override]
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->pairs);
    }

    /**
     * Percent-encodes a string with the WHATWG
     * application/x-www-form-urlencoded serializer, applied to the UTF-8
     * bytes of the string.
     *
     * The safe set is not the RFC 3986 unreserved set, so neither urlencode
     * nor rawurlencode produces it: `*` is emitted literally and `~` is
     * escaped, the exact opposite of both.
     */
    private static function encode_form_component(string $value): string
    {
        $encoded = "";
        $length = strlen($value);

        for ($i = 0; $i < $length; $i++) {
            $character = $value[$i];
            $byte = ord($character);

            $is_safe =
                ($byte >= 0x30 && $byte <= 0x39) ||
                ($byte >= 0x41 && $byte <= 0x5a) ||
                ($byte >= 0x61 && $byte <= 0x7a) ||
                $character === "*" ||
                $character === "-" ||
                $character === "." ||
                $character === "_";

            if ($is_safe) {
                $encoded .= $character;
            } elseif ($character === " ") {
                $encoded .= "+";
            } else {
                $encoded .= sprintf("%%%02X", $byte);
            }
        }

        return $encoded;
    }
}
