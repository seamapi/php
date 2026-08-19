<?php

namespace Seam\Http;

use GuzzleHttp\Utils;
use Psr\Http\Message\ResponseInterface;
use Seam\InvalidResponseError;

/**
 * Reads the JSON body of a response.
 */
final class Body
{
    /**
     * Returns the decoded body, or null when there is nothing to decode.
     *
     * A PSR-7 body is a stream that can only be read once, so it is rewound
     * first: the middleware reads it to look for a Seam error before the
     * caller ever sees the response. A body that cannot seek, such as a
     * streamed response, is simply read from where it stands.
     */
    public static function decode(ResponseInterface $response): mixed
    {
        $body = $response->getBody();

        if ($body->isSeekable()) {
            $body->rewind();
        }

        $contents = $body->getContents();

        if ($contents === "") {
            return null;
        }

        try {
            return Utils::jsonDecode($contents);
        } catch (\InvalidArgumentException) {
            return null;
        }
    }

    /**
     * Reads the resource an endpoint returns out of its response envelope.
     *
     * @throws InvalidResponseError If the envelope does not carry the key
     */
    public static function read(mixed $res, string $key, string $path): mixed
    {
        if (!is_object($res)) {
            throw new InvalidResponseError(
                $path,
                $key,
                "got " . get_debug_type($res) . " instead of a response object",
            );
        }

        if (!property_exists($res, $key)) {
            throw new InvalidResponseError(
                $path,
                $key,
                "which the response does not contain",
            );
        }

        return $res->$key;
    }

    /**
     * Reads a list of resources out of a response envelope.
     *
     * @return array<array-key, mixed>
     *
     * @throws InvalidResponseError If the envelope does not carry a list
     */
    public static function read_list(
        mixed $res,
        string $key,
        string $path,
    ): array {
        $value = self::read($res, $key, $path);

        if (!is_array($value)) {
            throw new InvalidResponseError(
                $path,
                $key,
                "got " . get_debug_type($value) . " instead of a list",
            );
        }

        return $value;
    }
}
