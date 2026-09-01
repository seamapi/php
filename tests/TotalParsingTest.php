<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Seam\ActionAttemptError;
use Seam\ActionAttemptUnknownStatusError;
use Seam\Resources\ActionAttempt;
use Seam\Resources\Device;
use Seam\Resources\Event;

/**
 * Reading a response must not fail on the shape of the payload.
 */
final class TotalParsingTest extends TestCase
{
    public function testAListPropertySentAsAScalarReadsAsEmpty(): void
    {
        $device = Device::from_json(
            json_decode('{"device_id":"device_1","errors":"oops"}'),
        );

        $this->assertSame([], $device->errors);
        $this->assertSame("device_1", $device->device_id);
    }

    public function testAListPropertySentAsNullReadsAsEmpty(): void
    {
        $device = Device::from_json(
            json_decode('{"device_id":"device_1","errors":null}'),
        );

        $this->assertSame([], $device->errors);
    }

    public function testAnUnknownErrorCodeKeepsTheRestOfTheResource(): void
    {
        $device = Device::from_json(
            json_decode(
                '{"device_id":"device_1","errors":[{"error_code":"brand_new","message":"m"}]}',
            ),
        );

        $this->assertSame("device_1", $device->device_id);
        $this->assertCount(1, $device->errors);
        $this->assertSame("brand_new", $device->errors[0]->error_code);
    }

    public function testANestedObjectSentAsAScalarDoesNotRaise(): void
    {
        $device = Device::from_json(
            json_decode('{"device_id":"device_1","location":"nope"}'),
        );

        $this->assertSame("device_1", $device->device_id);
    }

    public function testAnUnknownEventTypeUsesTheBaseClass(): void
    {
        $event = Event::from_json(
            json_decode('{"event_id":"event_1","event_type":"future.thing"}'),
        );

        $this->assertSame(Event::class, $event::class);
        $this->assertSame("future.thing", $event->event_type);
    }

    public function testWaitingOnAnUnknownStatusRaisesRatherThanClaimingSuccess(): void
    {
        $attempt = ActionAttempt::from_json(
            json_decode(
                '{"action_attempt_id":"attempt_1","action_type":"LOCK_DOOR","status":"cancelled"}',
            ),
        );

        $error = new ActionAttemptUnknownStatusError($attempt, "cancelled");

        $this->assertInstanceOf(ActionAttemptError::class, $error);
        $this->assertSame("cancelled", $error->getStatus());
        $this->assertStringContainsString("cancelled", $error->getMessage());
    }
}
