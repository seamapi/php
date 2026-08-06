<?php

namespace Tests\Support;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

/**
 * Replays canned responses and records the requests the SDK sent.
 *
 * Use this only for the things the fake cannot do: asserting what goes out on
 * the wire, counting retries, and serving malformed error responses. Prefer
 * the fake for everything else.
 */
class RecordingClient
{
    /** @var array<int, array{request: RequestInterface, options: array}> */
    public array $transactions = [];

    private MockHandler $mock;

    /**
     * @param array<int, Response|\Throwable> $responses Served in order; the last one repeats.
     */
    public function __construct(private array $responses)
    {
        $this->mock = new MockHandler($responses);
    }

    /**
     * Builds the Guzzle options to hand to a Seam client so its requests land
     * here instead of on the network.
     *
     * @return array<string, mixed>
     */
    public function guzzle_options(): array
    {
        $stack = HandlerStack::create($this->mock);
        $stack->push(Middleware::history($this->transactions));

        return ["handler" => $stack];
    }

    /**
     * How many logical calls the SDK made. Retries are invisible here because
     * the SDK pushes its retry middleware inside this recorder; use
     * attempt_count() to count those.
     */
    public function request_count(): int
    {
        return count($this->transactions);
    }

    /**
     * How many requests actually reached the handler, retries included.
     */
    public function attempt_count(): int
    {
        return count($this->responses) - $this->mock->count();
    }

    public function request(int $index = 0): RequestInterface
    {
        return $this->transactions[$index]["request"];
    }

    public function body(int $index = 0): mixed
    {
        return json_decode((string) $this->request($index)->getBody());
    }

    /**
     * A response whose body repeats for every request, so retries keep
     * failing the same way.
     */
    public static function repeating(Response $response, int $times = 20): self
    {
        return new self(array_fill(0, $times, $response));
    }

    public static function repeating_throwable(
        \Throwable $error,
        int $times = 20,
    ): self {
        return new self(array_fill(0, $times, $error));
    }

    public static function json(int $status, mixed $body): Response
    {
        return new Response(
            $status,
            ["content-type" => "application/json"],
            json_encode($body),
        );
    }

    public static function raw(
        int $status,
        string $body,
        string $content_type = "text/plain",
    ): Response {
        return new Response($status, ["content-type" => $content_type], $body);
    }
}
