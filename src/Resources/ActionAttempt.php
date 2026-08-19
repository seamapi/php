<?php

namespace Seam\Resources {
    /**
     * Base class for actions whose completion is tracked asynchronously.
     */
    abstract class ActionAttempt
    {
        public static function from_json(mixed $json): ActionAttempt|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->action_type ?? null)
                ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                    $json->action_type,
                )
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\ActionType::LOCK_DOOR
                    => \Seam\Resources\ActionAttempt\LockDoor::from_json($json),
                \Seam\Resources\ActionAttempt\ActionType::UNLOCK_DOOR
                    => \Seam\Resources\ActionAttempt\UnlockDoor::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::SCAN_CREDENTIAL
                    => \Seam\Resources\ActionAttempt\ScanCredential::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::ENCODE_CREDENTIAL
                    => \Seam\Resources\ActionAttempt\EncodeCredential::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::SCAN_TO_ASSIGN_CREDENTIAL
                    => \Seam\Resources\ActionAttempt\ScanToAssignCredential::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::ASSIGN_CREDENTIAL
                    => \Seam\Resources\ActionAttempt\AssignCredential::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::RESET_SANDBOX_WORKSPACE
                    => \Seam\Resources\ActionAttempt\ResetSandboxWorkspace::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::SET_FAN_MODE
                    => \Seam\Resources\ActionAttempt\SetFanMode::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::SET_HVAC_MODE
                    => \Seam\Resources\ActionAttempt\SetHvacMode::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::ACTIVATE_CLIMATE_PRESET
                    => \Seam\Resources\ActionAttempt\ActivateClimatePreset::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::SIMULATE_KEYPAD_CODE_ENTRY
                    => \Seam\Resources\ActionAttempt\SimulateKeypadCodeEntry::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::SIMULATE_MANUAL_LOCK_VIA_KEYPAD
                    => \Seam\Resources\ActionAttempt\SimulateManualLockViaKeypad::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::PUSH_THERMOSTAT_PROGRAMS
                    => \Seam\Resources\ActionAttempt\PushThermostatPrograms::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::CONFIGURE_AUTO_LOCK
                    => \Seam\Resources\ActionAttempt\ConfigureAutoLock::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::SYNC_ACCESS_CODES
                    => \Seam\Resources\ActionAttempt\SyncAccessCodes::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::CREATE_ACCESS_CODE
                    => \Seam\Resources\ActionAttempt\CreateAccessCode::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::DELETE_ACCESS_CODE
                    => \Seam\Resources\ActionAttempt\DeleteAccessCode::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::UPDATE_ACCESS_CODE
                    => \Seam\Resources\ActionAttempt\UpdateAccessCode::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::CREATE_NOISE_THRESHOLD
                    => \Seam\Resources\ActionAttempt\CreateNoiseThreshold::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::DELETE_NOISE_THRESHOLD
                    => \Seam\Resources\ActionAttempt\DeleteNoiseThreshold::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\ActionType::UPDATE_NOISE_THRESHOLD
                    => \Seam\Resources\ActionAttempt\UpdateNoiseThreshold::from_json(
                    $json,
                ),
                default
                    => \Seam\Resources\ActionAttempt\UnknownActionAttempt::from_json(
                    $json,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            public string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            public \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            public \Seam\Resources\ActionAttempt\Error|null $error,
            public \Seam\Resources\ActionAttempt\Status|null $status,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt {
    /**
     * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
     */
    class Error
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                message: $json->message ?? null,
                type: $json->type ?? null,
            );
        }

        public function __construct(
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * Type of the error.
             */
            public string|null $type,
        ) {}
    }

    /**
     * Locking a door is pending.
     */
    final class LockDoor extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): LockDoor|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\LockDoor\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public \Seam\Resources\ActionAttempt\LockDoor\Result|null $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    /**
     * Unlocking a door is pending.
     */
    final class UnlockDoor extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): UnlockDoor|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\UnlockDoor\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public \Seam\Resources\ActionAttempt\UnlockDoor\Result|null $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    /**
     * Reading credential data from the physical encoder is pending.
     */
    final class ScanCredential extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): ScanCredential|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of scanning a card. If the attempt was successful, includes a snapshot of credential data read from the physical encoder, the corresponding data stored on Seam and the access system, and any associated warnings. Null while the action attempt is pending or when this value does not apply.
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Result|null $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    /**
     * Encoding credential data from the physical encoder onto a card is pending.
     */
    final class EncodeCredential extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): EncodeCredential|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\EncodeCredential\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of an encoding attempt. If the attempt was successful, includes the credential data that was encoded onto the card. Null while the action attempt is pending or when this value does not apply.
             */
            public \Seam\Resources\ActionAttempt\EncodeCredential\Result|null $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    /**
     * Scanning a physical card and assigning the credential is pending.
     */
    final class ScanToAssignCredential extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(
            mixed $json,
        ): ScanToAssignCredential|null {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of a scan to assign attempt. If the attempt was successful, includes the credential data that was scanned and assigned. Null while the action attempt is pending or when this value does not apply.
             */
            public \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result|null $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    /**
     * Assigning a credential to an access method is pending.
     */
    final class AssignCredential extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): AssignCredential|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\AssignCredential\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of assigning a credential. If successful, includes the updated access method with the assigned credential. Null while the action attempt is pending or when this value does not apply.
             */
            public \Seam\Resources\ActionAttempt\AssignCredential\Result|null $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    /**
     * Resetting a sandbox workspace is pending.
     */
    final class ResetSandboxWorkspace extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(
            mixed $json,
        ): ResetSandboxWorkspace|null {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: $json->result ?? null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public mixed $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    /**
     * Setting the fan mode is pending.
     */
    final class SetFanMode extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): SetFanMode|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: $json->result ?? null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public mixed $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    /**
     * Setting the HVAC mode is pending.
     */
    final class SetHvacMode extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): SetHvacMode|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: $json->result ?? null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public mixed $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    /**
     * Activating a climate preset is pending.
     */
    final class ActivateClimatePreset extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(
            mixed $json,
        ): ActivateClimatePreset|null {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: $json->result ?? null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public mixed $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    /**
     * Simulating a keypad code entry is pending.
     */
    final class SimulateKeypadCodeEntry extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(
            mixed $json,
        ): SimulateKeypadCodeEntry|null {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: $json->result ?? null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public mixed $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    /**
     * Simulating a manual lock action using a keypad is pending.
     */
    final class SimulateManualLockViaKeypad extends
        \Seam\Resources\ActionAttempt
    {
        public static function from_json(
            mixed $json,
        ): SimulateManualLockViaKeypad|null {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: $json->result ?? null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public mixed $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    /**
     * Pushing thermostat weekly programs is pending.
     */
    final class PushThermostatPrograms extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(
            mixed $json,
        ): PushThermostatPrograms|null {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: $json->result ?? null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public mixed $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    /**
     * Configuring the auto-lock is pending.
     */
    final class ConfigureAutoLock extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): ConfigureAutoLock|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: $json->result ?? null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public mixed $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    final class SyncAccessCodes extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): SyncAccessCodes|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: $json->result ?? null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public mixed $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    final class CreateAccessCode extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): CreateAccessCode|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\CreateAccessCode\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public \Seam\Resources\ActionAttempt\CreateAccessCode\Result|null $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    final class DeleteAccessCode extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): DeleteAccessCode|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: $json->result ?? null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public mixed $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    final class UpdateAccessCode extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): UpdateAccessCode|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\UpdateAccessCode\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public \Seam\Resources\ActionAttempt\UpdateAccessCode\Result|null $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    final class CreateNoiseThreshold extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): CreateNoiseThreshold|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\CreateNoiseThreshold\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public \Seam\Resources\ActionAttempt\CreateNoiseThreshold\Result|null $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    final class DeleteNoiseThreshold extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): DeleteNoiseThreshold|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: $json->result ?? null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public mixed $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    final class UpdateNoiseThreshold extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): UpdateNoiseThreshold|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\UpdateNoiseThreshold\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            /**
             * Result of the action. Null while the action attempt is pending or when this value does not apply.
             */
            public \Seam\Resources\ActionAttempt\UpdateNoiseThreshold\Result|null $result,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    /**
     * Fallback for action_attempt values introduced after this SDK version.
     */
    final class UnknownActionAttempt extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): UnknownActionAttempt|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: is_string($json->action_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ActionType::tryFrom(
                            $json->action_type,
                        ) ?? $json->action_type
                    : null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\Error::from_json(
                        $json->error,
                    )
                    : null,
                status: is_string($json->status ?? null)
                    ? \Seam\Resources\ActionAttempt\Status::tryFrom(
                        $json->status,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             */
            \Seam\Resources\ActionAttempt\ActionType|string|null $action_type,
            /**
             * Error associated with the action. Null while the action attempt is pending or when this value does not apply.
             */
            \Seam\Resources\ActionAttempt\Error|null $error,
            \Seam\Resources\ActionAttempt\Status|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                error: $error,
                status: $status,
            );
        }
    }

    enum ActionType: string
    {
        case LOCK_DOOR = "LOCK_DOOR";
        case UNLOCK_DOOR = "UNLOCK_DOOR";
        case SCAN_CREDENTIAL = "SCAN_CREDENTIAL";
        case ENCODE_CREDENTIAL = "ENCODE_CREDENTIAL";
        case SCAN_TO_ASSIGN_CREDENTIAL = "SCAN_TO_ASSIGN_CREDENTIAL";
        case ASSIGN_CREDENTIAL = "ASSIGN_CREDENTIAL";
        case RESET_SANDBOX_WORKSPACE = "RESET_SANDBOX_WORKSPACE";
        case SET_FAN_MODE = "SET_FAN_MODE";
        case SET_HVAC_MODE = "SET_HVAC_MODE";
        case ACTIVATE_CLIMATE_PRESET = "ACTIVATE_CLIMATE_PRESET";
        case SIMULATE_KEYPAD_CODE_ENTRY = "SIMULATE_KEYPAD_CODE_ENTRY";
        case SIMULATE_MANUAL_LOCK_VIA_KEYPAD = "SIMULATE_MANUAL_LOCK_VIA_KEYPAD";
        case PUSH_THERMOSTAT_PROGRAMS = "PUSH_THERMOSTAT_PROGRAMS";
        case CONFIGURE_AUTO_LOCK = "CONFIGURE_AUTO_LOCK";
        case SYNC_ACCESS_CODES = "SYNC_ACCESS_CODES";
        case CREATE_ACCESS_CODE = "CREATE_ACCESS_CODE";
        case DELETE_ACCESS_CODE = "DELETE_ACCESS_CODE";
        case UPDATE_ACCESS_CODE = "UPDATE_ACCESS_CODE";
        case CREATE_NOISE_THRESHOLD = "CREATE_NOISE_THRESHOLD";
        case DELETE_NOISE_THRESHOLD = "DELETE_NOISE_THRESHOLD";
        case UPDATE_NOISE_THRESHOLD = "UPDATE_NOISE_THRESHOLD";
    }

    enum Status: string
    {
        case SUCCESS = "success";
        case PENDING = "pending";
        case ERROR = "error";
    }
}

namespace Seam\Resources\ActionAttempt\LockDoor {
    /**
     * Result of the action. Null while the action attempt is pending or when this value does not apply.
     */
    class Result
    {
        public static function from_json(mixed $json): Result|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                was_confirmed_by_device: $json->was_confirmed_by_device ?? null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the device confirmed that the lock action occurred.
             */
            public bool|null $was_confirmed_by_device = null,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\UnlockDoor {
    /**
     * Result of the action. Null while the action attempt is pending or when this value does not apply.
     */
    class Result
    {
        public static function from_json(mixed $json): Result|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                was_confirmed_by_device: $json->was_confirmed_by_device ?? null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the device confirmed that the unlock action occurred.
             */
            public bool|null $was_confirmed_by_device = null,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\ScanCredential {
    /**
     * Result of scanning a card. If the attempt was successful, includes a snapshot of credential data read from the physical encoder, the corresponding data stored on Seam and the access system, and any associated warnings. Null while the action attempt is pending or when this value does not apply.
     */
    class Result
    {
        public static function from_json(mixed $json): Result|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_credential_on_encoder: isset(
                    $json->acs_credential_on_encoder,
                )
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnEncoder::from_json(
                        $json->acs_credential_on_encoder,
                    )
                    : null,
                acs_credential_on_seam: isset($json->acs_credential_on_seam)
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam::from_json(
                        $json->acs_credential_on_seam,
                    )
                    : null,
                warnings: array_map(
                    fn(
                        $w,
                    ) => \Seam\Resources\ActionAttempt\ScanCredential\Result\Warnings::from_json(
                        $w,
                    ),
                    $json->warnings ?? [],
                ),
            );
        }

        public function __construct(
            /**
             * Snapshot of credential data read from the physical encoder.
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnEncoder|null $acs_credential_on_encoder,
            /**
             * Corresponding credential data as stored on Seam and the access system.
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam|null $acs_credential_on_seam,
            /**
             * Warnings related to scanning the credential, such as mismatches between the credential data currently encoded on the card and the corresponding data stored on Seam and the access system.
             *
             * @var list<\Seam\Resources\ActionAttempt\ScanCredential\Result\Warnings>
             */
            public array $warnings,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\ScanCredential\Result {
    /**
     * Snapshot of credential data read from the physical encoder.
     */
    class AcsCredentialOnEncoder
    {
        public static function from_json(
            mixed $json,
        ): AcsCredentialOnEncoder|null {
            if (!$json) {
                return null;
            }
            return new self(
                card_number: $json->card_number ?? null,
                created_at: $json->created_at ?? null,
                ends_at: $json->ends_at ?? null,
                is_issued: $json->is_issued ?? null,
                starts_at: $json->starts_at ?? null,
                visionline_metadata: isset($json->visionline_metadata)
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnEncoder\VisionlineMetadata::from_json(
                        $json->visionline_metadata,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * A number or string that physically identifies the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $card_number,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was created.
             */
            public string|null $created_at,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) will stop being usable.
             */
            public string|null $ends_at,
            /**
             * Indicates whether the credential has been issued (encoded onto a card).
             */
            public bool|null $is_issued,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) becomes usable.
             */
            public string|null $starts_at,
            /**
             * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnEncoder\VisionlineMetadata|null $visionline_metadata = null,
        ) {}
    }

    /**
     * Corresponding credential data as stored on Seam and the access system.
     */
    class AcsCredentialOnSeam
    {
        public static function from_json(mixed $json): AcsCredentialOnSeam|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_method: is_string($json->access_method ?? null)
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\AccessMethod::tryFrom(
                        $json->access_method,
                    )
                    : null,
                acs_credential_id: $json->acs_credential_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                errors: array_map(
                    fn(
                        $e,
                    ) => \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\Errors::from_json(
                        $e,
                    ),
                    $json->errors ?? [],
                ),
                is_managed: $json->is_managed ?? null,
                warnings: array_map(
                    fn(
                        $w,
                    ) => \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\Warnings::from_json(
                        $w,
                    ),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
                acs_credential_pool_id: $json->acs_credential_pool_id ?? null,
                acs_user_id: $json->acs_user_id ?? null,
                akiles_metadata: isset($json->akiles_metadata)
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\AkilesMetadata::from_json(
                        $json->akiles_metadata,
                    )
                    : null,
                assa_abloy_vostio_metadata: isset(
                    $json->assa_abloy_vostio_metadata,
                )
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\AssaAbloyVostioMetadata::from_json(
                        $json->assa_abloy_vostio_metadata,
                    )
                    : null,
                card_number: $json->card_number ?? null,
                code: $json->code ?? null,
                ends_at: $json->ends_at ?? null,
                external_type: is_string($json->external_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\ExternalType::tryFrom(
                        $json->external_type,
                    )
                    : null,
                external_type_display_name: $json->external_type_display_name ??
                    null,
                is_issued: $json->is_issued ?? null,
                is_latest_desired_state_synced_with_provider: $json->is_latest_desired_state_synced_with_provider ??
                    null,
                is_multi_phone_sync_credential: $json->is_multi_phone_sync_credential ??
                    null,
                is_one_time_use: $json->is_one_time_use ?? null,
                issued_at: $json->issued_at ?? null,
                latest_desired_state_synced_with_provider_at: $json->latest_desired_state_synced_with_provider_at ??
                    null,
                parent_acs_credential_id: $json->parent_acs_credential_id ??
                    null,
                starts_at: $json->starts_at ?? null,
                user_identity_id: $json->user_identity_id ?? null,
                visionline_metadata: isset($json->visionline_metadata)
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\VisionlineMetadata::from_json(
                        $json->visionline_metadata,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * Access method for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials). Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\AccessMethod|null $access_method,
            /**
             * ID of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $acs_credential_id,
            /**
             * ID of the [access control system](https://docs.seam.co/low-level-apis/access-systems) that contains the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $acs_system_id,
            /**
             * ID of the [connected account](https://docs.seam.co/core-concepts/connected-accounts) to which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was created.
             */
            public string|null $created_at,
            /**
             * Display name that corresponds to the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type.
             */
            public string|null $display_name,
            /**
             * Errors associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             *
             * @var list<\Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\Errors>
             */
            public array $errors,
            public bool|null $is_managed,
            /**
             * Warnings associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             *
             * @var list<\Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\Warnings>
             */
            public array $warnings,
            /**
             * ID of the workspace that contains the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $workspace_id,
            /**
             * ID of the credential pool to which the credential belongs.
             */
            public string|null $acs_credential_pool_id = null,
            /**
             * ID of the [ACS user](https://docs.seam.co/low-level-apis/access-systems/user-management) to whom the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
             */
            public string|null $acs_user_id = null,
            /**
             * Akiles-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\AkilesMetadata|null $akiles_metadata = null,
            /**
             * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\AssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata = null,
            /**
             * Number of the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $card_number = null,
            /**
             * Access (PIN) code for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $code = null,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) validity ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after `starts_at`.
             */
            public string|null $ends_at = null,
            /**
             * Brand-specific terminology for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type. Supported values: `pti_card`, `brivo_credential`, `hid_credential`, `visionline_card`.
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\ExternalType|null $external_type = null,
            /**
             * Display name that corresponds to the brand-specific terminology for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type.
             */
            public string|null $external_type_display_name = null,
            /**
             * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) has been encoded onto a card.
             */
            public bool|null $is_issued = null,
            /**
             * Indicates whether the latest state of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) has been synced from Seam to the provider.
             */
            public bool|null $is_latest_desired_state_synced_with_provider = null,
            /**
             * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is a [multi-phone sync credential](https://docs.seam.co/capability-guides/mobile-access/issuing-mobile-credentials-from-an-access-control-system#what-are-multi-phone-sync-credentials).
             */
            public bool|null $is_multi_phone_sync_credential = null,
            /**
             * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) can only be used once. If `true`, the code becomes invalid after the first use.
             */
            public bool|null $is_one_time_use = null,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was encoded onto a card.
             */
            public string|null $issued_at = null,
            /**
             * Date and time at which the state of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was most recently synced from Seam to the provider.
             */
            public string|null $latest_desired_state_synced_with_provider_at = null,
            /**
             * ID of the parent [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $parent_acs_credential_id = null,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) validity starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
             */
            public string|null $starts_at = null,
            /**
             * ID of the [user identity](https://docs.seam.co/api/user_identities) to whom the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
             */
            public string|null $user_identity_id = null,
            /**
             * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\VisionlineMetadata|null $visionline_metadata = null,
        ) {}
    }

    /**
     * Warnings related to scanning the credential, such as mismatches between the credential data currently encoded on the card and the corresponding data stored on Seam and the access system.
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Result\Warnings\WarningCode::tryFrom(
                        $json->warning_code,
                    )
                    : null,
                warning_message: $json->warning_message ?? null,
            );
        }

        public function __construct(
            /**
             * Indicates a warning related to scanning a credential.
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Result\Warnings\WarningCode|null $warning_code,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $warning_message,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnEncoder {
    /**
     * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class VisionlineMetadata
    {
        public static function from_json(mixed $json): VisionlineMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                cancelled: $json->cancelled ?? null,
                card_format: is_string($json->card_format ?? null)
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnEncoder\VisionlineMetadata\CardFormat::tryFrom(
                        $json->card_format,
                    )
                    : null,
                card_holder: $json->card_holder ?? null,
                card_id: $json->card_id ?? null,
                common_acs_entrance_ids: $json->common_acs_entrance_ids ?? null,
                discarded: $json->discarded ?? null,
                expired: $json->expired ?? null,
                guest_acs_entrance_ids: $json->guest_acs_entrance_ids ?? null,
                number_of_issued_cards: $json->number_of_issued_cards ?? null,
                overridden: $json->overridden ?? null,
                overwritten: $json->overwritten ?? null,
                pending_auto_update: $json->pending_auto_update ?? null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is cancelled.
             */
            public bool|null $cancelled = null,
            /**
             * Format of the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnEncoder\VisionlineMetadata\CardFormat|null $card_format = null,
            /**
             * Holder of the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $card_holder = null,
            /**
             * Card ID for the Visionline card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $card_id = null,
            /**
             * IDs of the common [entrances](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             *
             * @var list<string>|null
             */
            public array|null $common_acs_entrance_ids = null,
            /**
             * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is discarded.
             */
            public bool|null $discarded = null,
            /**
             * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is expired.
             */
            public bool|null $expired = null,
            /**
             * IDs of the guest [entrances](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             *
             * @var list<string>|null
             */
            public array|null $guest_acs_entrance_ids = null,
            /**
             * Number of issued cards associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public float|null $number_of_issued_cards = null,
            /**
             * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is overridden.
             */
            public bool|null $overridden = null,
            /**
             * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is overwritten.
             */
            public bool|null $overwritten = null,
            /**
             * Indicates whether the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is pending auto-update.
             */
            public bool|null $pending_auto_update = null,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnEncoder\VisionlineMetadata {
    enum CardFormat: string
    {
        case TL_CODE = "TLCode";
        case RFID48 = "rfid48";
    }
}

namespace Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam {
    /**
     * Akiles-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class AkilesMetadata
    {
        public static function from_json(mixed $json): AkilesMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(member_pin_id: $json->member_pin_id ?? null);
        }

        public function __construct(
            /**
             * ID of the Akiles member PIN.
             */
            public string|null $member_pin_id = null,
        ) {}
    }

    /**
     * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class AssaAbloyVostioMetadata
    {
        public static function from_json(
            mixed $json,
        ): AssaAbloyVostioMetadata|null {
            if (!$json) {
                return null;
            }
            return new self(
                auto_join: $json->auto_join ?? null,
                door_names: $json->door_names ?? null,
                endpoint_id: $json->endpoint_id ?? null,
                key_id: $json->key_id ?? null,
                key_issuing_request_id: $json->key_issuing_request_id ?? null,
                override_guest_acs_entrance_ids: $json->override_guest_acs_entrance_ids ??
                    null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the credential should auto-join. For an auto-join credential, Seam automatically issues an override card if there are no other cards and a joiner card if there are existing cards on the doors.
             */
            public bool|null $auto_join = null,
            /**
             * Names of the doors to which to grant access in the Vostio access system.
             *
             * @var list<string>|null
             */
            public array|null $door_names = null,
            /**
             * Endpoint ID in the Vostio access system.
             */
            public string|null $endpoint_id = null,
            /**
             * Key ID in the Vostio access system.
             */
            public string|null $key_id = null,
            /**
             * Key issuing request ID in the Vostio access system.
             */
            public string|null $key_issuing_request_id = null,
            /**
             * IDs of the guest entrances to override in the Vostio access system.
             *
             * @var list<string>|null
             */
            public array|null $override_guest_acs_entrance_ids = null,
        ) {}
    }

    /**
     * Errors associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at,
            public string|null $error_code,
            public string|null $message,
        ) {}
    }

    /**
     * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class VisionlineMetadata
    {
        public static function from_json(mixed $json): VisionlineMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                auto_join: $json->auto_join ?? null,
                card_function_type: is_string($json->card_function_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\VisionlineMetadata\CardFunctionType::tryFrom(
                        $json->card_function_type,
                    )
                    : null,
                card_id: $json->card_id ?? null,
                common_acs_entrance_ids: $json->common_acs_entrance_ids ?? null,
                credential_id: $json->credential_id ?? null,
                guest_acs_entrance_ids: $json->guest_acs_entrance_ids ?? null,
                is_valid: $json->is_valid ?? null,
                joiner_acs_credential_ids: $json->joiner_acs_credential_ids ??
                    null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the credential should auto-join. For an auto-join credential, Seam automatically issues an override card if there are no other cards and a joiner card if there are existing cards on the doors.
             */
            public bool|null $auto_join = null,
            /**
             * Card function type in the Visionline access system.
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\VisionlineMetadata\CardFunctionType|null $card_function_type = null,
            /**
             * ID of the card in the Visionline access system.
             */
            public string|null $card_id = null,
            /**
             * Common entrance IDs in the Visionline access system.
             *
             * @var list<string>|null
             */
            public array|null $common_acs_entrance_ids = null,
            /**
             * ID of the credential in the Visionline access system.
             */
            public string|null $credential_id = null,
            /**
             * Guest entrance IDs in the Visionline access system.
             *
             * @var list<string>|null
             */
            public array|null $guest_acs_entrance_ids = null,
            /**
             * Indicates whether the credential is valid.
             */
            public bool|null $is_valid = null,
            /**
             * IDs of the credentials to which you want to join.
             *
             * @var list<string>|null
             */
            public array|null $joiner_acs_credential_ids = null,
        ) {}
    }

    /**
     * Warnings associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\Warnings\WarningCode::tryFrom(
                        $json->warning_code,
                    )
                    : null,
                new_code: $json->new_code ?? null,
                original_code: $json->original_code ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the warning.
             */
            public string|null $created_at,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\Warnings\WarningCode|null $warning_code,
            /**
             * The PIN code that was assigned instead.
             */
            public string|null $new_code = null,
            /**
             * The originally requested PIN code that could not be used.
             */
            public string|null $original_code = null,
        ) {}
    }

    enum AccessMethod: string
    {
        case CODE = "code";
        case CARD = "card";
        case MOBILE_KEY = "mobile_key";
        case CLOUD_KEY = "cloud_key";
    }

    enum ExternalType: string
    {
        case PTI_CARD = "pti_card";
        case BRIVO_CREDENTIAL = "brivo_credential";
        case HID_CREDENTIAL = "hid_credential";
        case VISIONLINE_CARD = "visionline_card";
        case SALTO_KS_CREDENTIAL = "salto_ks_credential";
        case ASSA_ABLOY_VOSTIO_KEY = "assa_abloy_vostio_key";
        case SALTO_SPACE_KEY = "salto_space_key";
        case LATCH_ACCESS = "latch_access";
        case DORMAKABA_AMBIANCE_CREDENTIAL = "dormakaba_ambiance_credential";
        case HOTEK_CARD = "hotek_card";
        case SALTO_KS_TAG = "salto_ks_tag";
        case AVIGILON_ALTA_CREDENTIAL = "avigilon_alta_credential";
        case KISI_CREDENTIAL = "kisi_credential";
        case AKILES_CREDENTIAL = "akiles_credential";
    }
}

namespace Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\VisionlineMetadata {
    enum CardFunctionType: string
    {
        case GUEST = "guest";
        case STAFF = "staff";
    }
}

namespace Seam\Resources\ActionAttempt\ScanCredential\Result\AcsCredentialOnSeam\Warnings {
    enum WarningCode: string
    {
        case WAITING_TO_BE_ISSUED = "waiting_to_be_issued";
        case SCHEDULE_EXTERNALLY_MODIFIED = "schedule_externally_modified";
        case SCHEDULE_MODIFIED = "schedule_modified";
        case BEING_DELETED = "being_deleted";
        case UNKNOWN_ISSUE_WITH_ACS_CREDENTIAL = "unknown_issue_with_acs_credential";
        case NEEDS_TO_BE_REISSUED = "needs_to_be_reissued";
        case REQUESTED_CODE_UNAVAILABLE = "requested_code_unavailable";
    }
}

namespace Seam\Resources\ActionAttempt\ScanCredential\Result\Warnings {
    enum WarningCode: string
    {
        case ACS_CREDENTIAL_ON_ENCODER_OUT_OF_SYNC = "acs_credential_on_encoder_out_of_sync";
        case ACS_CREDENTIAL_ON_SEAM_NOT_FOUND = "acs_credential_on_seam_not_found";
    }
}

namespace Seam\Resources\ActionAttempt\EncodeCredential {
    /**
     * Result of an encoding attempt. If the attempt was successful, includes the credential data that was encoded onto the card. Null while the action attempt is pending or when this value does not apply.
     */
    class Result
    {
        public static function from_json(mixed $json): Result|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_method: is_string($json->access_method ?? null)
                    ? \Seam\Resources\ActionAttempt\EncodeCredential\Result\AccessMethod::tryFrom(
                        $json->access_method,
                    )
                    : null,
                acs_credential_id: $json->acs_credential_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                errors: array_map(
                    fn(
                        $e,
                    ) => \Seam\Resources\ActionAttempt\EncodeCredential\Result\Errors::from_json(
                        $e,
                    ),
                    $json->errors ?? [],
                ),
                is_managed: $json->is_managed ?? null,
                warnings: array_map(
                    fn(
                        $w,
                    ) => \Seam\Resources\ActionAttempt\EncodeCredential\Result\Warnings::from_json(
                        $w,
                    ),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
                acs_credential_pool_id: $json->acs_credential_pool_id ?? null,
                acs_user_id: $json->acs_user_id ?? null,
                akiles_metadata: isset($json->akiles_metadata)
                    ? \Seam\Resources\ActionAttempt\EncodeCredential\Result\AkilesMetadata::from_json(
                        $json->akiles_metadata,
                    )
                    : null,
                assa_abloy_vostio_metadata: isset(
                    $json->assa_abloy_vostio_metadata,
                )
                    ? \Seam\Resources\ActionAttempt\EncodeCredential\Result\AssaAbloyVostioMetadata::from_json(
                        $json->assa_abloy_vostio_metadata,
                    )
                    : null,
                card_number: $json->card_number ?? null,
                code: $json->code ?? null,
                ends_at: $json->ends_at ?? null,
                external_type: is_string($json->external_type ?? null)
                    ? \Seam\Resources\ActionAttempt\EncodeCredential\Result\ExternalType::tryFrom(
                        $json->external_type,
                    )
                    : null,
                external_type_display_name: $json->external_type_display_name ??
                    null,
                is_issued: $json->is_issued ?? null,
                is_latest_desired_state_synced_with_provider: $json->is_latest_desired_state_synced_with_provider ??
                    null,
                is_multi_phone_sync_credential: $json->is_multi_phone_sync_credential ??
                    null,
                is_one_time_use: $json->is_one_time_use ?? null,
                issued_at: $json->issued_at ?? null,
                latest_desired_state_synced_with_provider_at: $json->latest_desired_state_synced_with_provider_at ??
                    null,
                parent_acs_credential_id: $json->parent_acs_credential_id ??
                    null,
                starts_at: $json->starts_at ?? null,
                user_identity_id: $json->user_identity_id ?? null,
                visionline_metadata: isset($json->visionline_metadata)
                    ? \Seam\Resources\ActionAttempt\EncodeCredential\Result\VisionlineMetadata::from_json(
                        $json->visionline_metadata,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * Access method for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials). Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
             */
            public \Seam\Resources\ActionAttempt\EncodeCredential\Result\AccessMethod|null $access_method,
            /**
             * ID of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $acs_credential_id,
            /**
             * ID of the [access control system](https://docs.seam.co/low-level-apis/access-systems) that contains the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $acs_system_id,
            /**
             * ID of the [connected account](https://docs.seam.co/core-concepts/connected-accounts) to which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was created.
             */
            public string|null $created_at,
            /**
             * Display name that corresponds to the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type.
             */
            public string|null $display_name,
            /**
             * Errors associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             *
             * @var list<\Seam\Resources\ActionAttempt\EncodeCredential\Result\Errors>
             */
            public array $errors,
            public bool|null $is_managed,
            /**
             * Warnings associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             *
             * @var list<\Seam\Resources\ActionAttempt\EncodeCredential\Result\Warnings>
             */
            public array $warnings,
            /**
             * ID of the workspace that contains the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $workspace_id,
            /**
             * ID of the credential pool to which the credential belongs.
             */
            public string|null $acs_credential_pool_id = null,
            /**
             * ID of the [ACS user](https://docs.seam.co/low-level-apis/access-systems/user-management) to whom the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
             */
            public string|null $acs_user_id = null,
            /**
             * Akiles-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public \Seam\Resources\ActionAttempt\EncodeCredential\Result\AkilesMetadata|null $akiles_metadata = null,
            /**
             * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public \Seam\Resources\ActionAttempt\EncodeCredential\Result\AssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata = null,
            /**
             * Number of the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $card_number = null,
            /**
             * Access (PIN) code for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $code = null,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) validity ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after `starts_at`.
             */
            public string|null $ends_at = null,
            /**
             * Brand-specific terminology for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type. Supported values: `pti_card`, `brivo_credential`, `hid_credential`, `visionline_card`.
             */
            public \Seam\Resources\ActionAttempt\EncodeCredential\Result\ExternalType|null $external_type = null,
            /**
             * Display name that corresponds to the brand-specific terminology for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type.
             */
            public string|null $external_type_display_name = null,
            /**
             * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) has been encoded onto a card.
             */
            public bool|null $is_issued = null,
            /**
             * Indicates whether the latest state of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) has been synced from Seam to the provider.
             */
            public bool|null $is_latest_desired_state_synced_with_provider = null,
            /**
             * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is a [multi-phone sync credential](https://docs.seam.co/capability-guides/mobile-access/issuing-mobile-credentials-from-an-access-control-system#what-are-multi-phone-sync-credentials).
             */
            public bool|null $is_multi_phone_sync_credential = null,
            /**
             * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) can only be used once. If `true`, the code becomes invalid after the first use.
             */
            public bool|null $is_one_time_use = null,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was encoded onto a card.
             */
            public string|null $issued_at = null,
            /**
             * Date and time at which the state of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was most recently synced from Seam to the provider.
             */
            public string|null $latest_desired_state_synced_with_provider_at = null,
            /**
             * ID of the parent [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $parent_acs_credential_id = null,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) validity starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
             */
            public string|null $starts_at = null,
            /**
             * ID of the [user identity](https://docs.seam.co/api/user_identities) to whom the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
             */
            public string|null $user_identity_id = null,
            /**
             * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public \Seam\Resources\ActionAttempt\EncodeCredential\Result\VisionlineMetadata|null $visionline_metadata = null,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\EncodeCredential\Result {
    /**
     * Akiles-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class AkilesMetadata
    {
        public static function from_json(mixed $json): AkilesMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(member_pin_id: $json->member_pin_id ?? null);
        }

        public function __construct(
            /**
             * ID of the Akiles member PIN.
             */
            public string|null $member_pin_id = null,
        ) {}
    }

    /**
     * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class AssaAbloyVostioMetadata
    {
        public static function from_json(
            mixed $json,
        ): AssaAbloyVostioMetadata|null {
            if (!$json) {
                return null;
            }
            return new self(
                auto_join: $json->auto_join ?? null,
                door_names: $json->door_names ?? null,
                endpoint_id: $json->endpoint_id ?? null,
                key_id: $json->key_id ?? null,
                key_issuing_request_id: $json->key_issuing_request_id ?? null,
                override_guest_acs_entrance_ids: $json->override_guest_acs_entrance_ids ??
                    null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the credential should auto-join. For an auto-join credential, Seam automatically issues an override card if there are no other cards and a joiner card if there are existing cards on the doors.
             */
            public bool|null $auto_join = null,
            /**
             * Names of the doors to which to grant access in the Vostio access system.
             *
             * @var list<string>|null
             */
            public array|null $door_names = null,
            /**
             * Endpoint ID in the Vostio access system.
             */
            public string|null $endpoint_id = null,
            /**
             * Key ID in the Vostio access system.
             */
            public string|null $key_id = null,
            /**
             * Key issuing request ID in the Vostio access system.
             */
            public string|null $key_issuing_request_id = null,
            /**
             * IDs of the guest entrances to override in the Vostio access system.
             *
             * @var list<string>|null
             */
            public array|null $override_guest_acs_entrance_ids = null,
        ) {}
    }

    /**
     * Errors associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at,
            public string|null $error_code,
            public string|null $message,
        ) {}
    }

    /**
     * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class VisionlineMetadata
    {
        public static function from_json(mixed $json): VisionlineMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                auto_join: $json->auto_join ?? null,
                card_function_type: is_string($json->card_function_type ?? null)
                    ? \Seam\Resources\ActionAttempt\EncodeCredential\Result\VisionlineMetadata\CardFunctionType::tryFrom(
                        $json->card_function_type,
                    )
                    : null,
                card_id: $json->card_id ?? null,
                common_acs_entrance_ids: $json->common_acs_entrance_ids ?? null,
                credential_id: $json->credential_id ?? null,
                guest_acs_entrance_ids: $json->guest_acs_entrance_ids ?? null,
                is_valid: $json->is_valid ?? null,
                joiner_acs_credential_ids: $json->joiner_acs_credential_ids ??
                    null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the credential should auto-join. For an auto-join credential, Seam automatically issues an override card if there are no other cards and a joiner card if there are existing cards on the doors.
             */
            public bool|null $auto_join = null,
            /**
             * Card function type in the Visionline access system.
             */
            public \Seam\Resources\ActionAttempt\EncodeCredential\Result\VisionlineMetadata\CardFunctionType|null $card_function_type = null,
            /**
             * ID of the card in the Visionline access system.
             */
            public string|null $card_id = null,
            /**
             * Common entrance IDs in the Visionline access system.
             *
             * @var list<string>|null
             */
            public array|null $common_acs_entrance_ids = null,
            /**
             * ID of the credential in the Visionline access system.
             */
            public string|null $credential_id = null,
            /**
             * Guest entrance IDs in the Visionline access system.
             *
             * @var list<string>|null
             */
            public array|null $guest_acs_entrance_ids = null,
            /**
             * Indicates whether the credential is valid.
             */
            public bool|null $is_valid = null,
            /**
             * IDs of the credentials to which you want to join.
             *
             * @var list<string>|null
             */
            public array|null $joiner_acs_credential_ids = null,
        ) {}
    }

    /**
     * Warnings associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\ActionAttempt\EncodeCredential\Result\Warnings\WarningCode::tryFrom(
                        $json->warning_code,
                    )
                    : null,
                new_code: $json->new_code ?? null,
                original_code: $json->original_code ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the warning.
             */
            public string|null $created_at,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             */
            public \Seam\Resources\ActionAttempt\EncodeCredential\Result\Warnings\WarningCode|null $warning_code,
            /**
             * The PIN code that was assigned instead.
             */
            public string|null $new_code = null,
            /**
             * The originally requested PIN code that could not be used.
             */
            public string|null $original_code = null,
        ) {}
    }

    enum AccessMethod: string
    {
        case CODE = "code";
        case CARD = "card";
        case MOBILE_KEY = "mobile_key";
        case CLOUD_KEY = "cloud_key";
    }

    enum ExternalType: string
    {
        case PTI_CARD = "pti_card";
        case BRIVO_CREDENTIAL = "brivo_credential";
        case HID_CREDENTIAL = "hid_credential";
        case VISIONLINE_CARD = "visionline_card";
        case SALTO_KS_CREDENTIAL = "salto_ks_credential";
        case ASSA_ABLOY_VOSTIO_KEY = "assa_abloy_vostio_key";
        case SALTO_SPACE_KEY = "salto_space_key";
        case LATCH_ACCESS = "latch_access";
        case DORMAKABA_AMBIANCE_CREDENTIAL = "dormakaba_ambiance_credential";
        case HOTEK_CARD = "hotek_card";
        case SALTO_KS_TAG = "salto_ks_tag";
        case AVIGILON_ALTA_CREDENTIAL = "avigilon_alta_credential";
        case KISI_CREDENTIAL = "kisi_credential";
        case AKILES_CREDENTIAL = "akiles_credential";
    }
}

namespace Seam\Resources\ActionAttempt\EncodeCredential\Result\VisionlineMetadata {
    enum CardFunctionType: string
    {
        case GUEST = "guest";
        case STAFF = "staff";
    }
}

namespace Seam\Resources\ActionAttempt\EncodeCredential\Result\Warnings {
    enum WarningCode: string
    {
        case WAITING_TO_BE_ISSUED = "waiting_to_be_issued";
        case SCHEDULE_EXTERNALLY_MODIFIED = "schedule_externally_modified";
        case SCHEDULE_MODIFIED = "schedule_modified";
        case BEING_DELETED = "being_deleted";
        case UNKNOWN_ISSUE_WITH_ACS_CREDENTIAL = "unknown_issue_with_acs_credential";
        case NEEDS_TO_BE_REISSUED = "needs_to_be_reissued";
        case REQUESTED_CODE_UNAVAILABLE = "requested_code_unavailable";
    }
}

namespace Seam\Resources\ActionAttempt\ScanToAssignCredential {
    /**
     * Result of a scan to assign attempt. If the attempt was successful, includes the credential data that was scanned and assigned. Null while the action attempt is pending or when this value does not apply.
     */
    class Result
    {
        public static function from_json(mixed $json): Result|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_method: is_string($json->access_method ?? null)
                    ? \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\AccessMethod::tryFrom(
                        $json->access_method,
                    )
                    : null,
                acs_credential_id: $json->acs_credential_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                errors: array_map(
                    fn(
                        $e,
                    ) => \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\Errors::from_json(
                        $e,
                    ),
                    $json->errors ?? [],
                ),
                is_managed: $json->is_managed ?? null,
                warnings: array_map(
                    fn(
                        $w,
                    ) => \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\Warnings::from_json(
                        $w,
                    ),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
                acs_credential_pool_id: $json->acs_credential_pool_id ?? null,
                acs_user_id: $json->acs_user_id ?? null,
                akiles_metadata: isset($json->akiles_metadata)
                    ? \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\AkilesMetadata::from_json(
                        $json->akiles_metadata,
                    )
                    : null,
                assa_abloy_vostio_metadata: isset(
                    $json->assa_abloy_vostio_metadata,
                )
                    ? \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\AssaAbloyVostioMetadata::from_json(
                        $json->assa_abloy_vostio_metadata,
                    )
                    : null,
                card_number: $json->card_number ?? null,
                code: $json->code ?? null,
                ends_at: $json->ends_at ?? null,
                external_type: is_string($json->external_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\ExternalType::tryFrom(
                        $json->external_type,
                    )
                    : null,
                external_type_display_name: $json->external_type_display_name ??
                    null,
                is_issued: $json->is_issued ?? null,
                is_latest_desired_state_synced_with_provider: $json->is_latest_desired_state_synced_with_provider ??
                    null,
                is_multi_phone_sync_credential: $json->is_multi_phone_sync_credential ??
                    null,
                is_one_time_use: $json->is_one_time_use ?? null,
                issued_at: $json->issued_at ?? null,
                latest_desired_state_synced_with_provider_at: $json->latest_desired_state_synced_with_provider_at ??
                    null,
                parent_acs_credential_id: $json->parent_acs_credential_id ??
                    null,
                starts_at: $json->starts_at ?? null,
                user_identity_id: $json->user_identity_id ?? null,
                visionline_metadata: isset($json->visionline_metadata)
                    ? \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\VisionlineMetadata::from_json(
                        $json->visionline_metadata,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * Access method for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials). Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
             */
            public \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\AccessMethod|null $access_method,
            /**
             * ID of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $acs_credential_id,
            /**
             * ID of the [access control system](https://docs.seam.co/low-level-apis/access-systems) that contains the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $acs_system_id,
            /**
             * ID of the [connected account](https://docs.seam.co/core-concepts/connected-accounts) to which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was created.
             */
            public string|null $created_at,
            /**
             * Display name that corresponds to the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type.
             */
            public string|null $display_name,
            /**
             * Errors associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             *
             * @var list<\Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\Errors>
             */
            public array $errors,
            /**
             * Indicates whether Seam manages the credential.
             */
            public true|null $is_managed,
            /**
             * Warnings associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             *
             * @var list<\Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\Warnings>
             */
            public array $warnings,
            /**
             * ID of the workspace that contains the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $workspace_id,
            /**
             * ID of the credential pool to which the credential belongs.
             */
            public string|null $acs_credential_pool_id = null,
            /**
             * ID of the [ACS user](https://docs.seam.co/low-level-apis/access-systems/user-management) to whom the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
             */
            public string|null $acs_user_id = null,
            /**
             * Akiles-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\AkilesMetadata|null $akiles_metadata = null,
            /**
             * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\AssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata = null,
            /**
             * Number of the card associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $card_number = null,
            /**
             * Access (PIN) code for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $code = null,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) validity ends, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format. Must be a time in the future and after `starts_at`.
             */
            public string|null $ends_at = null,
            /**
             * Brand-specific terminology for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type. Supported values: `pti_card`, `brivo_credential`, `hid_credential`, `visionline_card`.
             */
            public \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\ExternalType|null $external_type = null,
            /**
             * Display name that corresponds to the brand-specific terminology for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) type.
             */
            public string|null $external_type_display_name = null,
            /**
             * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) has been encoded onto a card.
             */
            public bool|null $is_issued = null,
            /**
             * Indicates whether the latest state of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) has been synced from Seam to the provider.
             */
            public bool|null $is_latest_desired_state_synced_with_provider = null,
            /**
             * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) is a [multi-phone sync credential](https://docs.seam.co/capability-guides/mobile-access/issuing-mobile-credentials-from-an-access-control-system#what-are-multi-phone-sync-credentials).
             */
            public bool|null $is_multi_phone_sync_credential = null,
            /**
             * Indicates whether the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) can only be used once. If `true`, the code becomes invalid after the first use.
             */
            public bool|null $is_one_time_use = null,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was encoded onto a card.
             */
            public string|null $issued_at = null,
            /**
             * Date and time at which the state of the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was most recently synced from Seam to the provider.
             */
            public string|null $latest_desired_state_synced_with_provider_at = null,
            /**
             * ID of the parent [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public string|null $parent_acs_credential_id = null,
            /**
             * Date and time at which the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) validity starts, in [ISO 8601](https://www.iso.org/iso-8601-date-and-time-format.html) format.
             */
            public string|null $starts_at = null,
            /**
             * ID of the [user identity](https://docs.seam.co/api/user_identities) to whom the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) belongs.
             */
            public string|null $user_identity_id = null,
            /**
             * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\VisionlineMetadata|null $visionline_metadata = null,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\ScanToAssignCredential\Result {
    /**
     * Akiles-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class AkilesMetadata
    {
        public static function from_json(mixed $json): AkilesMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(member_pin_id: $json->member_pin_id ?? null);
        }

        public function __construct(
            /**
             * ID of the Akiles member PIN.
             */
            public string|null $member_pin_id = null,
        ) {}
    }

    /**
     * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class AssaAbloyVostioMetadata
    {
        public static function from_json(
            mixed $json,
        ): AssaAbloyVostioMetadata|null {
            if (!$json) {
                return null;
            }
            return new self(
                auto_join: $json->auto_join ?? null,
                door_names: $json->door_names ?? null,
                endpoint_id: $json->endpoint_id ?? null,
                key_id: $json->key_id ?? null,
                key_issuing_request_id: $json->key_issuing_request_id ?? null,
                override_guest_acs_entrance_ids: $json->override_guest_acs_entrance_ids ??
                    null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the credential should auto-join. For an auto-join credential, Seam automatically issues an override card if there are no other cards and a joiner card if there are existing cards on the doors.
             */
            public bool|null $auto_join = null,
            /**
             * Names of the doors to which to grant access in the Vostio access system.
             *
             * @var list<string>|null
             */
            public array|null $door_names = null,
            /**
             * Endpoint ID in the Vostio access system.
             */
            public string|null $endpoint_id = null,
            /**
             * Key ID in the Vostio access system.
             */
            public string|null $key_id = null,
            /**
             * Key issuing request ID in the Vostio access system.
             */
            public string|null $key_issuing_request_id = null,
            /**
             * IDs of the guest entrances to override in the Vostio access system.
             *
             * @var list<string>|null
             */
            public array|null $override_guest_acs_entrance_ids = null,
        ) {}
    }

    /**
     * Errors associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: $json->error_code ?? null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at,
            public string|null $error_code,
            public string|null $message,
        ) {}
    }

    /**
     * Visionline-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class VisionlineMetadata
    {
        public static function from_json(mixed $json): VisionlineMetadata|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                auto_join: $json->auto_join ?? null,
                card_function_type: is_string($json->card_function_type ?? null)
                    ? \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\VisionlineMetadata\CardFunctionType::tryFrom(
                        $json->card_function_type,
                    )
                    : null,
                card_id: $json->card_id ?? null,
                common_acs_entrance_ids: $json->common_acs_entrance_ids ?? null,
                credential_id: $json->credential_id ?? null,
                guest_acs_entrance_ids: $json->guest_acs_entrance_ids ?? null,
                is_valid: $json->is_valid ?? null,
                joiner_acs_credential_ids: $json->joiner_acs_credential_ids ??
                    null,
            );
        }

        public function __construct(
            /**
             * Indicates whether the credential should auto-join. For an auto-join credential, Seam automatically issues an override card if there are no other cards and a joiner card if there are existing cards on the doors.
             */
            public bool|null $auto_join = null,
            /**
             * Card function type in the Visionline access system.
             */
            public \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\VisionlineMetadata\CardFunctionType|null $card_function_type = null,
            /**
             * ID of the card in the Visionline access system.
             */
            public string|null $card_id = null,
            /**
             * Common entrance IDs in the Visionline access system.
             *
             * @var list<string>|null
             */
            public array|null $common_acs_entrance_ids = null,
            /**
             * ID of the credential in the Visionline access system.
             */
            public string|null $credential_id = null,
            /**
             * Guest entrance IDs in the Visionline access system.
             *
             * @var list<string>|null
             */
            public array|null $guest_acs_entrance_ids = null,
            /**
             * Indicates whether the credential is valid.
             */
            public bool|null $is_valid = null,
            /**
             * IDs of the credentials to which you want to join.
             *
             * @var list<string>|null
             */
            public array|null $joiner_acs_credential_ids = null,
        ) {}
    }

    /**
     * Warnings associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\Warnings\WarningCode::tryFrom(
                        $json->warning_code,
                    )
                    : null,
                new_code: $json->new_code ?? null,
                original_code: $json->original_code ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the warning.
             */
            public string|null $created_at,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             */
            public \Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\Warnings\WarningCode|null $warning_code,
            /**
             * The PIN code that was assigned instead.
             */
            public string|null $new_code = null,
            /**
             * The originally requested PIN code that could not be used.
             */
            public string|null $original_code = null,
        ) {}
    }

    enum AccessMethod: string
    {
        case CODE = "code";
        case CARD = "card";
        case MOBILE_KEY = "mobile_key";
        case CLOUD_KEY = "cloud_key";
    }

    enum ExternalType: string
    {
        case PTI_CARD = "pti_card";
        case BRIVO_CREDENTIAL = "brivo_credential";
        case HID_CREDENTIAL = "hid_credential";
        case VISIONLINE_CARD = "visionline_card";
        case SALTO_KS_CREDENTIAL = "salto_ks_credential";
        case ASSA_ABLOY_VOSTIO_KEY = "assa_abloy_vostio_key";
        case SALTO_SPACE_KEY = "salto_space_key";
        case LATCH_ACCESS = "latch_access";
        case DORMAKABA_AMBIANCE_CREDENTIAL = "dormakaba_ambiance_credential";
        case HOTEK_CARD = "hotek_card";
        case SALTO_KS_TAG = "salto_ks_tag";
        case AVIGILON_ALTA_CREDENTIAL = "avigilon_alta_credential";
        case KISI_CREDENTIAL = "kisi_credential";
        case AKILES_CREDENTIAL = "akiles_credential";
    }
}

namespace Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\VisionlineMetadata {
    enum CardFunctionType: string
    {
        case GUEST = "guest";
        case STAFF = "staff";
    }
}

namespace Seam\Resources\ActionAttempt\ScanToAssignCredential\Result\Warnings {
    enum WarningCode: string
    {
        case WAITING_TO_BE_ISSUED = "waiting_to_be_issued";
        case SCHEDULE_EXTERNALLY_MODIFIED = "schedule_externally_modified";
        case SCHEDULE_MODIFIED = "schedule_modified";
        case BEING_DELETED = "being_deleted";
        case UNKNOWN_ISSUE_WITH_ACS_CREDENTIAL = "unknown_issue_with_acs_credential";
        case NEEDS_TO_BE_REISSUED = "needs_to_be_reissued";
        case REQUESTED_CODE_UNAVAILABLE = "requested_code_unavailable";
    }
}

namespace Seam\Resources\ActionAttempt\AssignCredential {
    /**
     * Result of assigning a credential. If successful, includes the updated access method with the assigned credential. Null while the action attempt is pending or when this value does not apply.
     */
    class Result
    {
        public static function from_json(mixed $json): Result|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_method_id: $json->access_method_id ?? null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                errors: array_map(
                    fn(
                        $e,
                    ) => \Seam\Resources\ActionAttempt\AssignCredential\Result\Errors::from_json(
                        $e,
                    ),
                    $json->errors ?? [],
                ),
                is_issued: $json->is_issued ?? null,
                issued_at: $json->issued_at ?? null,
                mode: is_string($json->mode ?? null)
                    ? \Seam\Resources\ActionAttempt\AssignCredential\Result\Mode::tryFrom(
                        $json->mode,
                    )
                    : null,
                pending_mutations: array_map(
                    fn(
                        $p,
                    ) => \Seam\Resources\ActionAttempt\AssignCredential\Result\PendingMutations::from_json(
                        $p,
                    ),
                    $json->pending_mutations ?? [],
                ),
                warnings: array_map(
                    fn(
                        $w,
                    ) => \Seam\Resources\ActionAttempt\AssignCredential\Result\Warnings::from_json(
                        $w,
                    ),
                    $json->warnings ?? [],
                ),
                workspace_id: $json->workspace_id ?? null,
                client_session_token: $json->client_session_token ?? null,
                code: $json->code ?? null,
                customization_profile_id: $json->customization_profile_id ??
                    null,
                instant_key_url: $json->instant_key_url ?? null,
                is_assignment_required: $json->is_assignment_required ?? null,
                is_encoding_required: $json->is_encoding_required ?? null,
                is_ready_for_assignment: $json->is_ready_for_assignment ?? null,
                is_ready_for_encoding: $json->is_ready_for_encoding ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the access method.
             */
            public string|null $access_method_id,
            /**
             * Date and time at which the access method was created.
             */
            public string|null $created_at,
            /**
             * Display name of the access method.
             */
            public string|null $display_name,
            /**
             * Errors associated with the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant).
             *
             * @var list<\Seam\Resources\ActionAttempt\AssignCredential\Result\Errors>
             */
            public array $errors,
            /**
             * Indicates whether the access method has been issued.
             */
            public bool|null $is_issued,
            /**
             * Date and time at which the access method was issued.
             */
            public string|null $issued_at,
            /**
             * Access method mode. Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
             */
            public \Seam\Resources\ActionAttempt\AssignCredential\Result\Mode|null $mode,
            /**
             * Pending mutations for the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant). Indicates operations that are in progress.
             *
             * @var list<\Seam\Resources\ActionAttempt\AssignCredential\Result\PendingMutations>
             */
            public array $pending_mutations,
            /**
             * Warnings associated with the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant).
             *
             * @var list<\Seam\Resources\ActionAttempt\AssignCredential\Result\Warnings>
             */
            public array $warnings,
            /**
             * ID of the Seam workspace associated with the access method.
             */
            public string|null $workspace_id,
            /**
             * Token of the client session associated with the access method.
             */
            public string|null $client_session_token = null,
            /**
             * The actual PIN code for code access methods.
             */
            public string|null $code = null,
            /**
             * ID of the customization profile associated with the access method.
             */
            public string|null $customization_profile_id = null,
            /**
             * URL of the Instant Key for mobile key access methods.
             */
            public string|null $instant_key_url = null,
            /**
             * Indicates whether an existing card credential must be assigned to this access method before it can be issued. Only applies to card-mode access methods on systems that support credential assignment.
             */
            public bool|null $is_assignment_required = null,
            /**
             * Indicates whether encoding with an card encoder is required to issue or reissue the plastic card associated with the access method.
             */
            public bool|null $is_encoding_required = null,
            /**
             * Indicates whether the access method is ready for card assignment. This is true when the access method is in card mode, has not yet been issued, and the system supports credential assignment.
             */
            public bool|null $is_ready_for_assignment = null,
            /**
             * Indicates whether the access method is ready to be encoded. This is true when the credential has been created and the card has not yet been issued.
             */
            public bool|null $is_ready_for_encoding = null,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\AssignCredential\Result {
    /**
     * Errors associated with the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant).
     */
    class Errors
    {
        public static function from_json(mixed $json): Errors|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                error_code: is_string($json->error_code ?? null)
                    ? \Seam\Resources\ActionAttempt\AssignCredential\Result\Errors\ErrorCode::tryFrom(
                        $json->error_code,
                    )
                    : null,
                message: $json->message ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the error.
             */
            public string|null $created_at,
            /**
             * Unique identifier of the type of error. Enables quick recognition and categorization of the issue.
             */
            public \Seam\Resources\ActionAttempt\AssignCredential\Result\Errors\ErrorCode|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Pending mutations for the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant). Indicates operations that are in progress.
     */
    class PendingMutations
    {
        public static function from_json(mixed $json): PendingMutations|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\ActionAttempt\AssignCredential\Result\PendingMutations\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: is_string($json->mutation_code ?? null)
                    ? \Seam\Resources\ActionAttempt\AssignCredential\Result\PendingMutations\MutationCode::tryFrom(
                        $json->mutation_code,
                    )
                    : null,
                to: isset($json->to)
                    ? \Seam\Resources\ActionAttempt\AssignCredential\Result\PendingMutations\To::from_json(
                        $json->to,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * Date and time at which the mutation was created.
             */
            public string|null $created_at,
            /**
             * Previous access time configuration.
             */
            public \Seam\Resources\ActionAttempt\AssignCredential\Result\PendingMutations\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            public string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of updating the access times for this access method.
             */
            public \Seam\Resources\ActionAttempt\AssignCredential\Result\PendingMutations\MutationCode|null $mutation_code,
            /**
             * New access time configuration.
             */
            public \Seam\Resources\ActionAttempt\AssignCredential\Result\PendingMutations\To|null $to,
        ) {}
    }

    /**
     * Warnings associated with the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant).
     */
    class Warnings
    {
        public static function from_json(mixed $json): Warnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: is_string($json->warning_code ?? null)
                    ? \Seam\Resources\ActionAttempt\AssignCredential\Result\Warnings\WarningCode::tryFrom(
                        $json->warning_code,
                    )
                    : null,
                original_access_method_id: $json->original_access_method_id ??
                    null,
            );
        }

        public function __construct(
            /**
             * Date and time at which Seam created the warning.
             */
            public string|null $created_at,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
            /**
             * Unique identifier of the type of warning. Enables quick recognition and categorization of the issue.
             */
            public \Seam\Resources\ActionAttempt\AssignCredential\Result\Warnings\WarningCode|null $warning_code,
            /**
             * ID of the original access method from which this backup access method was split, if applicable.
             */
            public string|null $original_access_method_id = null,
        ) {}
    }

    enum Mode: string
    {
        case CODE = "code";
        case CARD = "card";
        case MOBILE_KEY = "mobile_key";
        case CLOUD_KEY = "cloud_key";
    }
}

namespace Seam\Resources\ActionAttempt\AssignCredential\Result\Errors {
    enum ErrorCode: string
    {
        case FAILED_TO_ISSUE = "failed_to_issue";
    }
}

namespace Seam\Resources\ActionAttempt\AssignCredential\Result\PendingMutations {
    /**
     * Previous access time configuration.
     */
    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                ends_at: $json->ends_at ?? null,
                starts_at: $json->starts_at ?? null,
            );
        }

        public function __construct(
            /**
             * Previous end time for access.
             */
            public string|null $ends_at,
            /**
             * Previous start time for access.
             */
            public string|null $starts_at,
        ) {}
    }

    /**
     * New access time configuration.
     */
    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                ends_at: $json->ends_at ?? null,
                starts_at: $json->starts_at ?? null,
            );
        }

        public function __construct(
            /**
             * New end time for access.
             */
            public string|null $ends_at,
            /**
             * New start time for access.
             */
            public string|null $starts_at,
        ) {}
    }

    enum MutationCode: string
    {
        case PROVISIONING_ACCESS = "provisioning_access";
        case REVOKING_ACCESS = "revoking_access";
        case UPDATING_ACCESS_TIMES = "updating_access_times";
    }
}

namespace Seam\Resources\ActionAttempt\AssignCredential\Result\Warnings {
    enum WarningCode: string
    {
        case BEING_DELETED = "being_deleted";
        case UPDATING_ACCESS_TIMES = "updating_access_times";
        case PULLED_BACKUP_ACCESS_CODE = "pulled_backup_access_code";
        case DELAY_IN_ISSUING = "delay_in_issuing";
    }
}

namespace Seam\Resources\ActionAttempt\CreateAccessCode {
    /**
     * Result of the action. Null while the action attempt is pending or when this value does not apply.
     */
    class Result
    {
        public static function from_json(mixed $json): Result|null
        {
            if (!$json) {
                return null;
            }
            return new self(access_code: $json->access_code ?? null);
        }

        public function __construct(
            /**
             * Created access code.
             *
             * @var array<string, mixed>|\stdClass|null
             */
            public array|\stdClass|null $access_code,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\UpdateAccessCode {
    /**
     * Result of the action. Null while the action attempt is pending or when this value does not apply.
     */
    class Result
    {
        public static function from_json(mixed $json): Result|null
        {
            if (!$json) {
                return null;
            }
            return new self(access_code: $json->access_code ?? null);
        }

        public function __construct(
            /**
             * Updated access code.
             *
             * @var array<string, mixed>|\stdClass|null
             */
            public array|\stdClass|null $access_code = null,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\CreateNoiseThreshold {
    /**
     * Result of the action. Null while the action attempt is pending or when this value does not apply.
     */
    class Result
    {
        public static function from_json(mixed $json): Result|null
        {
            if (!$json) {
                return null;
            }
            return new self(noise_threshold: $json->noise_threshold ?? null);
        }

        public function __construct(
            /**
             * Created noise threshold.
             *
             * @var array<string, mixed>|\stdClass|null
             */
            public array|\stdClass|null $noise_threshold,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\UpdateNoiseThreshold {
    /**
     * Result of the action. Null while the action attempt is pending or when this value does not apply.
     */
    class Result
    {
        public static function from_json(mixed $json): Result|null
        {
            if (!$json) {
                return null;
            }
            return new self(noise_threshold: $json->noise_threshold ?? null);
        }

        public function __construct(
            /**
             * Updated noise threshold.
             *
             * @var array<string, mixed>|\stdClass|null
             */
            public array|\stdClass|null $noise_threshold,
        ) {}
    }
}
