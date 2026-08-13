<?php

namespace Seam\Http;

use GuzzleHttp\Utils;
use Psr\Http\Message\ResponseInterface;

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
}
