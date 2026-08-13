<?php

namespace Seam\Http;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Seam\NullValue;
use Seam\UrlSearchParamsSerializer;

/**
 * Applies the Seam serialization standard to every request the wrapped
 * client sends.
 *
 * Query params given as a map are serialized with UrlSearchParamsSerializer
 * and handed to Guzzle as a raw query string, because Guzzle's own encoder
 * follows different rules: it escapes `*`, leaves `~` alone, and drops an
 * empty array instead of sending `name=`. A query already given as a string
 * is a representation the caller chose, so it passes through untouched.
 *
 * NullValue::NULL sentinels in a JSON body become JSON null, so the same
 * sentinel works whether a route sends a query string or a body.
 */
final class SerializingClient implements ClientInterface
{
    private function __construct(private ClientInterface $client) {}

    /**
     * Wraps a client, or returns it unchanged when it is already wrapped.
     */
    public static function wrap(ClientInterface $client): self
    {
        return $client instanceof self ? $client : new self($client);
    }

    #[\Override]
    public function send(
        RequestInterface $request,
        array $options = [],
    ): ResponseInterface {
        return $this->client->send($request, self::serialize_options($options));
    }

    #[\Override]
    public function sendAsync(
        RequestInterface $request,
        array $options = [],
    ): PromiseInterface {
        return $this->client->sendAsync(
            $request,
            self::serialize_options($options),
        );
    }

    #[\Override]
    public function request(
        string $method,
        $uri = "",
        array $options = [],
    ): ResponseInterface {
        return $this->client->request(
            $method,
            $uri,
            self::serialize_options($options),
        );
    }

    #[\Override]
    public function requestAsync(
        string $method,
        $uri = "",
        array $options = [],
    ): PromiseInterface {
        return $this->client->requestAsync(
            $method,
            $uri,
            self::serialize_options($options),
        );
    }

    #[\Override]
    public function getConfig(?string $option = null)
    {
        return $this->client->getConfig($option);
    }

    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    private static function serialize_options(array $options): array
    {
        if (
            isset($options["query"]) &&
            ($options["query"] instanceof \stdClass ||
                is_array($options["query"]))
        ) {
            $serialized = UrlSearchParamsSerializer::serialize(
                $options["query"],
            );

            if ($serialized === "") {
                // Nothing serialized must mean no query at all, not a bare
                // trailing `?`.
                unset($options["query"]);
            } else {
                $options["query"] = $serialized;
            }
        }

        if (array_key_exists("json", $options)) {
            $options["json"] = NullValue::replace($options["json"]);
        }

        return $options;
    }
}
