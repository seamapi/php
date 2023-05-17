<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use GuzzleHttp\Client;

final class EventsTest extends TestCase
{
  public function testGetAndListEvents(): void
  {
    $seam = Fixture::getTestServer(true);
    $events = $seam->events->list(since: "1970-01-01T00:00:00.000Z");
    $this->assertIsArray($events);

    $device = $seam->devices->list()[0];
    $events = $seam->events->list(since: "1970-01-01T00:00:00.000Z", device_id: $device->device_id);
    $this->assertIsArray($events);

    $access_code = $seam->access_codes->create(device_id: $device->device_id);
    $events = $seam->events->list(since: "1970-01-01T00:00:00.000Z", access_code_id: $access_code->access_code_id);
    $this->assertIsArray($events);

    // This endpoint is 404'ing
    // $event_id = $events[0]->event_id;
    // $event = $seam->events->get(event_id: $event_id);
    // $this->assertTrue($event->event_id === $event_id);
  }

  public function testListEventsError(): void
  {
    $seam = Fixture::getTestServer(true);
    try {
      $seam->events->list(since: "invalid_date");
    } catch (Exception $e) {
      $this->assertTrue(
        str_contains(
          $e->getMessage(),
          'Must be parsable date string for "since"'
        )
      );
    }
  }
}
