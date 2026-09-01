<?php

namespace Seam\Resources {
    /**
     * Base class for actions whose completion is tracked asynchronously. Known action_type values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class ActionAttempt
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
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
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
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            public string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            public string|null $status,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt {
    /**
     * Locking a door is pending. Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class LockDoor extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): LockDoor|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\LockDoor\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\LockDoor\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\LockDoor\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Unlocking a door is pending. Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class UnlockDoor extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): UnlockDoor|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\UnlockDoor\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\UnlockDoor\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\UnlockDoor\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Reading credential data from the physical encoder is pending. Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class ScanCredential extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): ScanCredential|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\ScanCredential\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\ScanCredential\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\ScanCredential\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Encoding credential data from the physical encoder onto a card is pending. Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class EncodeCredential extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): EncodeCredential|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\EncodeCredential\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\EncodeCredential\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\EncodeCredential\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Scanning a physical card and assigning the credential is pending. Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class ScanToAssignCredential extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(
            mixed $json,
        ): ScanToAssignCredential|null {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\ScanToAssignCredential\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\ScanToAssignCredential\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\ScanToAssignCredential\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Assigning a credential to an access method is pending. Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class AssignCredential extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): AssignCredential|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\AssignCredential\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\AssignCredential\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\AssignCredential\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Resetting a sandbox workspace is pending. Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class ResetSandboxWorkspace extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(
            mixed $json,
        ): ResetSandboxWorkspace|null {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\ResetSandboxWorkspace\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\ResetSandboxWorkspace\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\ResetSandboxWorkspace\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Setting the fan mode is pending. Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class SetFanMode extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): SetFanMode|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\SetFanMode\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\SetFanMode\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\SetFanMode\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Setting the HVAC mode is pending. Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class SetHvacMode extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): SetHvacMode|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\SetHvacMode\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\SetHvacMode\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\SetHvacMode\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Activating a climate preset is pending. Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class ActivateClimatePreset extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(
            mixed $json,
        ): ActivateClimatePreset|null {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\ActivateClimatePreset\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\ActivateClimatePreset\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\ActivateClimatePreset\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Simulating a keypad code entry is pending. Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class SimulateKeypadCodeEntry extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(
            mixed $json,
        ): SimulateKeypadCodeEntry|null {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\SimulateKeypadCodeEntry\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\SimulateKeypadCodeEntry\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\SimulateKeypadCodeEntry\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Simulating a manual lock action using a keypad is pending. Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class SimulateManualLockViaKeypad extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(
            mixed $json,
        ): SimulateManualLockViaKeypad|null {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\SimulateManualLockViaKeypad\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\SimulateManualLockViaKeypad\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\SimulateManualLockViaKeypad\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Pushing thermostat weekly programs is pending. Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class PushThermostatPrograms extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(
            mixed $json,
        ): PushThermostatPrograms|null {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\PushThermostatPrograms\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\PushThermostatPrograms\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\PushThermostatPrograms\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Configuring the auto-lock is pending. Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class ConfigureAutoLock extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): ConfigureAutoLock|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\ConfigureAutoLock\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\ConfigureAutoLock\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\ConfigureAutoLock\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class SyncAccessCodes extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): SyncAccessCodes|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\SyncAccessCodes\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\SyncAccessCodes\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\SyncAccessCodes\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class CreateAccessCode extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): CreateAccessCode|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\CreateAccessCode\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\CreateAccessCode\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\CreateAccessCode\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class DeleteAccessCode extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): DeleteAccessCode|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\DeleteAccessCode\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\DeleteAccessCode\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\DeleteAccessCode\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class UpdateAccessCode extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): UpdateAccessCode|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\UpdateAccessCode\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\UpdateAccessCode\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\UpdateAccessCode\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class CreateNoiseThreshold extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): CreateNoiseThreshold|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\CreateNoiseThreshold\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\CreateNoiseThreshold\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\CreateNoiseThreshold\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class DeleteNoiseThreshold extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): DeleteNoiseThreshold|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\DeleteNoiseThreshold\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\DeleteNoiseThreshold\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\DeleteNoiseThreshold\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Known status values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class UpdateNoiseThreshold extends \Seam\Resources\ActionAttempt
    {
        public static function from_json(mixed $json): UpdateNoiseThreshold|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->status ?? null)
                ? \Seam\Resources\ActionAttempt\Status::tryFrom($json->status)
                : null;

            return match ($discriminant) {
                \Seam\Resources\ActionAttempt\Status::SUCCESS
                    => \Seam\Resources\ActionAttempt\UpdateNoiseThreshold\Success::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::PENDING
                    => \Seam\Resources\ActionAttempt\UpdateNoiseThreshold\Pending::from_json(
                    $json,
                ),
                \Seam\Resources\ActionAttempt\Status::ERROR
                    => \Seam\Resources\ActionAttempt\UpdateNoiseThreshold\Error::from_json(
                    $json,
                ),
                default => new self(
                    action_attempt_id: $json->action_attempt_id ?? null,
                    action_type: $json->action_type ?? null,
                    status: $json->status ?? null,
                ),
            };
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
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
     * Locking a door is pending.
     */
    final class Success extends \Seam\Resources\ActionAttempt\LockDoor
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\LockDoor\Success\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public \Seam\Resources\ActionAttempt\LockDoor\Success\Result|null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Locking a door is pending.
     */
    final class Pending extends \Seam\Resources\ActionAttempt\LockDoor
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Locking a door is pending.
     */
    final class Error extends \Seam\Resources\ActionAttempt\LockDoor
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\LockDoor\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\LockDoor\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\LockDoor\Success {
    /**
     * Result of the action.
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

namespace Seam\Resources\ActionAttempt\LockDoor\Error {
    /**
     * Error associated with the action.
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
}

namespace Seam\Resources\ActionAttempt\UnlockDoor {
    /**
     * Unlocking a door is pending.
     */
    final class Success extends \Seam\Resources\ActionAttempt\UnlockDoor
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\UnlockDoor\Success\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public \Seam\Resources\ActionAttempt\UnlockDoor\Success\Result|null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Unlocking a door is pending.
     */
    final class Pending extends \Seam\Resources\ActionAttempt\UnlockDoor
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Unlocking a door is pending.
     */
    final class Error extends \Seam\Resources\ActionAttempt\UnlockDoor
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\UnlockDoor\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\UnlockDoor\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\UnlockDoor\Success {
    /**
     * Result of the action.
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

namespace Seam\Resources\ActionAttempt\UnlockDoor\Error {
    /**
     * Error associated with the action.
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
}

namespace Seam\Resources\ActionAttempt\ScanCredential {
    /**
     * Reading credential data from the physical encoder is pending.
     */
    final class Success extends \Seam\Resources\ActionAttempt\ScanCredential
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Success\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            public null $error,
            /**
             * Result of scanning a card. If the attempt was successful, includes a snapshot of credential data read from the physical encoder, the corresponding data stored on Seam and the access system, and any associated warnings.
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Success\Result|null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Reading credential data from the physical encoder is pending.
     */
    final class Pending extends \Seam\Resources\ActionAttempt\ScanCredential
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            public null $error,
            /**
             * Result of scanning a card. If the attempt was successful, includes a snapshot of credential data read from the physical encoder, the corresponding data stored on Seam and the access system, and any associated warnings.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Reading credential data from the physical encoder is pending.
     */
    final class Error extends \Seam\Resources\ActionAttempt\ScanCredential
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            public \Seam\Resources\ActionAttempt\ScanCredential\Error\Error|null $error,
            /**
             * Result of scanning a card. If the attempt was successful, includes a snapshot of credential data read from the physical encoder, the corresponding data stored on Seam and the access system, and any associated warnings.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\ScanCredential\Success {
    /**
     * Result of scanning a card. If the attempt was successful, includes a snapshot of credential data read from the physical encoder, the corresponding data stored on Seam and the access system, and any associated warnings.
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
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnEncoder::from_json(
                        $json->acs_credential_on_encoder,
                    )
                    : null,
                acs_credential_on_seam: isset($json->acs_credential_on_seam)
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam::from_json(
                        $json->acs_credential_on_seam,
                    )
                    : null,
                warnings: \Seam\Parse::to_list(
                    $json->warnings ?? null,
                    fn(
                        $w,
                    ) => \Seam\Resources\ActionAttempt\ScanCredential\Success\Result\Warnings::from_json(
                        $w,
                    ),
                ),
            );
        }

        public function __construct(
            /**
             * Snapshot of credential data read from the physical encoder.
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnEncoder|null $acs_credential_on_encoder,
            /**
             * Corresponding credential data as stored on Seam and the access system.
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam|null $acs_credential_on_seam,
            /**
             * Warnings related to scanning the credential, such as mismatches between the credential data currently encoded on the card and the corresponding data stored on Seam and the access system.
             *
             * @var list<\Seam\Resources\ActionAttempt\ScanCredential\Success\Result\Warnings>
             */
            public array $warnings,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\ScanCredential\Success\Result {
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
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnEncoder\VisionlineMetadata::from_json(
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
            public \Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnEncoder\VisionlineMetadata|null $visionline_metadata = null,
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
                access_method: $json->access_method ?? null,
                acs_credential_id: $json->acs_credential_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                errors: \Seam\Parse::to_list(
                    $json->errors ?? null,
                    fn(
                        $e,
                    ) => \Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam\Errors::from_json(
                        $e,
                    ),
                ),
                is_managed: $json->is_managed ?? null,
                warnings: \Seam\Parse::to_list(
                    $json->warnings ?? null,
                    fn(
                        $w,
                    ) => \Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam\Warnings::from_json(
                        $w,
                    ),
                ),
                workspace_id: $json->workspace_id ?? null,
                acs_credential_pool_id: $json->acs_credential_pool_id ?? null,
                acs_user_id: $json->acs_user_id ?? null,
                akiles_metadata: isset($json->akiles_metadata)
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam\AkilesMetadata::from_json(
                        $json->akiles_metadata,
                    )
                    : null,
                assa_abloy_vostio_metadata: isset(
                    $json->assa_abloy_vostio_metadata,
                )
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam\AssaAbloyVostioMetadata::from_json(
                        $json->assa_abloy_vostio_metadata,
                    )
                    : null,
                card_number: $json->card_number ?? null,
                code: $json->code ?? null,
                ends_at: $json->ends_at ?? null,
                external_type: $json->external_type ?? null,
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
                    ? \Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam\VisionlineMetadata::from_json(
                        $json->visionline_metadata,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * Access method for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials). Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam\AccessMethod>|string|null
             */
            public string|null $access_method,
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
             * @var list<\Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam\Errors>
             */
            public array $errors,
            public bool|null $is_managed,
            /**
             * Warnings associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             *
             * @var list<\Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam\Warnings>
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
            public \Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam\AkilesMetadata|null $akiles_metadata = null,
            /**
             * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public \Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam\AssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata = null,
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
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam\ExternalType>|string|null
             */
            public string|null $external_type = null,
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
            public \Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam\VisionlineMetadata|null $visionline_metadata = null,
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
                warning_code: $json->warning_code ?? null,
                warning_message: $json->warning_message ?? null,
            );
        }

        public function __construct(
            /**
             * Indicates a warning related to scanning a credential.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ScanCredential\Success\Result\Warnings\WarningCode>|string|null
             */
            public string|null $warning_code,
            /**
             * Detailed description of the warning. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $warning_message,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnEncoder {
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
                card_format: $json->card_format ?? null,
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
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnEncoder\VisionlineMetadata\CardFormat>|string|null
             */
            public string|null $card_format = null,
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

namespace Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnEncoder\VisionlineMetadata {
    enum CardFormat: string
    {
        case TL_CODE = "TLCode";
        case RFID48 = "rfid48";
    }
}

namespace Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam {
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
                card_function_type: $json->card_function_type ?? null,
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
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam\VisionlineMetadata\CardFunctionType>|string|null
             */
            public string|null $card_function_type = null,
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
                warning_code: $json->warning_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam\Warnings\WarningCode>|string|null
             */
            public string|null $warning_code,
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

namespace Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam\VisionlineMetadata {
    enum CardFunctionType: string
    {
        case GUEST = "guest";
        case STAFF = "staff";
    }
}

namespace Seam\Resources\ActionAttempt\ScanCredential\Success\Result\AcsCredentialOnSeam\Warnings {
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

namespace Seam\Resources\ActionAttempt\ScanCredential\Success\Result\Warnings {
    enum WarningCode: string
    {
        case ACS_CREDENTIAL_ON_ENCODER_OUT_OF_SYNC = "acs_credential_on_encoder_out_of_sync";
        case ACS_CREDENTIAL_ON_SEAM_NOT_FOUND = "acs_credential_on_seam_not_found";
    }
}

namespace Seam\Resources\ActionAttempt\ScanCredential\Error {
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
             * Error type to indicate that the Seam Bridge is disconnected or cannot reach the access control system.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ScanCredential\Error\Error\Type>|string|null
             */
            public string|null $type,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\ScanCredential\Error\Error {
    enum Type: string
    {
        case UNCATEGORIZED_ERROR = "uncategorized_error";
        case ACTION_ATTEMPT_EXPIRED = "action_attempt_expired";
        case NO_CREDENTIAL_ON_ENCODER = "no_credential_on_encoder";
        case ENCODER_NOT_ONLINE = "encoder_not_online";
        case ENCODER_COMMUNICATION_TIMEOUT = "encoder_communication_timeout";
        case BRIDGE_DISCONNECTED = "bridge_disconnected";
    }
}

namespace Seam\Resources\ActionAttempt\EncodeCredential {
    /**
     * Encoding credential data from the physical encoder onto a card is pending.
     */
    final class Success extends \Seam\Resources\ActionAttempt\EncodeCredential
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\EncodeCredential\Success\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            public null $error,
            /**
             * Result of an encoding attempt. If the attempt was successful, includes the credential data that was encoded onto the card.
             */
            public \Seam\Resources\ActionAttempt\EncodeCredential\Success\Result|null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Encoding credential data from the physical encoder onto a card is pending.
     */
    final class Pending extends \Seam\Resources\ActionAttempt\EncodeCredential
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            public null $error,
            /**
             * Result of an encoding attempt. If the attempt was successful, includes the credential data that was encoded onto the card.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Encoding credential data from the physical encoder onto a card is pending.
     */
    final class Error extends \Seam\Resources\ActionAttempt\EncodeCredential
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\EncodeCredential\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            public \Seam\Resources\ActionAttempt\EncodeCredential\Error\Error|null $error,
            /**
             * Result of an encoding attempt. If the attempt was successful, includes the credential data that was encoded onto the card.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\EncodeCredential\Success {
    /**
     * Result of an encoding attempt. If the attempt was successful, includes the credential data that was encoded onto the card.
     */
    class Result
    {
        public static function from_json(mixed $json): Result|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_method: $json->access_method ?? null,
                acs_credential_id: $json->acs_credential_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                errors: \Seam\Parse::to_list(
                    $json->errors ?? null,
                    fn(
                        $e,
                    ) => \Seam\Resources\ActionAttempt\EncodeCredential\Success\Result\Errors::from_json(
                        $e,
                    ),
                ),
                is_managed: $json->is_managed ?? null,
                warnings: \Seam\Parse::to_list(
                    $json->warnings ?? null,
                    fn(
                        $w,
                    ) => \Seam\Resources\ActionAttempt\EncodeCredential\Success\Result\Warnings::from_json(
                        $w,
                    ),
                ),
                workspace_id: $json->workspace_id ?? null,
                acs_credential_pool_id: $json->acs_credential_pool_id ?? null,
                acs_user_id: $json->acs_user_id ?? null,
                akiles_metadata: isset($json->akiles_metadata)
                    ? \Seam\Resources\ActionAttempt\EncodeCredential\Success\Result\AkilesMetadata::from_json(
                        $json->akiles_metadata,
                    )
                    : null,
                assa_abloy_vostio_metadata: isset(
                    $json->assa_abloy_vostio_metadata,
                )
                    ? \Seam\Resources\ActionAttempt\EncodeCredential\Success\Result\AssaAbloyVostioMetadata::from_json(
                        $json->assa_abloy_vostio_metadata,
                    )
                    : null,
                card_number: $json->card_number ?? null,
                code: $json->code ?? null,
                ends_at: $json->ends_at ?? null,
                external_type: $json->external_type ?? null,
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
                    ? \Seam\Resources\ActionAttempt\EncodeCredential\Success\Result\VisionlineMetadata::from_json(
                        $json->visionline_metadata,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * Access method for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials). Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\EncodeCredential\Success\Result\AccessMethod>|string|null
             */
            public string|null $access_method,
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
             * @var list<\Seam\Resources\ActionAttempt\EncodeCredential\Success\Result\Errors>
             */
            public array $errors,
            public bool|null $is_managed,
            /**
             * Warnings associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             *
             * @var list<\Seam\Resources\ActionAttempt\EncodeCredential\Success\Result\Warnings>
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
            public \Seam\Resources\ActionAttempt\EncodeCredential\Success\Result\AkilesMetadata|null $akiles_metadata = null,
            /**
             * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public \Seam\Resources\ActionAttempt\EncodeCredential\Success\Result\AssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata = null,
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
             *
             * @var value-of<\Seam\Resources\ActionAttempt\EncodeCredential\Success\Result\ExternalType>|string|null
             */
            public string|null $external_type = null,
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
            public \Seam\Resources\ActionAttempt\EncodeCredential\Success\Result\VisionlineMetadata|null $visionline_metadata = null,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\EncodeCredential\Success\Result {
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
                card_function_type: $json->card_function_type ?? null,
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
             *
             * @var value-of<\Seam\Resources\ActionAttempt\EncodeCredential\Success\Result\VisionlineMetadata\CardFunctionType>|string|null
             */
            public string|null $card_function_type = null,
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
                warning_code: $json->warning_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\ActionAttempt\EncodeCredential\Success\Result\Warnings\WarningCode>|string|null
             */
            public string|null $warning_code,
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

namespace Seam\Resources\ActionAttempt\EncodeCredential\Success\Result\VisionlineMetadata {
    enum CardFunctionType: string
    {
        case GUEST = "guest";
        case STAFF = "staff";
    }
}

namespace Seam\Resources\ActionAttempt\EncodeCredential\Success\Result\Warnings {
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

namespace Seam\Resources\ActionAttempt\EncodeCredential\Error {
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
             * Error type to indicate that the credential was deleted and can no longer be encoded.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\EncodeCredential\Error\Error\Type>|string|null
             */
            public string|null $type,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\EncodeCredential\Error\Error {
    enum Type: string
    {
        case UNCATEGORIZED_ERROR = "uncategorized_error";
        case ACTION_ATTEMPT_EXPIRED = "action_attempt_expired";
        case NO_CREDENTIAL_ON_ENCODER = "no_credential_on_encoder";
        case INCOMPATIBLE_CARD_FORMAT = "incompatible_card_format";
        case CREDENTIAL_CANNOT_BE_REISSUED = "credential_cannot_be_reissued";
        case ENCODER_NOT_ONLINE = "encoder_not_online";
        case ENCODER_COMMUNICATION_TIMEOUT = "encoder_communication_timeout";
        case BRIDGE_DISCONNECTED = "bridge_disconnected";
        case ENCODING_INTERRUPTED = "encoding_interrupted";
        case CREDENTIAL_DELETED = "credential_deleted";
    }
}

namespace Seam\Resources\ActionAttempt\ScanToAssignCredential {
    /**
     * Scanning a physical card and assigning the credential is pending.
     */
    final class Success extends
        \Seam\Resources\ActionAttempt\ScanToAssignCredential
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            public null $error,
            /**
             * Result of a scan to assign attempt. If the attempt was successful, includes the credential data that was scanned and assigned.
             */
            public \Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result|null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Scanning a physical card and assigning the credential is pending.
     */
    final class Pending extends
        \Seam\Resources\ActionAttempt\ScanToAssignCredential
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            public null $error,
            /**
             * Result of a scan to assign attempt. If the attempt was successful, includes the credential data that was scanned and assigned.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Scanning a physical card and assigning the credential is pending.
     */
    final class Error extends
        \Seam\Resources\ActionAttempt\ScanToAssignCredential
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\ScanToAssignCredential\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            public \Seam\Resources\ActionAttempt\ScanToAssignCredential\Error\Error|null $error,
            /**
             * Result of a scan to assign attempt. If the attempt was successful, includes the credential data that was scanned and assigned.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\ScanToAssignCredential\Success {
    /**
     * Result of a scan to assign attempt. If the attempt was successful, includes the credential data that was scanned and assigned.
     */
    class Result
    {
        public static function from_json(mixed $json): Result|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_method: $json->access_method ?? null,
                acs_credential_id: $json->acs_credential_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                display_name: $json->display_name ?? null,
                errors: \Seam\Parse::to_list(
                    $json->errors ?? null,
                    fn(
                        $e,
                    ) => \Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result\Errors::from_json(
                        $e,
                    ),
                ),
                is_managed: $json->is_managed ?? null,
                warnings: \Seam\Parse::to_list(
                    $json->warnings ?? null,
                    fn(
                        $w,
                    ) => \Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result\Warnings::from_json(
                        $w,
                    ),
                ),
                workspace_id: $json->workspace_id ?? null,
                acs_credential_pool_id: $json->acs_credential_pool_id ?? null,
                acs_user_id: $json->acs_user_id ?? null,
                akiles_metadata: isset($json->akiles_metadata)
                    ? \Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result\AkilesMetadata::from_json(
                        $json->akiles_metadata,
                    )
                    : null,
                assa_abloy_vostio_metadata: isset(
                    $json->assa_abloy_vostio_metadata,
                )
                    ? \Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result\AssaAbloyVostioMetadata::from_json(
                        $json->assa_abloy_vostio_metadata,
                    )
                    : null,
                card_number: $json->card_number ?? null,
                code: $json->code ?? null,
                ends_at: $json->ends_at ?? null,
                external_type: $json->external_type ?? null,
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
                    ? \Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result\VisionlineMetadata::from_json(
                        $json->visionline_metadata,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * Access method for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials). Supported values: `code`, `card`, `mobile_key`, `cloud_key`.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result\AccessMethod>|string|null
             */
            public string|null $access_method,
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
             * @var list<\Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result\Errors>
             */
            public array $errors,
            /**
             * Indicates whether Seam manages the credential.
             */
            public true|null $is_managed,
            /**
             * Warnings associated with the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             *
             * @var list<\Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result\Warnings>
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
            public \Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result\AkilesMetadata|null $akiles_metadata = null,
            /**
             * Vostio-specific metadata for the [credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials).
             */
            public \Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result\AssaAbloyVostioMetadata|null $assa_abloy_vostio_metadata = null,
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
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result\ExternalType>|string|null
             */
            public string|null $external_type = null,
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
            public \Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result\VisionlineMetadata|null $visionline_metadata = null,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result {
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
                card_function_type: $json->card_function_type ?? null,
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
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result\VisionlineMetadata\CardFunctionType>|string|null
             */
            public string|null $card_function_type = null,
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
                warning_code: $json->warning_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result\Warnings\WarningCode>|string|null
             */
            public string|null $warning_code,
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

namespace Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result\VisionlineMetadata {
    enum CardFunctionType: string
    {
        case GUEST = "guest";
        case STAFF = "staff";
    }
}

namespace Seam\Resources\ActionAttempt\ScanToAssignCredential\Success\Result\Warnings {
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

namespace Seam\Resources\ActionAttempt\ScanToAssignCredential\Error {
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
             * Error type to indicate that there is no credential on the encoder.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ScanToAssignCredential\Error\Error\Type>|string|null
             */
            public string|null $type,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\ScanToAssignCredential\Error\Error {
    enum Type: string
    {
        case UNCATEGORIZED_ERROR = "uncategorized_error";
        case ACTION_ATTEMPT_EXPIRED = "action_attempt_expired";
        case NO_CREDENTIAL_ON_ENCODER = "no_credential_on_encoder";
    }
}

namespace Seam\Resources\ActionAttempt\AssignCredential {
    /**
     * Assigning a credential to an access method is pending.
     */
    final class Success extends \Seam\Resources\ActionAttempt\AssignCredential
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\AssignCredential\Success\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            public null $error,
            /**
             * Result of assigning a credential. If successful, includes the updated access method with the assigned credential.
             */
            public \Seam\Resources\ActionAttempt\AssignCredential\Success\Result|null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Assigning a credential to an access method is pending.
     */
    final class Pending extends \Seam\Resources\ActionAttempt\AssignCredential
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            public null $error,
            /**
             * Result of assigning a credential. If successful, includes the updated access method with the assigned credential.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Assigning a credential to an access method is pending.
     */
    final class Error extends \Seam\Resources\ActionAttempt\AssignCredential
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\AssignCredential\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            public \Seam\Resources\ActionAttempt\AssignCredential\Error\Error|null $error,
            /**
             * Result of assigning a credential. If successful, includes the updated access method with the assigned credential.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\AssignCredential\Success {
    /**
     * Result of assigning a credential. If successful, includes the updated access method with the assigned credential.
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
                display_status: $json->display_status ?? null,
                errors: \Seam\Parse::to_list(
                    $json->errors ?? null,
                    fn(
                        $e,
                    ) => \Seam\Resources\ActionAttempt\AssignCredential\Success\Result\Errors::from_json(
                        $e,
                    ),
                ),
                is_issued: $json->is_issued ?? null,
                issued_at: $json->issued_at ?? null,
                mode: $json->mode ?? null,
                pending_mutations: \Seam\Parse::to_list(
                    $json->pending_mutations ?? null,
                    fn(
                        $p,
                    ) => \Seam\Resources\ActionAttempt\AssignCredential\Success\Result\PendingMutations::from_json(
                        $p,
                    ),
                ),
                warnings: \Seam\Parse::to_list(
                    $json->warnings ?? null,
                    fn(
                        $w,
                    ) => \Seam\Resources\ActionAttempt\AssignCredential\Success\Result\Warnings::from_json(
                        $w,
                    ),
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
             * Human-readable sentence describing where the access method sits in its relationship with the device or access system, for example `Awaiting encoding`. For display only. The wording is not stable and is not an enumeration — it may change at any time, so never compare against or branch on it. To make decisions, read `is_issued`, `errors`, and `pending_mutations`.
             */
            public string|null $display_status,
            /**
             * Errors associated with the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant).
             *
             * @var list<\Seam\Resources\ActionAttempt\AssignCredential\Success\Result\Errors>
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
             *
             * @var value-of<\Seam\Resources\ActionAttempt\AssignCredential\Success\Result\Mode>|string|null
             */
            public string|null $mode,
            /**
             * Pending mutations for the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant). Indicates operations that are in progress.
             *
             * @var list<\Seam\Resources\ActionAttempt\AssignCredential\Success\Result\PendingMutations>
             */
            public array $pending_mutations,
            /**
             * Warnings associated with the [access method](https://docs.seam.co/use-cases/granting-access/creating-an-access-grant).
             *
             * @var list<\Seam\Resources\ActionAttempt\AssignCredential\Success\Result\Warnings>
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

namespace Seam\Resources\ActionAttempt\AssignCredential\Success\Result {
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
                error_code: $json->error_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\ActionAttempt\AssignCredential\Success\Result\Errors\ErrorCode>|string|null
             */
            public string|null $error_code,
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
                    ? \Seam\Resources\ActionAttempt\AssignCredential\Success\Result\PendingMutations\From::from_json(
                        $json->from,
                    )
                    : null,
                message: $json->message ?? null,
                mutation_code: $json->mutation_code ?? null,
                to: isset($json->to)
                    ? \Seam\Resources\ActionAttempt\AssignCredential\Success\Result\PendingMutations\To::from_json(
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
            public \Seam\Resources\ActionAttempt\AssignCredential\Success\Result\PendingMutations\From|null $from,
            /**
             * Detailed description of the mutation.
             */
            public string|null $message,
            /**
             * Mutation code to indicate that Seam is in the process of updating the access times for this access method.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\AssignCredential\Success\Result\PendingMutations\MutationCode>|string|null
             */
            public string|null $mutation_code,
            /**
             * New access time configuration.
             */
            public \Seam\Resources\ActionAttempt\AssignCredential\Success\Result\PendingMutations\To|null $to,
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
                warning_code: $json->warning_code ?? null,
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
             *
             * @var value-of<\Seam\Resources\ActionAttempt\AssignCredential\Success\Result\Warnings\WarningCode>|string|null
             */
            public string|null $warning_code,
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

namespace Seam\Resources\ActionAttempt\AssignCredential\Success\Result\Errors {
    enum ErrorCode: string
    {
        case FAILED_TO_ISSUE = "failed_to_issue";
    }
}

namespace Seam\Resources\ActionAttempt\AssignCredential\Success\Result\PendingMutations {
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

namespace Seam\Resources\ActionAttempt\AssignCredential\Success\Result\Warnings {
    enum WarningCode: string
    {
        case BEING_DELETED = "being_deleted";
        case UPDATING_ACCESS_TIMES = "updating_access_times";
        case PULLED_BACKUP_ACCESS_CODE = "pulled_backup_access_code";
        case DELAY_IN_ISSUING = "delay_in_issuing";
    }
}

namespace Seam\Resources\ActionAttempt\AssignCredential\Error {
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
             * Error type to indicate that no matching credential was found.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\AssignCredential\Error\Error\Type>|string|null
             */
            public string|null $type,
        ) {}
    }
}

namespace Seam\Resources\ActionAttempt\AssignCredential\Error\Error {
    enum Type: string
    {
        case UNCATEGORIZED_ERROR = "uncategorized_error";
        case ACTION_ATTEMPT_EXPIRED = "action_attempt_expired";
        case CREDENTIAL_NOT_FOUND = "credential_not_found";
    }
}

namespace Seam\Resources\ActionAttempt\ResetSandboxWorkspace {
    /**
     * Resetting a sandbox workspace is pending.
     */
    final class Success extends
        \Seam\Resources\ActionAttempt\ResetSandboxWorkspace
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: $json->result ?? null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public mixed $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Resetting a sandbox workspace is pending.
     */
    final class Pending extends
        \Seam\Resources\ActionAttempt\ResetSandboxWorkspace
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Resetting a sandbox workspace is pending.
     */
    final class Error extends
        \Seam\Resources\ActionAttempt\ResetSandboxWorkspace
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\ResetSandboxWorkspace\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\ResetSandboxWorkspace\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\ResetSandboxWorkspace\Error {
    /**
     * Error associated with the action.
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
}

namespace Seam\Resources\ActionAttempt\SetFanMode {
    /**
     * Setting the fan mode is pending.
     */
    final class Success extends \Seam\Resources\ActionAttempt\SetFanMode
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: $json->result ?? null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public mixed $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Setting the fan mode is pending.
     */
    final class Pending extends \Seam\Resources\ActionAttempt\SetFanMode
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Setting the fan mode is pending.
     */
    final class Error extends \Seam\Resources\ActionAttempt\SetFanMode
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\SetFanMode\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\SetFanMode\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\SetFanMode\Error {
    /**
     * Error associated with the action.
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
}

namespace Seam\Resources\ActionAttempt\SetHvacMode {
    /**
     * Setting the HVAC mode is pending.
     */
    final class Success extends \Seam\Resources\ActionAttempt\SetHvacMode
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: $json->result ?? null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public mixed $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Setting the HVAC mode is pending.
     */
    final class Pending extends \Seam\Resources\ActionAttempt\SetHvacMode
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Setting the HVAC mode is pending.
     */
    final class Error extends \Seam\Resources\ActionAttempt\SetHvacMode
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\SetHvacMode\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\SetHvacMode\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\SetHvacMode\Error {
    /**
     * Error associated with the action.
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
}

namespace Seam\Resources\ActionAttempt\ActivateClimatePreset {
    /**
     * Activating a climate preset is pending.
     */
    final class Success extends
        \Seam\Resources\ActionAttempt\ActivateClimatePreset
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: $json->result ?? null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public mixed $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Activating a climate preset is pending.
     */
    final class Pending extends
        \Seam\Resources\ActionAttempt\ActivateClimatePreset
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Activating a climate preset is pending.
     */
    final class Error extends
        \Seam\Resources\ActionAttempt\ActivateClimatePreset
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\ActivateClimatePreset\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\ActivateClimatePreset\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\ActivateClimatePreset\Error {
    /**
     * Error associated with the action.
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
}

namespace Seam\Resources\ActionAttempt\SimulateKeypadCodeEntry {
    /**
     * Simulating a keypad code entry is pending.
     */
    final class Success extends
        \Seam\Resources\ActionAttempt\SimulateKeypadCodeEntry
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: $json->result ?? null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public mixed $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Simulating a keypad code entry is pending.
     */
    final class Pending extends
        \Seam\Resources\ActionAttempt\SimulateKeypadCodeEntry
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Simulating a keypad code entry is pending.
     */
    final class Error extends
        \Seam\Resources\ActionAttempt\SimulateKeypadCodeEntry
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\SimulateKeypadCodeEntry\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\SimulateKeypadCodeEntry\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\SimulateKeypadCodeEntry\Error {
    /**
     * Error associated with the action.
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
}

namespace Seam\Resources\ActionAttempt\SimulateManualLockViaKeypad {
    /**
     * Simulating a manual lock action using a keypad is pending.
     */
    final class Success extends
        \Seam\Resources\ActionAttempt\SimulateManualLockViaKeypad
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: $json->result ?? null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public mixed $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Simulating a manual lock action using a keypad is pending.
     */
    final class Pending extends
        \Seam\Resources\ActionAttempt\SimulateManualLockViaKeypad
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Simulating a manual lock action using a keypad is pending.
     */
    final class Error extends
        \Seam\Resources\ActionAttempt\SimulateManualLockViaKeypad
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\SimulateManualLockViaKeypad\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\SimulateManualLockViaKeypad\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\SimulateManualLockViaKeypad\Error {
    /**
     * Error associated with the action.
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
}

namespace Seam\Resources\ActionAttempt\PushThermostatPrograms {
    /**
     * Pushing thermostat weekly programs is pending.
     */
    final class Success extends
        \Seam\Resources\ActionAttempt\PushThermostatPrograms
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: $json->result ?? null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public mixed $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Pushing thermostat weekly programs is pending.
     */
    final class Pending extends
        \Seam\Resources\ActionAttempt\PushThermostatPrograms
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Pushing thermostat weekly programs is pending.
     */
    final class Error extends
        \Seam\Resources\ActionAttempt\PushThermostatPrograms
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\PushThermostatPrograms\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\PushThermostatPrograms\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\PushThermostatPrograms\Error {
    /**
     * Error associated with the action.
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
}

namespace Seam\Resources\ActionAttempt\ConfigureAutoLock {
    /**
     * Configuring the auto-lock is pending.
     */
    final class Success extends \Seam\Resources\ActionAttempt\ConfigureAutoLock
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: $json->result ?? null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public mixed $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Configuring the auto-lock is pending.
     */
    final class Pending extends \Seam\Resources\ActionAttempt\ConfigureAutoLock
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    /**
     * Configuring the auto-lock is pending.
     */
    final class Error extends \Seam\Resources\ActionAttempt\ConfigureAutoLock
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\ConfigureAutoLock\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\ConfigureAutoLock\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\ConfigureAutoLock\Error {
    /**
     * Error associated with the action.
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
}

namespace Seam\Resources\ActionAttempt\SyncAccessCodes {
    final class Success extends \Seam\Resources\ActionAttempt\SyncAccessCodes
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: $json->result ?? null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public mixed $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    final class Pending extends \Seam\Resources\ActionAttempt\SyncAccessCodes
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    final class Error extends \Seam\Resources\ActionAttempt\SyncAccessCodes
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\SyncAccessCodes\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\SyncAccessCodes\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\SyncAccessCodes\Error {
    /**
     * Error associated with the action.
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
}

namespace Seam\Resources\ActionAttempt\CreateAccessCode {
    final class Success extends \Seam\Resources\ActionAttempt\CreateAccessCode
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\CreateAccessCode\Success\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public \Seam\Resources\ActionAttempt\CreateAccessCode\Success\Result|null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    final class Pending extends \Seam\Resources\ActionAttempt\CreateAccessCode
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    final class Error extends \Seam\Resources\ActionAttempt\CreateAccessCode
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\CreateAccessCode\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\CreateAccessCode\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\CreateAccessCode\Success {
    /**
     * Result of the action.
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

namespace Seam\Resources\ActionAttempt\CreateAccessCode\Error {
    /**
     * Error associated with the action.
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
}

namespace Seam\Resources\ActionAttempt\DeleteAccessCode {
    final class Success extends \Seam\Resources\ActionAttempt\DeleteAccessCode
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: $json->result ?? null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public mixed $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    final class Pending extends \Seam\Resources\ActionAttempt\DeleteAccessCode
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    final class Error extends \Seam\Resources\ActionAttempt\DeleteAccessCode
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\DeleteAccessCode\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\DeleteAccessCode\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\DeleteAccessCode\Error {
    /**
     * Error associated with the action.
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
}

namespace Seam\Resources\ActionAttempt\UpdateAccessCode {
    final class Success extends \Seam\Resources\ActionAttempt\UpdateAccessCode
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\UpdateAccessCode\Success\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public \Seam\Resources\ActionAttempt\UpdateAccessCode\Success\Result|null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    final class Pending extends \Seam\Resources\ActionAttempt\UpdateAccessCode
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    final class Error extends \Seam\Resources\ActionAttempt\UpdateAccessCode
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\UpdateAccessCode\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\UpdateAccessCode\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\UpdateAccessCode\Success {
    /**
     * Result of the action.
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

namespace Seam\Resources\ActionAttempt\UpdateAccessCode\Error {
    /**
     * Error associated with the action.
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
}

namespace Seam\Resources\ActionAttempt\CreateNoiseThreshold {
    final class Success extends
        \Seam\Resources\ActionAttempt\CreateNoiseThreshold
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\CreateNoiseThreshold\Success\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public \Seam\Resources\ActionAttempt\CreateNoiseThreshold\Success\Result|null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    final class Pending extends
        \Seam\Resources\ActionAttempt\CreateNoiseThreshold
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    final class Error extends \Seam\Resources\ActionAttempt\CreateNoiseThreshold
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\CreateNoiseThreshold\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\CreateNoiseThreshold\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\CreateNoiseThreshold\Success {
    /**
     * Result of the action.
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

namespace Seam\Resources\ActionAttempt\CreateNoiseThreshold\Error {
    /**
     * Error associated with the action.
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
}

namespace Seam\Resources\ActionAttempt\DeleteNoiseThreshold {
    final class Success extends
        \Seam\Resources\ActionAttempt\DeleteNoiseThreshold
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: $json->result ?? null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public mixed $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    final class Pending extends
        \Seam\Resources\ActionAttempt\DeleteNoiseThreshold
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    final class Error extends \Seam\Resources\ActionAttempt\DeleteNoiseThreshold
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\DeleteNoiseThreshold\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\DeleteNoiseThreshold\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\DeleteNoiseThreshold\Error {
    /**
     * Error associated with the action.
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
}

namespace Seam\Resources\ActionAttempt\UpdateNoiseThreshold {
    final class Success extends
        \Seam\Resources\ActionAttempt\UpdateNoiseThreshold
    {
        public static function from_json(mixed $json): Success|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: isset($json->result)
                    ? \Seam\Resources\ActionAttempt\UpdateNoiseThreshold\Success\Result::from_json(
                        $json->result,
                    )
                    : null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public \Seam\Resources\ActionAttempt\UpdateNoiseThreshold\Success\Result|null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    final class Pending extends
        \Seam\Resources\ActionAttempt\UpdateNoiseThreshold
    {
        public static function from_json(mixed $json): Pending|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }

    final class Error extends \Seam\Resources\ActionAttempt\UpdateNoiseThreshold
    {
        public static function from_json(mixed $json): Error|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                error: isset($json->error)
                    ? \Seam\Resources\ActionAttempt\UpdateNoiseThreshold\Error\Error::from_json(
                        $json->error,
                    )
                    : null,
                result: null,
                status: $json->status ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the action attempt.
             */
            string|null $action_attempt_id,
            /**
             * Action attempt to track the status of locking a door.
             *
             * @var value-of<\Seam\Resources\ActionAttempt\ActionType>|string|null
             */
            string|null $action_type,
            /**
             * Error associated with the action.
             */
            public \Seam\Resources\ActionAttempt\UpdateNoiseThreshold\Error\Error|null $error,
            /**
             * Result of the action.
             */
            public null $result,
            /**
             * @var value-of<\Seam\Resources\ActionAttempt\Status>|string|null
             */
            string|null $status,
        ) {
            parent::__construct(
                action_attempt_id: $action_attempt_id,
                action_type: $action_type,
                status: $status,
            );
        }
    }
}

namespace Seam\Resources\ActionAttempt\UpdateNoiseThreshold\Success {
    /**
     * Result of the action.
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

namespace Seam\Resources\ActionAttempt\UpdateNoiseThreshold\Error {
    /**
     * Error associated with the action.
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
}
