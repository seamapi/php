<?php

namespace Seam\Http;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Seam\HttpApiError;
use Seam\HttpInvalidInputError;
use Seam\HttpUnauthorizedError;

/**
 * Guzzle middleware that turns a Seam error response into a Seam exception.
 *
 * The client is built with `http_errors` disabled so this runs instead, and it
 * sits outside the retry middleware so it only sees the response a request
 * finally settled on.
 *
 * A response that is not a Seam error envelope, such as a gateway returning
 * HTML, raises the transport error Guzzle would have raised on its own.
 */
final class ErrorMiddleware
{
    public static function create(): callable
    {
        return static fn(callable $handler): callable => static fn(
            RequestInterface $request,
            array $options,
        ): PromiseInterface => $handler($request, $options)->then(
            static function (ResponseInterface $response) use (
                $request,
            ): ResponseInterface {
                $status_code = $response->getStatusCode();

                if ($status_code >= 200 && $status_code < 300) {
                    return $response;
                }

                throw self::to_exception($request, $response);
            },
        );
    }

    private static function to_exception(
        RequestInterface $request,
        ResponseInterface $response,
    ): \Throwable {
        $status_code = $response->getStatusCode();
        $request_id = self::get_request_id($response);

        if ($status_code === 401) {
            return new HttpUnauthorizedError($request_id);
        }

        $error = self::get_error($response);

        if ($error === null) {
            return BadResponseException::create($request, $response);
        }

        if (($error->type ?? null) === "invalid_input") {
            return new HttpInvalidInputError($error, $status_code, $request_id);
        }

        return new HttpApiError($error, $status_code, $request_id);
    }

    /**
     * The error from a Seam error envelope, i.e. JSON holding an `error`
     * object with a string `type` and `message`, or null when the response is
     * not one.
     */
    private static function get_error(ResponseInterface $response): ?object
    {
        if (
            !str_starts_with(
                $response->getHeaderLine("content-type"),
                "application/json",
            )
        ) {
            return null;
        }

        $body = Body::decode($response);

        if (!is_object($body)) {
            return null;
        }

        $error = $body->error ?? null;

        if (!is_object($error)) {
            return null;
        }

        return is_string($error->type ?? null) &&
            is_string($error->message ?? null)
            ? $error
            : null;
    }

    private static function get_request_id(ResponseInterface $response): ?string
    {
        return $response->hasHeader("seam-request-id")
            ? $response->getHeaderLine("seam-request-id")
            : null;
    }
}
