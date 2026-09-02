<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Seam\ActionAttemptError;
use Seam\ActionAttemptUnknownStatusError;
use Seam\Resources\ActionAttempt;
use Seam\Resources\Device;
use Seam\Resources\Event;

final class ForwardCompatibilityTest extends TestCase
{
    public function testAnUnknownEnumValueReadsAsItself(): void
    {
        $device = Device::from_json(
            json_decode('{"device_id":"device_1","device_type":"future_lock"}'),
        );

        $this->assertSame("future_lock", $device->device_type);
    }

    public function testAnUnknownEventTypeUsesTheBaseClass(): void
    {
        $event = Event::from_json(
            json_decode('{"event_id":"event_1","event_type":"future.thing"}'),
        );

        $this->assertSame(Event::class, $event::class);
        $this->assertSame("future.thing", $event->event_type);
    }

    public function testAnUnknownErrorCodeKeepsTheRestOfTheResource(): void
    {
        $device = Device::from_json(
            json_decode(
                '{"device_id":"device_1","errors":[{"error_code":"brand_new","message":"m"}]}',
            ),
        );

        $this->assertSame("device_1", $device->device_id);
        $this->assertSame("brand_new", $device->errors[0]->error_code);
    }

    public function testAnUnknownActionAttemptStatusReadsAsItself(): void
    {
        $attempt = ActionAttempt::from_json(
            json_decode(
                '{"action_attempt_id":"attempt_1","action_type":"LOCK_DOOR","status":"cancelled"}',
            ),
        );

        $this->assertSame("cancelled", $attempt->status);
    }

    public function testTheUnknownStatusErrorSubclassesTheBase(): void
    {
        $attempt = ActionAttempt::from_json(
            json_decode(
                '{"action_attempt_id":"attempt_1","action_type":"LOCK_DOOR"}',
            ),
        );

        $error = new ActionAttemptUnknownStatusError($attempt, "cancelled");

        $this->assertInstanceOf(ActionAttemptError::class, $error);
        $this->assertSame("cancelled", $error->getStatus());
    }

    public function testRawJsonRecoversAFieldTheGeneratedShapeDrops(): void
    {
        $json =
            '{"event_id":"event_1","event_type":"access_code.created","brand_new_field":"kept"}';

        $event = Event::from_json(json_decode($json));

        $this->assertFalse(property_exists($event, "brand_new_field"));
        $this->assertEquals(
            json_decode($json),
            json_decode($event->raw_json()),
        );
    }

    public function testRawJsonRoundTripsAnUnrecognizedEvent(): void
    {
        $json = '{"event_id":"event_1","event_type":"future.thing","x":1}';

        $event = Event::from_json(json_decode($json));

        $this->assertEquals(
            json_decode($json),
            json_decode($event->raw_json()),
        );
    }

    public function testRawJsonIsScopedToEvents(): void
    {
        $device = Device::from_json(json_decode('{"device_id":"device_1"}'));

        $this->assertFalse(method_exists($device, "raw_json"));
    }
}
