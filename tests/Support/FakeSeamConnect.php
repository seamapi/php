<?php

namespace Tests\Support;

/**
 * Runs a fake Seam Connect server for the duration of a single test.
 *
 * Prefer this over stubbing HTTP responses: the fake exercises the SDK against
 * a real server and seeded records.
 */
class FakeSeamConnect
{
    private const STARTUP_TIMEOUT = 30.0;
    private const SHUTDOWN_TIMEOUT = 10.0;
    private const POLL_INTERVAL = 50000;

    private string $endpoint;
    private array $seed;
    /** @var resource|null */
    private $process = null;
    /** @var array<int, resource> */
    private array $pipes = [];

    public static function start(): self
    {
        $fake = new self();
        $fake->run();

        return $fake;
    }

    public function endpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * The ids and tokens of the seeded records.
     */
    public function seed(): array
    {
        return $this->seed;
    }

    private function run(): void
    {
        $binary = dirname(__DIR__, 2) . "/node_modules/.bin/fake-seam-connect";

        if (!is_executable($binary)) {
            throw new \RuntimeException(
                "Could not find {$binary}, run npm install before the tests.",
            );
        }

        $port = self::unused_port();
        $this->endpoint = "http://127.0.0.1:{$port}";

        // The binary is spawned directly rather than through npm so the
        // process handle is the server itself and stopping it does not leave
        // an orphan behind. PORT goes to the child only, leaving the parent
        // environment alone for the tests that read it.
        $this->process = proc_open(
            [$binary, "--seed"],
            [
                0 => ["file", "/dev/null", "r"],
                1 => ["file", "/dev/null", "w"],
                2 => ["file", "/dev/null", "w"],
            ],
            $this->pipes,
            dirname(__DIR__, 2),
            ["PORT" => (string) $port] + getenv(),
        );

        if (!is_resource($this->process)) {
            throw new \RuntimeException("Could not start Fake Seam Connect.");
        }

        $this->wait_for_health();
        $this->seed = $this->fetch_seed();
    }

    public function stop(): void
    {
        if (!is_resource($this->process)) {
            return;
        }

        proc_terminate($this->process, SIGTERM);

        $deadline = microtime(true) + self::SHUTDOWN_TIMEOUT;
        while (microtime(true) < $deadline) {
            if (!proc_get_status($this->process)["running"]) {
                break;
            }
            usleep(self::POLL_INTERVAL);
        }

        if (proc_get_status($this->process)["running"]) {
            proc_terminate($this->process, SIGKILL);
        }

        proc_close($this->process);
        $this->process = null;
    }

    private function wait_for_health(): void
    {
        $deadline = microtime(true) + self::STARTUP_TIMEOUT;

        while (microtime(true) < $deadline) {
            if (!proc_get_status($this->process)["running"]) {
                throw new \RuntimeException(
                    "Fake Seam Connect exited before becoming healthy.",
                );
            }

            if ($this->get("/health") !== null) {
                return;
            }

            usleep(self::POLL_INTERVAL);
        }

        throw new \RuntimeException(
            "Fake Seam Connect did not become healthy within " .
                self::STARTUP_TIMEOUT .
                "s.",
        );
    }

    private function fetch_seed(): array
    {
        $body = $this->get("/_fake/default_seed");

        if ($body === null) {
            throw new \RuntimeException(
                "Could not read the seed from Fake Seam Connect.",
            );
        }

        return json_decode($body, true);
    }

    private function get(string $path): ?string
    {
        $context = stream_context_create([
            "http" => ["timeout" => 5, "ignore_errors" => true],
        ]);

        $body = @file_get_contents($this->endpoint . $path, false, $context);

        return $body === false ? null : $body;
    }

    private static function unused_port(): int
    {
        $socket = stream_socket_server("tcp://127.0.0.1:0", $errno, $errstr);

        if ($socket === false) {
            throw new \RuntimeException(
                "Could not find an unused port: {$errstr}",
            );
        }

        $name = stream_socket_get_name($socket, false);
        fclose($socket);

        return (int) substr($name, strrpos($name, ":") + 1);
    }
}
