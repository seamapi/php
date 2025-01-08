<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;

final class EventsTest extends TestCase
{
    public function testGetAndListEvents(): void
    {
        $seam = Fixture::getTestServer();
        $events = $seam->events->list(since: "1970-01-01T00:00:00.000Z");
        $this->assertIsArray($events);

        $device = $seam->devices->list()[0];
        $events = $seam->events->list(
            since: "1970-01-01T00:00:00.000Z",
            device_id: $device->device_id
        );
        $this->assertIsArray($events);

        $seam->access_codes->create(device_id: $device->device_id);
        $events = $seam->events->list(
            since: "1970-01-01T00:00:00.000Z",
            device_id: $device->device_id
        );
        $this->assertIsArray($events);

        $event_id = $events[0]->event_id;
        $event = $seam->events->get(event_id: $event_id);
        $this->assertTrue($event->event_id === $event_id);
    }

    public function testListEventsError(): void
    {
        $seam = Fixture::getTestServer();
        try {
            $seam->events->list(since: "invalid_date");
        } catch (\Seam\HttpInvalidInputError $e) {
            $this->assertTrue(
                str_contains($e->getMessage(), "Must be parsable date string")
            );
        }
    }
}
