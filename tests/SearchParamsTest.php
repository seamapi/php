<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Seam\NullValue;
use Seam\Seam;
use Seam\UnserializableParamError;
use Tests\Support\RecordingClient;

final class SearchParamsTest extends TestCase
{
    private const DEVICES = ["devices" => []];
    private const DEVICE = ["device" => ["device_id" => "device1"]];

    private function seam(RecordingClient $recording): Seam
    {
        return Seam::from_api_key(
            "seam_apikey_token",
            endpoint: "https://example.com",
            guzzle_options: $recording->guzzle_options(),
        );
    }

    public function testClientSerializesSearchParams(): void
    {
        $recording = RecordingClient::repeating(
            RecordingClient::json(200, self::DEVICES),
        );

        $this->seam($recording)->client->request("GET", "/devices/list", [
            "query" => [
                "device_ids" => ["device1", "device2"],
                "custom_metadata_has" => ["tag" => "front", "floor" => 2],
                "limit" => 20,
            ],
        ]);

        $this->assertSame(
            "custom_metadata_has.floor=2" .
                "&custom_metadata_has.tag=front" .
                "&device_ids=device1" .
                "&device_ids=device2" .
                "&limit=20" .
                "&_strict=true",
            $recording->request()->getUri()->getQuery(),
        );
        $this->assertSame("GET", $recording->request()->getMethod());
        $this->assertSame(
            "/devices/list",
            $recording->request()->getUri()->getPath(),
        );
    }

    public function testClientDoesNotReencodeTheSerializedSearchParams(): void
    {
        $recording = RecordingClient::repeating(
            RecordingClient::json(200, self::DEVICES),
        );

        $this->seam($recording)->client->request("GET", "/devices/list", [
            "query" => ["search" => "a *~ b"],
        ]);

        $this->assertSame(
            "search=a+*%7E+b&_strict=true",
            $recording->request()->getUri()->getQuery(),
        );
    }

    public function testClientSerializesEmptyArraysToAnEmptyValue(): void
    {
        $recording = RecordingClient::repeating(
            RecordingClient::json(200, self::DEVICES),
        );

        $this->seam($recording)->client->request("GET", "/devices/list", [
            "query" => ["device_ids" => []],
        ]);

        $this->assertSame(
            "device_ids=&_strict=true",
            $recording->request()->getUri()->getQuery(),
        );
    }

    public function testClientOmitsSearchParamsSetToNull(): void
    {
        $recording = RecordingClient::repeating(
            RecordingClient::json(200, self::DEVICES),
        );

        $this->seam($recording)->client->request("GET", "/devices/list", [
            "query" => ["search" => null, "limit" => 20],
        ]);

        $this->assertSame(
            "limit=20&_strict=true",
            $recording->request()->getUri()->getQuery(),
        );
    }

    public function testClientSerializesSearchParamsSetToNullValue(): void
    {
        $recording = RecordingClient::repeating(
            RecordingClient::json(200, self::DEVICES),
        );

        $this->seam($recording)->client->request("GET", "/devices/list", [
            "query" => ["search" => NullValue::NULL, "limit" => 20],
        ]);

        $this->assertSame(
            "limit=20&search=&_strict=true",
            $recording->request()->getUri()->getQuery(),
        );
    }

    public function testClientSendsNoQueryStringWithoutSearchParams(): void
    {
        $recording = RecordingClient::repeating(
            RecordingClient::json(200, self::DEVICES),
        );
        $seam = $this->seam($recording);

        $seam->client->request("GET", "/devices/list", ["query" => []]);
        $seam->client->request("GET", "/devices/list", [
            "query" => ["search" => null],
        ]);
        $seam->client->request("GET", "/devices/list");

        foreach ([0, 1, 2] as $index) {
            $this->assertSame(
                "/devices/list",
                $recording->request($index)->getRequestTarget(),
            );
        }
    }

    public function testClientSerializesSearchParamsOfEveryVerb(): void
    {
        $recording = RecordingClient::repeating(
            RecordingClient::json(200, self::DEVICES),
        );
        $seam = $this->seam($recording);

        $verbs = ["GET", "POST", "PUT", "PATCH", "DELETE"];

        foreach ($verbs as $verb) {
            $seam->client->request($verb, "/devices/list", [
                "query" => ["sync" => true],
            ]);
        }

        foreach ($verbs as $index => $verb) {
            $this->assertSame($verb, $recording->request($index)->getMethod());
            $this->assertSame(
                "sync=true&_strict=true",
                $recording->request($index)->getUri()->getQuery(),
            );
        }
    }

    public function testClientPassesSearchParamsItDidNotSerializeToGuzzle(): void
    {
        $recording = RecordingClient::repeating(
            RecordingClient::json(200, self::DEVICES),
        );

        $this->seam($recording)->client->request("GET", "/devices/list", [
            "query" => "device_ids=device1",
        ]);

        $this->assertSame(
            "device_ids=device1",
            $recording->request()->getUri()->getQuery(),
        );
    }

    public function testClientRejectsASearchParamItCannotSerialize(): void
    {
        $recording = RecordingClient::repeating(
            RecordingClient::json(200, self::DEVICES),
        );
        $seam = $this->seam($recording);

        try {
            $seam->client->request("GET", "/devices/list", [
                "query" => ["search" => new \SplStack()],
            ]);
            $this->fail("Expected an UnserializableParamError");
        } catch (UnserializableParamError $error) {
            $this->assertSame("search", $error->getName());
        }

        $this->assertSame(0, $recording->request_count());
    }

    public function testClientSerializesNullValueInAJsonBodyToNull(): void
    {
        $recording = RecordingClient::repeating(
            RecordingClient::json(200, self::DEVICE),
        );

        $this->seam($recording)->client->request("POST", "/devices/update", [
            "json" => (object) [
                "device_id" => "device1",
                "name" => NullValue::NULL,
                "properties" => ["code" => NullValue::NULL],
            ],
        ]);

        $this->assertSame("POST", $recording->request()->getMethod());
        $this->assertSame(
            [
                "device_id" => "device1",
                "name" => null,
                "properties" => ["code" => null],
            ],
            json_decode((string) $recording->request()->getBody(), true),
        );
    }

    public function testClientLeavesAJsonBodyWithoutNullValueUnchanged(): void
    {
        $recording = RecordingClient::repeating(
            RecordingClient::json(200, self::DEVICE),
        );

        $body = [
            "device_id" => "device1",
            "name" => "Front Door",
            "limit" => 20,
            "sync" => true,
        ];

        $this->seam($recording)->client->request("POST", "/devices/update", [
            "json" => (object) $body,
        ]);

        $this->assertSame(
            $body,
            json_decode((string) $recording->request()->getBody(), true),
        );
    }

    public function testClientSerializesTheSearchParamsOfAGeneratedRoute(): void
    {
        $recording = RecordingClient::repeating(
            RecordingClient::json(200, self::DEVICE),
        );

        $this->seam($recording)->devices->get(name: "Front Door");

        $this->assertSame("GET", $recording->request()->getMethod());
        $this->assertSame(
            "/devices/get",
            $recording->request()->getUri()->getPath(),
        );
        $this->assertSame(
            "name=Front+Door&_strict=true",
            $recording->request()->getUri()->getQuery(),
        );
    }
}
