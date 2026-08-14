<?php

namespace Seam;

/**
 * Serializes params for the Seam API: the URL search params standard plus
 * `_strict=true` appended to any non-empty query, which tells the API to
 * use strict, schema-aware parsing. A query with no serializable params
 * remains empty.
 *
 * The strict flag is Seam API behavior, not part of the serialization
 * standard, so it lives here rather than in UrlSearchParamsSerializer,
 * which stays a pure implementation of the standard.
 */
final class StrictUrlSearchParamsSerializer
{
    /**
     * Serializes params to a URL search param query string with strict API
     * validation enabled, without a leading `?`.
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
     * Updates existing URL search params with serialized params and strict
     * API validation enabled.
     *
     * @param array<string, mixed>|\stdClass $params
     *
     * @throws UnserializableParamError If any param could not be serialized
     */
    public static function update(
        UrlSearchParams $search_params,
        array|\stdClass $params,
    ): void {
        UrlSearchParamsSerializer::update($search_params, $params);

        if (count($search_params) > 0) {
            // Replaced rather than repeated, and appended after the sort so
            // it always sits last.
            $search_params->delete("_strict");
            $search_params->append("_strict", "true");
        }
    }
}
