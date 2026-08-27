<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use ReflectionNamedType;
use ReflectionProperty;
use Seam\ActionAttemptFailedError;
use Seam\Resources\ActionAttempt;
use Seam\Resources\ActionAttempt\LockDoor;
use TypeError;

/**
 * Action attempts are discriminated twice: by action_type into one subclass
 * per action, and by status into one subclass per status under each action.
 * A property that only applies to some statuses keeps its real type on the
 * subclasses for those statuses and has the null type everywhere else, so
 * result and error cannot be dereferenced without narrowing to the matching
 * status subclass first.
 */
final class ActionAttemptTest extends TestCase
{
    private function attempt(array $payload): ActionAttempt
    {
        $attempt = ActionAttempt::from_json(
            json_decode(
                json_encode(
                    array_merge(
                        [
                            "action_attempt_id" => "attempt_1",
                            "action_type" => "LOCK_DOOR",
                        ],
                        $payload,
                    ),
                ),
            ),
        );

        $this->assertNotNull($attempt);

        return $attempt;
    }

    public function testSuccessExposesANonNullResult(): void
    {
        $attempt = $this->attempt([
            "status" => "success",
            "result" => ["was_confirmed_by_device" => true],
        ]);

        $this->assertInstanceOf(LockDoor\Success::class, $attempt);
        $this->assertInstanceOf(LockDoor::class, $attempt);
        $this->assertSame("success", $attempt->status);
        $this->assertInstanceOf(
            LockDoor\Success\Result::class,
            $attempt->result,
        );
        $this->assertTrue($attempt->result->was_confirmed_by_device);
        $this->assertNull($attempt->error);
    }

    public function testErrorExposesANonNullError(): void
    {
        $attempt = $this->attempt([
            "status" => "error",
            "error" => ["type" => "device_offline", "message" => "Offline"],
        ]);

        $this->assertInstanceOf(LockDoor\Error::class, $attempt);
        $this->assertSame("error", $attempt->status);
        $this->assertInstanceOf(LockDoor\Error\Error::class, $attempt->error);
        $this->assertSame("Offline", $attempt->error->message);
        $this->assertSame("device_offline", $attempt->error->type);
        $this->assertNull($attempt->result);
    }

    public function testPendingHasANullResultAndError(): void
    {
        $attempt = $this->attempt(["status" => "pending"]);

        $this->assertInstanceOf(LockDoor\Pending::class, $attempt);
        $this->assertSame("pending", $attempt->status);
        $this->assertNull($attempt->result);
        $this->assertNull($attempt->error);
    }

    /**
     * The annotation drives the shape, not the payload: a result sent while
     * the action attempt is pending never reaches the resource.
     */
    public function testResultSentWhilePendingIsStillNull(): void
    {
        $attempt = $this->attempt([
            "status" => "pending",
            "result" => ["was_confirmed_by_device" => true],
            "error" => ["type" => "foo", "message" => "bar"],
        ]);

        $this->assertInstanceOf(LockDoor\Pending::class, $attempt);
        $this->assertNull($attempt->result);
        $this->assertNull($attempt->error);
    }

    /**
     * Dereferencing result or error without narrowing to the status subclass
     * is caught by the declared types: statuses a property does not apply to
     * declare it with the null type rather than a nullable object type.
     */
    public function testInapplicablePropertiesAreDeclaredWithTheNullType(): void
    {
        foreach (
            [
                [LockDoor\Pending::class, "result"],
                [LockDoor\Pending::class, "error"],
                [LockDoor\Success::class, "error"],
                [LockDoor\Error::class, "result"],
            ]
            as [$className, $propertyName]
        ) {
            $type = (new ReflectionProperty(
                $className,
                $propertyName,
            ))->getType();

            $this->assertInstanceOf(ReflectionNamedType::class, $type);
            $this->assertSame(
                "null",
                $type->getName(),
                "{$className}::\${$propertyName} should have the null type",
            );
        }

        $successResult = (new ReflectionProperty(
            LockDoor\Success::class,
            "result",
        ))->getType();

        $this->assertInstanceOf(ReflectionNamedType::class, $successResult);
        $this->assertSame(
            LockDoor\Success\Result::class,
            $successResult->getName(),
        );
    }

    public function testInapplicablePropertiesRejectNonNullValues(): void
    {
        $this->expectException(TypeError::class);

        new LockDoor\Success(
            action_attempt_id: "attempt_1",
            action_type: "LOCK_DOOR",
            error: new LockDoor\Error\Error(
                message: "Offline",
                type: "device_offline",
            ),
            result: null,
            status: "success",
        );
    }

    public function testUnknownActionTypeUsesTheBaseClass(): void
    {
        $attempt = $this->attempt([
            "action_type" => "FUTURE_ACTION",
            "status" => "success",
        ]);

        $this->assertSame(ActionAttempt::class, $attempt::class);
        $this->assertSame("FUTURE_ACTION", $attempt->action_type);
        $this->assertSame("success", $attempt->status);
    }

    public function testUnknownStatusUsesTheActionTypeClass(): void
    {
        $attempt = $this->attempt(["status" => "future_status"]);

        $this->assertSame(LockDoor::class, $attempt::class);
        $this->assertSame("LOCK_DOOR", $attempt->action_type);
        $this->assertSame("future_status", $attempt->status);
    }

    public function testFailedErrorReadsTheErrorFromTheStatusSubclass(): void
    {
        $attempt = $this->attempt([
            "status" => "error",
            "error" => ["type" => "device_offline", "message" => "Offline"],
        ]);

        $error = new ActionAttemptFailedError($attempt);

        $this->assertSame("Offline", $error->getMessage());
        $this->assertSame("device_offline", $error->getErrorCode());
        $this->assertSame($attempt, $error->getActionAttempt());
    }

    /**
     * A failed attempt with an unknown action_type has no error property at
     * all, so the error falls back to its defaults instead of raising.
     */
    public function testFailedErrorFallsBackWithoutAnErrorProperty(): void
    {
        $attempt = $this->attempt([
            "action_type" => "FUTURE_ACTION",
            "status" => "error",
            "error" => ["type" => "foo", "message" => "bar"],
        ]);

        $this->assertSame(ActionAttempt::class, $attempt::class);

        $error = new ActionAttemptFailedError($attempt);

        $this->assertSame("Action attempt failed", $error->getMessage());
        $this->assertSame("unknown_error", $error->getErrorCode());
    }
}
