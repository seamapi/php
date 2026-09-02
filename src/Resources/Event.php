<?php

namespace Seam\Resources {
    /**
     * Base class for events returned by the Seam API. Known event_type values use subclasses; unknown values use this base class and retain their raw discriminator.
     */
    class Event
    {
        public static function from_json(mixed $json): Event|null
        {
            if (!$json) {
                return null;
            }
            $discriminant = is_string($json->event_type ?? null)
                ? \Seam\Resources\Event\EventType::tryFrom($json->event_type)
                : null;

            $resource = match ($discriminant) {
                \Seam\Resources\Event\EventType::ACCESS_CODE_CREATED
                    => \Seam\Resources\Event\AccessCodeCreated::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_CHANGED
                    => \Seam\Resources\Event\AccessCodeChanged::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_NAME_CHANGED
                    => \Seam\Resources\Event\AccessCodeNameChanged::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_CODE_CHANGED
                    => \Seam\Resources\Event\AccessCodeCodeChanged::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_TIME_FRAME_CHANGED
                    => \Seam\Resources\Event\AccessCodeTimeFrameChanged::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_MUTATIONS_REQUESTED
                    => \Seam\Resources\Event\AccessCodeMutationsRequested::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_SCHEDULED_ON_DEVICE
                    => \Seam\Resources\Event\AccessCodeScheduledOnDevice::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_SET_ON_DEVICE
                    => \Seam\Resources\Event\AccessCodeSetOnDevice::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_REMOVED_FROM_DEVICE
                    => \Seam\Resources\Event\AccessCodeRemovedFromDevice::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_DELAY_IN_SETTING_ON_DEVICE
                    => \Seam\Resources\Event\AccessCodeDelayInSettingOnDevice::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_FAILED_TO_SET_ON_DEVICE
                    => \Seam\Resources\Event\AccessCodeFailedToSetOnDevice::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_DELETED
                    => \Seam\Resources\Event\AccessCodeDeleted::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_DELAY_IN_REMOVING_FROM_DEVICE
                    => \Seam\Resources\Event\AccessCodeDelayInRemovingFromDevice::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_FAILED_TO_REMOVE_FROM_DEVICE
                    => \Seam\Resources\Event\AccessCodeFailedToRemoveFromDevice::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_MODIFIED_EXTERNAL_TO_SEAM
                    => \Seam\Resources\Event\AccessCodeModifiedExternalToSeam::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_DELETED_EXTERNAL_TO_SEAM
                    => \Seam\Resources\Event\AccessCodeDeletedExternalToSeam::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_BACKUP_ACCESS_CODE_PULLED
                    => \Seam\Resources\Event\AccessCodeBackupAccessCodePulled::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_UNMANAGED_CONVERTED_TO_MANAGED
                    => \Seam\Resources\Event\AccessCodeUnmanagedConvertedToManaged::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_UNMANAGED_FAILED_TO_CONVERT_TO_MANAGED
                    => \Seam\Resources\Event\AccessCodeUnmanagedFailedToConvertToManaged::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_UNMANAGED_CREATED
                    => \Seam\Resources\Event\AccessCodeUnmanagedCreated::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_CODE_UNMANAGED_REMOVED
                    => \Seam\Resources\Event\AccessCodeUnmanagedRemoved::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_GRANT_CREATED
                    => \Seam\Resources\Event\AccessGrantCreated::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_GRANT_DELETED
                    => \Seam\Resources\Event\AccessGrantDeleted::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_GRANT_ACCESS_GRANTED_TO_ALL_DOORS
                    => \Seam\Resources\Event\AccessGrantAccessGrantedToAllDoors::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_GRANT_ACCESS_GRANTED_TO_DOOR
                    => \Seam\Resources\Event\AccessGrantAccessGrantedToDoor::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_GRANT_ACCESS_TO_DOOR_LOST
                    => \Seam\Resources\Event\AccessGrantAccessToDoorLost::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_GRANT_ACCESS_TIMES_CHANGED
                    => \Seam\Resources\Event\AccessGrantAccessTimesChanged::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_GRANT_COULD_NOT_CREATE_REQUESTED_ACCESS_METHODS
                    => \Seam\Resources\Event\AccessGrantCouldNotCreateRequestedAccessMethods::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_METHOD_ISSUED
                    => \Seam\Resources\Event\AccessMethodIssued::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_METHOD_REVOKED
                    => \Seam\Resources\Event\AccessMethodRevoked::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_METHOD_CARD_ENCODING_REQUIRED
                    => \Seam\Resources\Event\AccessMethodCardEncodingRequired::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_METHOD_DELETED
                    => \Seam\Resources\Event\AccessMethodDeleted::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_METHOD_REISSUED
                    => \Seam\Resources\Event\AccessMethodReissued::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_METHOD_CREATED
                    => \Seam\Resources\Event\AccessMethodCreated::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_METHOD_DELAY_IN_ISSUING
                    => \Seam\Resources\Event\AccessMethodDelayInIssuing::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACCESS_METHOD_FAILED_TO_ISSUE
                    => \Seam\Resources\Event\AccessMethodFailedToIssue::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACS_SYSTEM_CONNECTED
                    => \Seam\Resources\Event\AcsSystemConnected::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACS_SYSTEM_ADDED
                    => \Seam\Resources\Event\AcsSystemAdded::from_json($json),
                \Seam\Resources\Event\EventType::ACS_SYSTEM_DISCONNECTED
                    => \Seam\Resources\Event\AcsSystemDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACS_CREDENTIAL_DELETED
                    => \Seam\Resources\Event\AcsCredentialDeleted::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACS_CREDENTIAL_ISSUED
                    => \Seam\Resources\Event\AcsCredentialIssued::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACS_CREDENTIAL_REISSUED
                    => \Seam\Resources\Event\AcsCredentialReissued::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACS_CREDENTIAL_INVALIDATED
                    => \Seam\Resources\Event\AcsCredentialInvalidated::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACS_USER_CREATED
                    => \Seam\Resources\Event\AcsUserCreated::from_json($json),
                \Seam\Resources\Event\EventType::ACS_USER_DELETED
                    => \Seam\Resources\Event\AcsUserDeleted::from_json($json),
                \Seam\Resources\Event\EventType::ACS_ENCODER_ADDED
                    => \Seam\Resources\Event\AcsEncoderAdded::from_json($json),
                \Seam\Resources\Event\EventType::ACS_ENCODER_REMOVED
                    => \Seam\Resources\Event\AcsEncoderRemoved::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACS_ACCESS_GROUP_DELETED
                    => \Seam\Resources\Event\AcsAccessGroupDeleted::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACS_ENTRANCE_ADDED
                    => \Seam\Resources\Event\AcsEntranceAdded::from_json($json),
                \Seam\Resources\Event\EventType::ACS_ENTRANCE_REMOVED
                    => \Seam\Resources\Event\AcsEntranceRemoved::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::CLIENT_SESSION_DELETED
                    => \Seam\Resources\Event\ClientSessionDeleted::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::CONNECTED_ACCOUNT_CONNECTED
                    => \Seam\Resources\Event\ConnectedAccountConnected::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::CONNECTED_ACCOUNT_CREATED
                    => \Seam\Resources\Event\ConnectedAccountCreated::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::CONNECTED_ACCOUNT_SUCCESSFUL_LOGIN
                    => \Seam\Resources\Event\ConnectedAccountSuccessfulLogin::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::CONNECTED_ACCOUNT_DISCONNECTED
                    => \Seam\Resources\Event\ConnectedAccountDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::CONNECTED_ACCOUNT_COMPLETED_FIRST_SYNC
                    => \Seam\Resources\Event\ConnectedAccountCompletedFirstSync::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::CONNECTED_ACCOUNT_DELETED
                    => \Seam\Resources\Event\ConnectedAccountDeleted::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::CONNECTED_ACCOUNT_COMPLETED_FIRST_SYNC_AFTER_RECONNECTION
                    => \Seam\Resources\Event\ConnectedAccountCompletedFirstSyncAfterReconnection::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::CONNECTED_ACCOUNT_REAUTHORIZATION_REQUESTED
                    => \Seam\Resources\Event\ConnectedAccountReauthorizationRequested::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACTION_ATTEMPT_LOCK_DOOR_SUCCEEDED
                    => \Seam\Resources\Event\ActionAttemptLockDoorSucceeded::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACTION_ATTEMPT_LOCK_DOOR_FAILED
                    => \Seam\Resources\Event\ActionAttemptLockDoorFailed::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACTION_ATTEMPT_UNLOCK_DOOR_SUCCEEDED
                    => \Seam\Resources\Event\ActionAttemptUnlockDoorSucceeded::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACTION_ATTEMPT_UNLOCK_DOOR_FAILED
                    => \Seam\Resources\Event\ActionAttemptUnlockDoorFailed::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACTION_ATTEMPT_SIMULATE_KEYPAD_CODE_ENTRY_SUCCEEDED
                    => \Seam\Resources\Event\ActionAttemptSimulateKeypadCodeEntrySucceeded::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACTION_ATTEMPT_SIMULATE_KEYPAD_CODE_ENTRY_FAILED
                    => \Seam\Resources\Event\ActionAttemptSimulateKeypadCodeEntryFailed::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACTION_ATTEMPT_SIMULATE_MANUAL_LOCK_VIA_KEYPAD_SUCCEEDED
                    => \Seam\Resources\Event\ActionAttemptSimulateManualLockViaKeypadSucceeded::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::ACTION_ATTEMPT_SIMULATE_MANUAL_LOCK_VIA_KEYPAD_FAILED
                    => \Seam\Resources\Event\ActionAttemptSimulateManualLockViaKeypadFailed::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::CONNECT_WEBVIEW_LOGIN_SUCCEEDED
                    => \Seam\Resources\Event\ConnectWebviewLoginSucceeded::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::CONNECT_WEBVIEW_LOGIN_FAILED
                    => \Seam\Resources\Event\ConnectWebviewLoginFailed::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_CONNECTED
                    => \Seam\Resources\Event\DeviceConnected::from_json($json),
                \Seam\Resources\Event\EventType::DEVICE_ADDED
                    => \Seam\Resources\Event\DeviceAdded::from_json($json),
                \Seam\Resources\Event\EventType::DEVICE_CONVERTED_TO_UNMANAGED
                    => \Seam\Resources\Event\DeviceConvertedToUnmanaged::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_UNMANAGED_CONVERTED_TO_MANAGED
                    => \Seam\Resources\Event\DeviceUnmanagedConvertedToManaged::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_UNMANAGED_CONNECTED
                    => \Seam\Resources\Event\DeviceUnmanagedConnected::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_DISCONNECTED
                    => \Seam\Resources\Event\DeviceDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_UNMANAGED_DISCONNECTED
                    => \Seam\Resources\Event\DeviceUnmanagedDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_TAMPERED
                    => \Seam\Resources\Event\DeviceTampered::from_json($json),
                \Seam\Resources\Event\EventType::DEVICE_LOW_BATTERY
                    => \Seam\Resources\Event\DeviceLowBattery::from_json($json),
                \Seam\Resources\Event\EventType::DEVICE_BATTERY_STATUS_CHANGED
                    => \Seam\Resources\Event\DeviceBatteryStatusChanged::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_REMOVED
                    => \Seam\Resources\Event\DeviceRemoved::from_json($json),
                \Seam\Resources\Event\EventType::DEVICE_DELETED
                    => \Seam\Resources\Event\DeviceDeleted::from_json($json),
                \Seam\Resources\Event\EventType::DEVICE_THIRD_PARTY_INTEGRATION_DETECTED
                    => \Seam\Resources\Event\DeviceThirdPartyIntegrationDetected::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_THIRD_PARTY_INTEGRATION_NO_LONGER_DETECTED
                    => \Seam\Resources\Event\DeviceThirdPartyIntegrationNoLongerDetected::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_SALTO_PRIVACY_MODE_ACTIVATED
                    => \Seam\Resources\Event\DeviceSaltoPrivacyModeActivated::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_SALTO_PRIVACY_MODE_DEACTIVATED
                    => \Seam\Resources\Event\DeviceSaltoPrivacyModeDeactivated::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_CONNECTION_BECAME_FLAKY
                    => \Seam\Resources\Event\DeviceConnectionBecameFlaky::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_CONNECTION_STABILIZED
                    => \Seam\Resources\Event\DeviceConnectionStabilized::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_ERROR_SUBSCRIPTION_REQUIRED
                    => \Seam\Resources\Event\DeviceErrorSubscriptionRequired::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_ERROR_SUBSCRIPTION_REQUIRED_RESOLVED
                    => \Seam\Resources\Event\DeviceErrorSubscriptionRequiredResolved::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_ACCESSORY_KEYPAD_CONNECTED
                    => \Seam\Resources\Event\DeviceAccessoryKeypadConnected::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_ACCESSORY_KEYPAD_DISCONNECTED
                    => \Seam\Resources\Event\DeviceAccessoryKeypadDisconnected::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::NOISE_SENSOR_NOISE_THRESHOLD_TRIGGERED
                    => \Seam\Resources\Event\NoiseSensorNoiseThresholdTriggered::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::LOCK_LOCKED
                    => \Seam\Resources\Event\LockLocked::from_json($json),
                \Seam\Resources\Event\EventType::LOCK_UNLOCKED
                    => \Seam\Resources\Event\LockUnlocked::from_json($json),
                \Seam\Resources\Event\EventType::LOCK_ACCESS_DENIED
                    => \Seam\Resources\Event\LockAccessDenied::from_json($json),
                \Seam\Resources\Event\EventType::THERMOSTAT_CLIMATE_PRESET_ACTIVATED
                    => \Seam\Resources\Event\ThermostatClimatePresetActivated::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::THERMOSTAT_MANUALLY_ADJUSTED
                    => \Seam\Resources\Event\ThermostatManuallyAdjusted::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::THERMOSTAT_TEMPERATURE_THRESHOLD_EXCEEDED
                    => \Seam\Resources\Event\ThermostatTemperatureThresholdExceeded::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::THERMOSTAT_TEMPERATURE_THRESHOLD_NO_LONGER_EXCEEDED
                    => \Seam\Resources\Event\ThermostatTemperatureThresholdNoLongerExceeded::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::THERMOSTAT_TEMPERATURE_REACHED_SET_POINT
                    => \Seam\Resources\Event\ThermostatTemperatureReachedSetPoint::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::THERMOSTAT_TEMPERATURE_CHANGED
                    => \Seam\Resources\Event\ThermostatTemperatureChanged::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::DEVICE_NAME_CHANGED
                    => \Seam\Resources\Event\DeviceNameChanged::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::CAMERA_ACTIVATED
                    => \Seam\Resources\Event\CameraActivated::from_json($json),
                \Seam\Resources\Event\EventType::DEVICE_DOORBELL_RANG
                    => \Seam\Resources\Event\DeviceDoorbellRang::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::PHONE_DEACTIVATED
                    => \Seam\Resources\Event\PhoneDeactivated::from_json($json),
                \Seam\Resources\Event\EventType::SPACE_DEVICE_MEMBERSHIP_CHANGED
                    => \Seam\Resources\Event\SpaceDeviceMembershipChanged::from_json(
                    $json,
                ),
                \Seam\Resources\Event\EventType::SPACE_CREATED
                    => \Seam\Resources\Event\SpaceCreated::from_json($json),
                \Seam\Resources\Event\EventType::SPACE_DELETED
                    => \Seam\Resources\Event\SpaceDeleted::from_json($json),
                default => new self(
                    created_at: $json->created_at ?? null,
                    event_id: $json->event_id ?? null,
                    event_type: $json->event_type ?? null,
                    occurred_at: $json->occurred_at ?? null,
                    workspace_id: $json->workspace_id ?? null,
                    event_description: $json->event_description ?? null,
                ),
            };

            $resource->raw_json_source = $json;

            return $resource;
        }

        private mixed $raw_json_source = null;

        /**
         * The payload this event was parsed from, as JSON. Reaches fields the
         * generated properties do not cover, such as one added after this release.
         */
        public function raw_json(): string
        {
            return json_encode($this->raw_json_source);
        }

        public function __construct(
            /**
             * Date and time at which the event was created.
             */
            public string|null $created_at,
            /**
             * ID of the event.
             */
            public string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            public string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            public string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            public string|null $workspace_id,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            public string|null $event_description = null,
        ) {}
    }
}

namespace Seam\Resources\Event {
    /**
     * An [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes) was created.
     */
    final class AccessCodeCreated extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AccessCodeCreated|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes) was changed.
     */
    final class AccessCodeChanged extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AccessCodeChanged|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                change_reason: $json->change_reason ?? null,
                changed_properties: \Seam\Parse::to_list(
                    $json->changed_properties ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\AccessCodeChanged\ChangedProperties::from_json(
                        $c,
                    ),
                ),
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Human-readable reason for the change (e.g. `ongoing code auto-renewed`).
             */
            public string|null $change_reason = null,
            /**
             * List of properties that changed on the access code.
             *
             * @var list<\Seam\Resources\Event\AccessCodeChanged\ChangedProperties>|null
             */
            public array|null $changed_properties = null,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * The name of an [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes) was changed on the device.
     */
    final class AccessCodeNameChanged extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeNameChanged|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                description: $json->description ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\Event\AccessCodeNameChanged\From::from_json(
                        $json->from,
                    )
                    : null,
                occurred_at: $json->occurred_at ?? null,
                to: isset($json->to)
                    ? \Seam\Resources\Event\AccessCodeNameChanged\To::from_json(
                        $json->to,
                    )
                    : null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * Human-readable description of the change and its source.
             */
            public string|null $description,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Previous access code name configuration.
             */
            public \Seam\Resources\Event\AccessCodeNameChanged\From|null $from,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * New access code name configuration.
             */
            public \Seam\Resources\Event\AccessCodeNameChanged\To|null $to,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * The pin code of an [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes) was changed on the device.
     */
    final class AccessCodeCodeChanged extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeCodeChanged|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                description: $json->description ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\Event\AccessCodeCodeChanged\From::from_json(
                        $json->from,
                    )
                    : null,
                occurred_at: $json->occurred_at ?? null,
                to: isset($json->to)
                    ? \Seam\Resources\Event\AccessCodeCodeChanged\To::from_json(
                        $json->to,
                    )
                    : null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * Human-readable description of the change and its source.
             */
            public string|null $description,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Previous pin code configuration.
             */
            public \Seam\Resources\Event\AccessCodeCodeChanged\From|null $from,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * New pin code configuration.
             */
            public \Seam\Resources\Event\AccessCodeCodeChanged\To|null $to,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * The time frame of an [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes) was changed on the device.
     */
    final class AccessCodeTimeFrameChanged extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeTimeFrameChanged|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                description: $json->description ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                from: isset($json->from)
                    ? \Seam\Resources\Event\AccessCodeTimeFrameChanged\From::from_json(
                        $json->from,
                    )
                    : null,
                occurred_at: $json->occurred_at ?? null,
                to: isset($json->to)
                    ? \Seam\Resources\Event\AccessCodeTimeFrameChanged\To::from_json(
                        $json->to,
                    )
                    : null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * Human-readable description of the change and its source.
             */
            public string|null $description,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Previous time frame configuration.
             */
            public \Seam\Resources\Event\AccessCodeTimeFrameChanged\From|null $from,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * New time frame configuration.
             */
            public \Seam\Resources\Event\AccessCodeTimeFrameChanged\To|null $to,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * Mutations were requested on an [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes). This event fires at request time, before the change is confirmed on the device.
     */
    final class AccessCodeMutationsRequested extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeMutationsRequested|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                requested_mutations: \Seam\Parse::to_list(
                    $json->requested_mutations ?? null,
                    fn(
                        $r,
                    ) => \Seam\Resources\Event\AccessCodeMutationsRequested\RequestedMutations::from_json(
                        $r,
                    ),
                ),
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * Array of mutations requested on the access code, each containing the mutation type and from/to values.
             *
             * @var list<\Seam\Resources\Event\AccessCodeMutationsRequested\RequestedMutations>
             */
            public array $requested_mutations,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes) was [scheduled natively](https://docs.seam.co/low-level-apis/smart-locks/access-codes#native-scheduling) on a device.
     */
    final class AccessCodeScheduledOnDevice extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeScheduledOnDevice|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                code: $json->code ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * Code for the affected access code.
             */
            public string|null $code,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes) was set on a device.
     */
    final class AccessCodeSetOnDevice extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeSetOnDevice|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                code: $json->code ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * Code for the affected access code.
             */
            public string|null $code,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes) was removed from a device.
     */
    final class AccessCodeRemovedFromDevice extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeRemovedFromDevice|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * There was an unusually long delay in setting an [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes) on a device.
     */
    final class AccessCodeDelayInSettingOnDevice extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeDelayInSettingOnDevice|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_errors: \Seam\Parse::to_list(
                    $json->access_code_errors ?? null,
                    fn(
                        $a,
                    ) => \Seam\Resources\Event\AccessCodeDelayInSettingOnDevice\AccessCodeErrors::from_json(
                        $a,
                    ),
                ),
                access_code_id: $json->access_code_id ?? null,
                access_code_warnings: \Seam\Parse::to_list(
                    $json->access_code_warnings ?? null,
                    fn(
                        $a,
                    ) => \Seam\Resources\Event\AccessCodeDelayInSettingOnDevice\AccessCodeWarnings::from_json(
                        $a,
                    ),
                ),
                connected_account_errors: \Seam\Parse::to_list(
                    $json->connected_account_errors ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\AccessCodeDelayInSettingOnDevice\ConnectedAccountErrors::from_json(
                        $c,
                    ),
                ),
                connected_account_id: $json->connected_account_id ?? null,
                connected_account_warnings: \Seam\Parse::to_list(
                    $json->connected_account_warnings ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\AccessCodeDelayInSettingOnDevice\ConnectedAccountWarnings::from_json(
                        $c,
                    ),
                ),
                created_at: $json->created_at ?? null,
                device_errors: \Seam\Parse::to_list(
                    $json->device_errors ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\AccessCodeDelayInSettingOnDevice\DeviceErrors::from_json(
                        $d,
                    ),
                ),
                device_id: $json->device_id ?? null,
                device_warnings: \Seam\Parse::to_list(
                    $json->device_warnings ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\AccessCodeDelayInSettingOnDevice\DeviceWarnings::from_json(
                        $d,
                    ),
                ),
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Errors associated with the access code.
             *
             * @var list<\Seam\Resources\Event\AccessCodeDelayInSettingOnDevice\AccessCodeErrors>
             */
            public array $access_code_errors,
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * Warnings associated with the access code.
             *
             * @var list<\Seam\Resources\Event\AccessCodeDelayInSettingOnDevice\AccessCodeWarnings>
             */
            public array $access_code_warnings,
            /**
             * Errors associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\AccessCodeDelayInSettingOnDevice\ConnectedAccountErrors>
             */
            public array $connected_account_errors,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Warnings associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\AccessCodeDelayInSettingOnDevice\ConnectedAccountWarnings>
             */
            public array $connected_account_warnings,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * Errors associated with the device.
             *
             * @var list<\Seam\Resources\Event\AccessCodeDelayInSettingOnDevice\DeviceErrors>
             */
            public array $device_errors,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * Warnings associated with the device.
             *
             * @var list<\Seam\Resources\Event\AccessCodeDelayInSettingOnDevice\DeviceWarnings>
             */
            public array $device_warnings,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes) failed to be set on a device.
     */
    final class AccessCodeFailedToSetOnDevice extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeFailedToSetOnDevice|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_errors: \Seam\Parse::to_list(
                    $json->access_code_errors ?? null,
                    fn(
                        $a,
                    ) => \Seam\Resources\Event\AccessCodeFailedToSetOnDevice\AccessCodeErrors::from_json(
                        $a,
                    ),
                ),
                access_code_id: $json->access_code_id ?? null,
                access_code_warnings: \Seam\Parse::to_list(
                    $json->access_code_warnings ?? null,
                    fn(
                        $a,
                    ) => \Seam\Resources\Event\AccessCodeFailedToSetOnDevice\AccessCodeWarnings::from_json(
                        $a,
                    ),
                ),
                connected_account_errors: \Seam\Parse::to_list(
                    $json->connected_account_errors ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\AccessCodeFailedToSetOnDevice\ConnectedAccountErrors::from_json(
                        $c,
                    ),
                ),
                connected_account_id: $json->connected_account_id ?? null,
                connected_account_warnings: \Seam\Parse::to_list(
                    $json->connected_account_warnings ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\AccessCodeFailedToSetOnDevice\ConnectedAccountWarnings::from_json(
                        $c,
                    ),
                ),
                created_at: $json->created_at ?? null,
                device_errors: \Seam\Parse::to_list(
                    $json->device_errors ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\AccessCodeFailedToSetOnDevice\DeviceErrors::from_json(
                        $d,
                    ),
                ),
                device_id: $json->device_id ?? null,
                device_warnings: \Seam\Parse::to_list(
                    $json->device_warnings ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\AccessCodeFailedToSetOnDevice\DeviceWarnings::from_json(
                        $d,
                    ),
                ),
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Errors associated with the access code.
             *
             * @var list<\Seam\Resources\Event\AccessCodeFailedToSetOnDevice\AccessCodeErrors>
             */
            public array $access_code_errors,
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * Warnings associated with the access code.
             *
             * @var list<\Seam\Resources\Event\AccessCodeFailedToSetOnDevice\AccessCodeWarnings>
             */
            public array $access_code_warnings,
            /**
             * Errors associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\AccessCodeFailedToSetOnDevice\ConnectedAccountErrors>
             */
            public array $connected_account_errors,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Warnings associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\AccessCodeFailedToSetOnDevice\ConnectedAccountWarnings>
             */
            public array $connected_account_warnings,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * Errors associated with the device.
             *
             * @var list<\Seam\Resources\Event\AccessCodeFailedToSetOnDevice\DeviceErrors>
             */
            public array $device_errors,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * Warnings associated with the device.
             *
             * @var list<\Seam\Resources\Event\AccessCodeFailedToSetOnDevice\DeviceWarnings>
             */
            public array $device_warnings,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes) was deleted.
     */
    final class AccessCodeDeleted extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AccessCodeDeleted|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                code: $json->code ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * Code for the affected access code.
             */
            public string|null $code,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * There was an unusually long delay in removing an [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes) from a device.
     */
    final class AccessCodeDelayInRemovingFromDevice extends
        \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeDelayInRemovingFromDevice|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_errors: \Seam\Parse::to_list(
                    $json->access_code_errors ?? null,
                    fn(
                        $a,
                    ) => \Seam\Resources\Event\AccessCodeDelayInRemovingFromDevice\AccessCodeErrors::from_json(
                        $a,
                    ),
                ),
                access_code_id: $json->access_code_id ?? null,
                access_code_warnings: \Seam\Parse::to_list(
                    $json->access_code_warnings ?? null,
                    fn(
                        $a,
                    ) => \Seam\Resources\Event\AccessCodeDelayInRemovingFromDevice\AccessCodeWarnings::from_json(
                        $a,
                    ),
                ),
                connected_account_errors: \Seam\Parse::to_list(
                    $json->connected_account_errors ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\AccessCodeDelayInRemovingFromDevice\ConnectedAccountErrors::from_json(
                        $c,
                    ),
                ),
                connected_account_id: $json->connected_account_id ?? null,
                connected_account_warnings: \Seam\Parse::to_list(
                    $json->connected_account_warnings ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\AccessCodeDelayInRemovingFromDevice\ConnectedAccountWarnings::from_json(
                        $c,
                    ),
                ),
                created_at: $json->created_at ?? null,
                device_errors: \Seam\Parse::to_list(
                    $json->device_errors ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\AccessCodeDelayInRemovingFromDevice\DeviceErrors::from_json(
                        $d,
                    ),
                ),
                device_id: $json->device_id ?? null,
                device_warnings: \Seam\Parse::to_list(
                    $json->device_warnings ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\AccessCodeDelayInRemovingFromDevice\DeviceWarnings::from_json(
                        $d,
                    ),
                ),
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Errors associated with the access code.
             *
             * @var list<\Seam\Resources\Event\AccessCodeDelayInRemovingFromDevice\AccessCodeErrors>
             */
            public array $access_code_errors,
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * Warnings associated with the access code.
             *
             * @var list<\Seam\Resources\Event\AccessCodeDelayInRemovingFromDevice\AccessCodeWarnings>
             */
            public array $access_code_warnings,
            /**
             * Errors associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\AccessCodeDelayInRemovingFromDevice\ConnectedAccountErrors>
             */
            public array $connected_account_errors,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Warnings associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\AccessCodeDelayInRemovingFromDevice\ConnectedAccountWarnings>
             */
            public array $connected_account_warnings,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * Errors associated with the device.
             *
             * @var list<\Seam\Resources\Event\AccessCodeDelayInRemovingFromDevice\DeviceErrors>
             */
            public array $device_errors,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * Warnings associated with the device.
             *
             * @var list<\Seam\Resources\Event\AccessCodeDelayInRemovingFromDevice\DeviceWarnings>
             */
            public array $device_warnings,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes) failed to be removed from a device.
     */
    final class AccessCodeFailedToRemoveFromDevice extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeFailedToRemoveFromDevice|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_errors: \Seam\Parse::to_list(
                    $json->access_code_errors ?? null,
                    fn(
                        $a,
                    ) => \Seam\Resources\Event\AccessCodeFailedToRemoveFromDevice\AccessCodeErrors::from_json(
                        $a,
                    ),
                ),
                access_code_id: $json->access_code_id ?? null,
                access_code_warnings: \Seam\Parse::to_list(
                    $json->access_code_warnings ?? null,
                    fn(
                        $a,
                    ) => \Seam\Resources\Event\AccessCodeFailedToRemoveFromDevice\AccessCodeWarnings::from_json(
                        $a,
                    ),
                ),
                connected_account_errors: \Seam\Parse::to_list(
                    $json->connected_account_errors ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\AccessCodeFailedToRemoveFromDevice\ConnectedAccountErrors::from_json(
                        $c,
                    ),
                ),
                connected_account_id: $json->connected_account_id ?? null,
                connected_account_warnings: \Seam\Parse::to_list(
                    $json->connected_account_warnings ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\AccessCodeFailedToRemoveFromDevice\ConnectedAccountWarnings::from_json(
                        $c,
                    ),
                ),
                created_at: $json->created_at ?? null,
                device_errors: \Seam\Parse::to_list(
                    $json->device_errors ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\AccessCodeFailedToRemoveFromDevice\DeviceErrors::from_json(
                        $d,
                    ),
                ),
                device_id: $json->device_id ?? null,
                device_warnings: \Seam\Parse::to_list(
                    $json->device_warnings ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\AccessCodeFailedToRemoveFromDevice\DeviceWarnings::from_json(
                        $d,
                    ),
                ),
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Errors associated with the access code.
             *
             * @var list<\Seam\Resources\Event\AccessCodeFailedToRemoveFromDevice\AccessCodeErrors>
             */
            public array $access_code_errors,
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * Warnings associated with the access code.
             *
             * @var list<\Seam\Resources\Event\AccessCodeFailedToRemoveFromDevice\AccessCodeWarnings>
             */
            public array $access_code_warnings,
            /**
             * Errors associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\AccessCodeFailedToRemoveFromDevice\ConnectedAccountErrors>
             */
            public array $connected_account_errors,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Warnings associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\AccessCodeFailedToRemoveFromDevice\ConnectedAccountWarnings>
             */
            public array $connected_account_warnings,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * Errors associated with the device.
             *
             * @var list<\Seam\Resources\Event\AccessCodeFailedToRemoveFromDevice\DeviceErrors>
             */
            public array $device_errors,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * Warnings associated with the device.
             *
             * @var list<\Seam\Resources\Event\AccessCodeFailedToRemoveFromDevice\DeviceWarnings>
             */
            public array $device_warnings,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes) was modified outside of Seam.
     */
    final class AccessCodeModifiedExternalToSeam extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeModifiedExternalToSeam|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes) was deleted outside of Seam.
     */
    final class AccessCodeDeletedExternalToSeam extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeDeletedExternalToSeam|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A [backup access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/backup-access-codes) was pulled from the backup access code pool and set on a device.
     */
    final class AccessCodeBackupAccessCodePulled extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeBackupAccessCodePulled|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                backup_access_code_id: $json->backup_access_code_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * ID of the backup access code that was pulled from the pool.
             */
            public string|null $backup_access_code_id,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [unmanaged access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes) was converted successfully to a managed access code.
     */
    final class AccessCodeUnmanagedConvertedToManaged extends
        \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeUnmanagedConvertedToManaged|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [unmanaged access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes) failed to be converted to a managed access code.
     */
    final class AccessCodeUnmanagedFailedToConvertToManaged extends
        \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeUnmanagedFailedToConvertToManaged|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_errors: \Seam\Parse::to_list(
                    $json->access_code_errors ?? null,
                    fn(
                        $a,
                    ) => \Seam\Resources\Event\AccessCodeUnmanagedFailedToConvertToManaged\AccessCodeErrors::from_json(
                        $a,
                    ),
                ),
                access_code_id: $json->access_code_id ?? null,
                access_code_warnings: \Seam\Parse::to_list(
                    $json->access_code_warnings ?? null,
                    fn(
                        $a,
                    ) => \Seam\Resources\Event\AccessCodeUnmanagedFailedToConvertToManaged\AccessCodeWarnings::from_json(
                        $a,
                    ),
                ),
                connected_account_errors: \Seam\Parse::to_list(
                    $json->connected_account_errors ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\AccessCodeUnmanagedFailedToConvertToManaged\ConnectedAccountErrors::from_json(
                        $c,
                    ),
                ),
                connected_account_id: $json->connected_account_id ?? null,
                connected_account_warnings: \Seam\Parse::to_list(
                    $json->connected_account_warnings ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\AccessCodeUnmanagedFailedToConvertToManaged\ConnectedAccountWarnings::from_json(
                        $c,
                    ),
                ),
                created_at: $json->created_at ?? null,
                device_errors: \Seam\Parse::to_list(
                    $json->device_errors ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\AccessCodeUnmanagedFailedToConvertToManaged\DeviceErrors::from_json(
                        $d,
                    ),
                ),
                device_id: $json->device_id ?? null,
                device_warnings: \Seam\Parse::to_list(
                    $json->device_warnings ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\AccessCodeUnmanagedFailedToConvertToManaged\DeviceWarnings::from_json(
                        $d,
                    ),
                ),
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Errors associated with the access code.
             *
             * @var list<\Seam\Resources\Event\AccessCodeUnmanagedFailedToConvertToManaged\AccessCodeErrors>
             */
            public array $access_code_errors,
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * Warnings associated with the access code.
             *
             * @var list<\Seam\Resources\Event\AccessCodeUnmanagedFailedToConvertToManaged\AccessCodeWarnings>
             */
            public array $access_code_warnings,
            /**
             * Errors associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\AccessCodeUnmanagedFailedToConvertToManaged\ConnectedAccountErrors>
             */
            public array $connected_account_errors,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Warnings associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\AccessCodeUnmanagedFailedToConvertToManaged\ConnectedAccountWarnings>
             */
            public array $connected_account_warnings,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * Errors associated with the device.
             *
             * @var list<\Seam\Resources\Event\AccessCodeUnmanagedFailedToConvertToManaged\DeviceErrors>
             */
            public array $device_errors,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * Warnings associated with the device.
             *
             * @var list<\Seam\Resources\Event\AccessCodeUnmanagedFailedToConvertToManaged\DeviceWarnings>
             */
            public array $device_warnings,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [unmanaged access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes) was created on a device.
     */
    final class AccessCodeUnmanagedCreated extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeUnmanagedCreated|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [unmanaged access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes/migrating-existing-access-codes) was removed from a device.
     */
    final class AccessCodeUnmanagedRemoved extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessCodeUnmanagedRemoved|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_id: $json->access_code_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access code.
             */
            public string|null $access_code_id,
            /**
             * ID of the connected account associated with the affected access code.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the device associated with the affected access code.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An Access Grant was created.
     */
    final class AccessGrantCreated extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AccessGrantCreated|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_grant_id: $json->access_grant_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected Access Grant.
             */
            public string|null $access_grant_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An Access Grant was deleted.
     */
    final class AccessGrantDeleted extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AccessGrantDeleted|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_grant_id: $json->access_grant_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected Access Grant.
             */
            public string|null $access_grant_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * All access requested for an Access Grant was successfully granted.
     */
    final class AccessGrantAccessGrantedToAllDoors extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessGrantAccessGrantedToAllDoors|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_grant_id: $json->access_grant_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected Access Grant.
             */
            public string|null $access_grant_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * Access requested as part of an Access Grant to a particular door was successfully granted.
     */
    final class AccessGrantAccessGrantedToDoor extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessGrantAccessGrantedToDoor|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_grant_id: $json->access_grant_id ?? null,
                acs_entrance_id: $json->acs_entrance_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected Access Grant.
             */
            public string|null $access_grant_id,
            /**
             * ID of the affected [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public string|null $acs_entrance_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * Access to a particular door that was requested as part of an Access Grant was lost.
     */
    final class AccessGrantAccessToDoorLost extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessGrantAccessToDoorLost|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_grant_id: $json->access_grant_id ?? null,
                acs_entrance_id: $json->acs_entrance_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected Access Grant.
             */
            public string|null $access_grant_id,
            /**
             * ID of the affected [entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details).
             */
            public string|null $acs_entrance_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An Access Grant's start or end time was changed.
     */
    final class AccessGrantAccessTimesChanged extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessGrantAccessTimesChanged|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_grant_id: $json->access_grant_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                access_grant_key: $json->access_grant_key ?? null,
                ends_at: $json->ends_at ?? null,
                event_description: $json->event_description ?? null,
                starts_at: $json->starts_at ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected Access Grant.
             */
            public string|null $access_grant_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Key of the affected Access Grant (if present).
             */
            public string|null $access_grant_key = null,
            /**
             * The new end time for the access grant.
             */
            public string|null $ends_at = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
            /**
             * The new start time for the access grant.
             */
            public string|null $starts_at = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * One or more requested access methods could not be created for an Access Grant.
     */
    final class AccessGrantCouldNotCreateRequestedAccessMethods extends
        \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessGrantCouldNotCreateRequestedAccessMethods|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_grant_id: $json->access_grant_id ?? null,
                created_at: $json->created_at ?? null,
                error_message: $json->error_message ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                event_description: $json->event_description ?? null,
                missing_device_ids: $json->missing_device_ids ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected Access Grant.
             */
            public string|null $access_grant_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * Description of why the access methods could not be created.
             */
            public string|null $error_message,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
            /**
             * IDs of the devices that did not receive a requested access method. Use these to identify which specific devices failed without having to fetch the Access Grant.
             *
             * @var list<string>|null
             */
            public array|null $missing_device_ids = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An access method was issued.
     */
    final class AccessMethodIssued extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AccessMethodIssued|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_grant_ids: $json->access_grant_ids ?? null,
                access_method_id: $json->access_method_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                access_grant_keys: $json->access_grant_keys ?? null,
                code: $json->code ?? null,
                event_description: $json->event_description ?? null,
                is_backup_code: $json->is_backup_code ?? null,
            );
        }

        public function __construct(
            /**
             * IDs of the access grants associated with this access method.
             *
             * @var list<string>|null
             */
            public array|null $access_grant_ids,
            /**
             * ID of the affected access method.
             */
            public string|null $access_method_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Keys of the access grants associated with this access method (if present).
             *
             * @var list<string>|null
             */
            public array|null $access_grant_keys = null,
            /**
             * The actual PIN code for code access methods (only present when mode is 'code').
             */
            public string|null $code = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
            /**
             * Indicates whether the code is a backup code (only present when mode is 'code' and a backup code was used).
             */
            public bool|null $is_backup_code = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An access method was revoked.
     */
    final class AccessMethodRevoked extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AccessMethodRevoked|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_grant_ids: $json->access_grant_ids ?? null,
                access_method_id: $json->access_method_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                access_grant_keys: $json->access_grant_keys ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * IDs of the access grants associated with this access method.
             *
             * @var list<string>|null
             */
            public array|null $access_grant_ids,
            /**
             * ID of the affected access method.
             */
            public string|null $access_method_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Keys of the access grants associated with this access method (if present).
             *
             * @var list<string>|null
             */
            public array|null $access_grant_keys = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An access method representing a physical card requires encoding.
     */
    final class AccessMethodCardEncodingRequired extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessMethodCardEncodingRequired|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_grant_ids: $json->access_grant_ids ?? null,
                access_method_id: $json->access_method_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                access_grant_keys: $json->access_grant_keys ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * IDs of the access grants associated with this access method.
             *
             * @var list<string>|null
             */
            public array|null $access_grant_ids,
            /**
             * ID of the affected access method.
             */
            public string|null $access_method_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Keys of the access grants associated with this access method (if present).
             *
             * @var list<string>|null
             */
            public array|null $access_grant_keys = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An access method was deleted.
     */
    final class AccessMethodDeleted extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AccessMethodDeleted|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_grant_ids: $json->access_grant_ids ?? null,
                access_method_id: $json->access_method_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                access_grant_keys: $json->access_grant_keys ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * IDs of the access grants associated with this access method.
             *
             * @var list<string>|null
             */
            public array|null $access_grant_ids,
            /**
             * ID of the affected access method.
             */
            public string|null $access_method_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Keys of the access grants associated with this access method (if present).
             *
             * @var list<string>|null
             */
            public array|null $access_grant_keys = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An access method was reissued.
     */
    final class AccessMethodReissued extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AccessMethodReissued|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_grant_ids: $json->access_grant_ids ?? null,
                access_method_id: $json->access_method_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                access_grant_keys: $json->access_grant_keys ?? null,
                code: $json->code ?? null,
                event_description: $json->event_description ?? null,
                is_backup_code: $json->is_backup_code ?? null,
            );
        }

        public function __construct(
            /**
             * IDs of the access grants associated with this access method.
             *
             * @var list<string>|null
             */
            public array|null $access_grant_ids,
            /**
             * ID of the affected access method.
             */
            public string|null $access_method_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Keys of the access grants associated with this access method (if present).
             *
             * @var list<string>|null
             */
            public array|null $access_grant_keys = null,
            /**
             * The actual PIN code for code access methods (only present when mode is 'code').
             */
            public string|null $code = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
            /**
             * Indicates whether the code is a backup code (only present when mode is 'code' and a backup code was used).
             */
            public bool|null $is_backup_code = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An access method was created.
     */
    final class AccessMethodCreated extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AccessMethodCreated|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_grant_ids: $json->access_grant_ids ?? null,
                access_method_id: $json->access_method_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                access_grant_keys: $json->access_grant_keys ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * IDs of the access grants associated with this access method.
             *
             * @var list<string>|null
             */
            public array|null $access_grant_ids,
            /**
             * ID of the affected access method.
             */
            public string|null $access_method_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Keys of the access grants associated with this access method (if present).
             *
             * @var list<string>|null
             */
            public array|null $access_grant_keys = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * Seam has not yet issued this access method, even though its access grant is about to begin, so access may not be ready when the recipient arrives. Seam is still attempting to issue it, and the accompanying `delay_in_issuing` warning clears automatically once issuance succeeds.
     */
    final class AccessMethodDelayInIssuing extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessMethodDelayInIssuing|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_grant_ids: $json->access_grant_ids ?? null,
                access_method_id: $json->access_method_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                access_grant_keys: $json->access_grant_keys ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * IDs of the access grants associated with this access method.
             *
             * @var list<string>|null
             */
            public array|null $access_grant_ids,
            /**
             * ID of the affected access method.
             */
            public string|null $access_method_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Keys of the access grants associated with this access method (if present).
             *
             * @var list<string>|null
             */
            public array|null $access_grant_keys = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * Seam was unable to issue this access method before its access grant started, so the recipient may be unable to access the space. This usually points to a problem that needs attention, such as an offline or disconnected device. Seam keeps retrying, and the accompanying `failed_to_issue` error clears automatically if the access method is eventually issued.
     */
    final class AccessMethodFailedToIssue extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AccessMethodFailedToIssue|null {
            if (!$json) {
                return null;
            }
            return new self(
                access_grant_ids: $json->access_grant_ids ?? null,
                access_method_id: $json->access_method_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                access_grant_keys: $json->access_grant_keys ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * IDs of the access grants associated with this access method.
             *
             * @var list<string>|null
             */
            public array|null $access_grant_ids,
            /**
             * ID of the affected access method.
             */
            public string|null $access_method_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Keys of the access grants associated with this access method (if present).
             *
             * @var list<string>|null
             */
            public array|null $access_grant_keys = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access system](https://docs.seam.co/low-level-apis/access-systems) was connected.
     */
    final class AcsSystemConnected extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AcsSystemConnected|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_system_id: $json->acs_system_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the access system.
             */
            public string|null $acs_system_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account.
             */
            public string|null $connected_account_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access system](https://docs.seam.co/low-level-apis/access-systems) was added.
     */
    final class AcsSystemAdded extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AcsSystemAdded|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_system_id: $json->acs_system_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the access system.
             */
            public string|null $acs_system_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account.
             */
            public string|null $connected_account_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access system](https://docs.seam.co/low-level-apis/access-systems) was disconnected.
     */
    final class AcsSystemDisconnected extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AcsSystemDisconnected|null {
            if (!$json) {
                return null;
            }
            return new self(
                acs_system_errors: \Seam\Parse::to_list(
                    $json->acs_system_errors ?? null,
                    fn(
                        $a,
                    ) => \Seam\Resources\Event\AcsSystemDisconnected\AcsSystemErrors::from_json(
                        $a,
                    ),
                ),
                acs_system_id: $json->acs_system_id ?? null,
                acs_system_warnings: \Seam\Parse::to_list(
                    $json->acs_system_warnings ?? null,
                    fn(
                        $a,
                    ) => \Seam\Resources\Event\AcsSystemDisconnected\AcsSystemWarnings::from_json(
                        $a,
                    ),
                ),
                connected_account_errors: \Seam\Parse::to_list(
                    $json->connected_account_errors ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\AcsSystemDisconnected\ConnectedAccountErrors::from_json(
                        $c,
                    ),
                ),
                connected_account_warnings: \Seam\Parse::to_list(
                    $json->connected_account_warnings ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\AcsSystemDisconnected\ConnectedAccountWarnings::from_json(
                        $c,
                    ),
                ),
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Errors associated with the access control system.
             *
             * @var list<\Seam\Resources\Event\AcsSystemDisconnected\AcsSystemErrors>
             */
            public array $acs_system_errors,
            /**
             * ID of the access system.
             */
            public string|null $acs_system_id,
            /**
             * Warnings associated with the access control system.
             *
             * @var list<\Seam\Resources\Event\AcsSystemDisconnected\AcsSystemWarnings>
             */
            public array $acs_system_warnings,
            /**
             * Errors associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\AcsSystemDisconnected\ConnectedAccountErrors>
             */
            public array $connected_account_errors,
            /**
             * Warnings associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\AcsSystemDisconnected\ConnectedAccountWarnings>
             */
            public array $connected_account_warnings,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account.
             */
            public string|null $connected_account_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access system credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was deleted.
     */
    final class AcsCredentialDeleted extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AcsCredentialDeleted|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_credential_id: $json->acs_credential_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected credential.
             */
            public string|null $acs_credential_id,
            /**
             * ID of the access system.
             */
            public string|null $acs_system_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account.
             */
            public string|null $connected_account_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access system credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was issued.
     */
    final class AcsCredentialIssued extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AcsCredentialIssued|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_credential_id: $json->acs_credential_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected credential.
             */
            public string|null $acs_credential_id,
            /**
             * ID of the access system.
             */
            public string|null $acs_system_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account.
             */
            public string|null $connected_account_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access system credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was reissued.
     */
    final class AcsCredentialReissued extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AcsCredentialReissued|null {
            if (!$json) {
                return null;
            }
            return new self(
                acs_credential_id: $json->acs_credential_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected credential.
             */
            public string|null $acs_credential_id,
            /**
             * ID of the access system.
             */
            public string|null $acs_system_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account.
             */
            public string|null $connected_account_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access system credential](https://docs.seam.co/low-level-apis/access-systems/managing-credentials) was invalidated. That is, the credential cannot be used anymore.
     */
    final class AcsCredentialInvalidated extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AcsCredentialInvalidated|null {
            if (!$json) {
                return null;
            }
            return new self(
                acs_credential_id: $json->acs_credential_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected credential.
             */
            public string|null $acs_credential_id,
            /**
             * ID of the access system.
             */
            public string|null $acs_system_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account.
             */
            public string|null $connected_account_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) was created.
     */
    final class AcsUserCreated extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AcsUserCreated|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_system_id: $json->acs_system_id ?? null,
                acs_user_id: $json->acs_user_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the access system.
             */
            public string|null $acs_system_id,
            /**
             * ID of the affected access system user.
             */
            public string|null $acs_user_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account.
             */
            public string|null $connected_account_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access system user](https://docs.seam.co/low-level-apis/access-systems/user-management) was deleted.
     */
    final class AcsUserDeleted extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AcsUserDeleted|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_system_id: $json->acs_system_id ?? null,
                acs_user_id: $json->acs_user_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the access system.
             */
            public string|null $acs_system_id,
            /**
             * ID of the affected access system user.
             */
            public string|null $acs_user_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account.
             */
            public string|null $connected_account_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access system encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners) was added.
     */
    final class AcsEncoderAdded extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AcsEncoderAdded|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_encoder_id: $json->acs_encoder_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected encoder.
             */
            public string|null $acs_encoder_id,
            /**
             * ID of the access system.
             */
            public string|null $acs_system_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account.
             */
            public string|null $connected_account_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access system encoder](https://docs.seam.co/low-level-apis/access-systems/working-with-card-encoders-and-scanners) was removed.
     */
    final class AcsEncoderRemoved extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AcsEncoderRemoved|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_encoder_id: $json->acs_encoder_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected encoder.
             */
            public string|null $acs_encoder_id,
            /**
             * ID of the access system.
             */
            public string|null $acs_system_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account.
             */
            public string|null $connected_account_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An ACS access group was deleted.
     */
    final class AcsAccessGroupDeleted extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): AcsAccessGroupDeleted|null {
            if (!$json) {
                return null;
            }
            return new self(
                acs_access_group_id: $json->acs_access_group_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected access group.
             */
            public string|null $acs_access_group_id,
            /**
             * ID of the access system.
             */
            public string|null $acs_system_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account.
             */
            public string|null $connected_account_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access system entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) was added.
     */
    final class AcsEntranceAdded extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AcsEntranceAdded|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_entrance_id: $json->acs_entrance_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected entrance.
             */
            public string|null $acs_entrance_id,
            /**
             * ID of the access system.
             */
            public string|null $acs_system_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account.
             */
            public string|null $connected_account_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [access system entrance](https://docs.seam.co/low-level-apis/access-systems/retrieving-entrance-details) was removed.
     */
    final class AcsEntranceRemoved extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): AcsEntranceRemoved|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_entrance_id: $json->acs_entrance_id ?? null,
                acs_system_id: $json->acs_system_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected entrance.
             */
            public string|null $acs_entrance_id,
            /**
             * ID of the access system.
             */
            public string|null $acs_system_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account.
             */
            public string|null $connected_account_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A client session was deleted.
     */
    final class ClientSessionDeleted extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): ClientSessionDeleted|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                client_session_id: $json->client_session_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected client session.
             */
            public string|null $client_session_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A connected account was connected for the first time or was reconnected after being disconnected.
     */
    final class ConnectedAccountConnected extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountConnected|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connect_webview_id: $json->connect_webview_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected connected account.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the Connect Webview associated with the event.
             */
            public string|null $connect_webview_id = null,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with this connected account, if any.
             */
            public string|null $customer_key = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A connected account was created.
     */
    final class ConnectedAccountCreated extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountCreated|null {
            if (!$json) {
                return null;
            }
            return new self(
                connect_webview_id: $json->connect_webview_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the Connect Webview associated with the event.
             */
            public string|null $connect_webview_id,
            /**
             * ID of the affected connected account.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A connected account had a successful login using a Connect Webview.
     */
    final class ConnectedAccountSuccessfulLogin extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountSuccessfulLogin|null {
            if (!$json) {
                return null;
            }
            return new self(
                connect_webview_id: $json->connect_webview_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the Connect Webview associated with the event.
             */
            public string|null $connect_webview_id,
            /**
             * ID of the affected connected account.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A connected account was disconnected.
     */
    final class ConnectedAccountDisconnected extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountDisconnected|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_errors: \Seam\Parse::to_list(
                    $json->connected_account_errors ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\ConnectedAccountDisconnected\ConnectedAccountErrors::from_json(
                        $c,
                    ),
                ),
                connected_account_id: $json->connected_account_id ?? null,
                connected_account_warnings: \Seam\Parse::to_list(
                    $json->connected_account_warnings ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\ConnectedAccountDisconnected\ConnectedAccountWarnings::from_json(
                        $c,
                    ),
                ),
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Errors associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\ConnectedAccountDisconnected\ConnectedAccountErrors>
             */
            public array $connected_account_errors,
            /**
             * ID of the affected connected account.
             */
            public string|null $connected_account_id,
            /**
             * Warnings associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\ConnectedAccountDisconnected\ConnectedAccountWarnings>
             */
            public array $connected_account_warnings,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A connected account completed the first sync with Seam, and the corresponding devices or systems are now available.
     */
    final class ConnectedAccountCompletedFirstSync extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountCompletedFirstSync|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected connected account.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A connected account was deleted.
     */
    final class ConnectedAccountDeleted extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountDeleted|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected connected account.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with this connected account, if any.
             */
            public string|null $customer_key = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A connected account completed the first sync after reconnection with Seam, and the corresponding devices or systems are now available.
     */
    final class ConnectedAccountCompletedFirstSyncAfterReconnection extends
        \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountCompletedFirstSyncAfterReconnection|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected connected account.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A connected account requires reauthorization using a new Connect Webview. The account is still connected, but cannot access new features. Delaying reauthorization too long will eventually cause the Connected Account to become disconnected.
     */
    final class ConnectedAccountReauthorizationRequested extends
        \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountReauthorizationRequested|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_errors: \Seam\Parse::to_list(
                    $json->connected_account_errors ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\ConnectedAccountReauthorizationRequested\ConnectedAccountErrors::from_json(
                        $c,
                    ),
                ),
                connected_account_id: $json->connected_account_id ?? null,
                connected_account_warnings: \Seam\Parse::to_list(
                    $json->connected_account_warnings ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\ConnectedAccountReauthorizationRequested\ConnectedAccountWarnings::from_json(
                        $c,
                    ),
                ),
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Errors associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\ConnectedAccountReauthorizationRequested\ConnectedAccountErrors>
             */
            public array $connected_account_errors,
            /**
             * ID of the affected connected account.
             */
            public string|null $connected_account_id,
            /**
             * Warnings associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\ConnectedAccountReauthorizationRequested\ConnectedAccountWarnings>
             */
            public array $connected_account_warnings,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A lock door action attempt succeeded.
     */
    final class ActionAttemptLockDoorSucceeded extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ActionAttemptLockDoorSucceeded|null {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                status: $json->status ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                device_id: $json->device_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected action attempt.
             */
            public string|null $action_attempt_id,
            /**
             * Type of the action.
             */
            public string|null $action_type,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * Status of the action.
             */
            public string|null $status,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account associated with the action attempt, if applicable.
             */
            public string|null $connected_account_id = null,
            /**
             * ID of the device associated with the action attempt, if applicable.
             */
            public string|null $device_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A lock door action attempt failed.
     */
    final class ActionAttemptLockDoorFailed extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ActionAttemptLockDoorFailed|null {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                status: $json->status ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                device_id: $json->device_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected action attempt.
             */
            public string|null $action_attempt_id,
            /**
             * Type of the action.
             */
            public string|null $action_type,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * Status of the action.
             */
            public string|null $status,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account associated with the action attempt, if applicable.
             */
            public string|null $connected_account_id = null,
            /**
             * ID of the device associated with the action attempt, if applicable.
             */
            public string|null $device_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An unlock door action attempt succeeded.
     */
    final class ActionAttemptUnlockDoorSucceeded extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ActionAttemptUnlockDoorSucceeded|null {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                status: $json->status ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                device_id: $json->device_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected action attempt.
             */
            public string|null $action_attempt_id,
            /**
             * Type of the action.
             */
            public string|null $action_type,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * Status of the action.
             */
            public string|null $status,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account associated with the action attempt, if applicable.
             */
            public string|null $connected_account_id = null,
            /**
             * ID of the device associated with the action attempt, if applicable.
             */
            public string|null $device_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An unlock door action attempt failed.
     */
    final class ActionAttemptUnlockDoorFailed extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ActionAttemptUnlockDoorFailed|null {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                status: $json->status ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                device_id: $json->device_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected action attempt.
             */
            public string|null $action_attempt_id,
            /**
             * Type of the action.
             */
            public string|null $action_type,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * Status of the action.
             */
            public string|null $status,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account associated with the action attempt, if applicable.
             */
            public string|null $connected_account_id = null,
            /**
             * ID of the device associated with the action attempt, if applicable.
             */
            public string|null $device_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A simulate keypad code entry action attempt succeeded.
     */
    final class ActionAttemptSimulateKeypadCodeEntrySucceeded extends
        \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ActionAttemptSimulateKeypadCodeEntrySucceeded|null {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                status: $json->status ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                device_id: $json->device_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected action attempt.
             */
            public string|null $action_attempt_id,
            /**
             * Type of the action.
             */
            public string|null $action_type,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * Status of the action.
             */
            public string|null $status,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account associated with the action attempt, if applicable.
             */
            public string|null $connected_account_id = null,
            /**
             * ID of the device associated with the action attempt, if applicable.
             */
            public string|null $device_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A simulate keypad code entry action attempt failed.
     */
    final class ActionAttemptSimulateKeypadCodeEntryFailed extends
        \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ActionAttemptSimulateKeypadCodeEntryFailed|null {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                status: $json->status ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                device_id: $json->device_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected action attempt.
             */
            public string|null $action_attempt_id,
            /**
             * Type of the action.
             */
            public string|null $action_type,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * Status of the action.
             */
            public string|null $status,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account associated with the action attempt, if applicable.
             */
            public string|null $connected_account_id = null,
            /**
             * ID of the device associated with the action attempt, if applicable.
             */
            public string|null $device_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A simulate manual lock via keypad action attempt succeeded.
     */
    final class ActionAttemptSimulateManualLockViaKeypadSucceeded extends
        \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ActionAttemptSimulateManualLockViaKeypadSucceeded|null {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                status: $json->status ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                device_id: $json->device_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected action attempt.
             */
            public string|null $action_attempt_id,
            /**
             * Type of the action.
             */
            public string|null $action_type,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * Status of the action.
             */
            public string|null $status,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account associated with the action attempt, if applicable.
             */
            public string|null $connected_account_id = null,
            /**
             * ID of the device associated with the action attempt, if applicable.
             */
            public string|null $device_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A simulate manual lock via keypad action attempt failed.
     */
    final class ActionAttemptSimulateManualLockViaKeypadFailed extends
        \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ActionAttemptSimulateManualLockViaKeypadFailed|null {
            if (!$json) {
                return null;
            }
            return new self(
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                status: $json->status ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                device_id: $json->device_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected action attempt.
             */
            public string|null $action_attempt_id,
            /**
             * Type of the action.
             */
            public string|null $action_type,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * Status of the action.
             */
            public string|null $status,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the connected account associated with the action attempt, if applicable.
             */
            public string|null $connected_account_id = null,
            /**
             * ID of the device associated with the action attempt, if applicable.
             */
            public string|null $device_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A Connect Webview login succeeded.
     */
    final class ConnectWebviewLoginSucceeded extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ConnectWebviewLoginSucceeded|null {
            if (!$json) {
                return null;
            }
            return new self(
                connect_webview_id: $json->connect_webview_id ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected Connect Webview.
             */
            public string|null $connect_webview_id,
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account; present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with this connect webview, if any.
             */
            public string|null $customer_key = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A Connect Webview login failed.
     */
    final class ConnectWebviewLoginFailed extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ConnectWebviewLoginFailed|null {
            if (!$json) {
                return null;
            }
            return new self(
                connect_webview_id: $json->connect_webview_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the affected Connect Webview.
             */
            public string|null $connect_webview_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * The status of a device changed from offline to online. That is, the `device.properties.online` property changed from `false` to `true`. Note that some devices operate entirely in offline mode, so Seam never emits a `device.connected` event for these devices.
     */
    final class DeviceConnected extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): DeviceConnected|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A device was added to Seam or was re-added to Seam after having been removed.
     */
    final class DeviceAdded extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): DeviceAdded|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A managed device was successfully converted to an [unmanaged device](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices).
     */
    final class DeviceConvertedToUnmanaged extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): DeviceConvertedToUnmanaged|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An [unmanaged device](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices) was successfully converted to a managed device.
     */
    final class DeviceUnmanagedConvertedToManaged extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): DeviceUnmanagedConvertedToManaged|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * The status of an [unmanaged device](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices) changed from offline to online. That is, the `device.properties.online` property changed from `false` to `true`.
     */
    final class DeviceUnmanagedConnected extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): DeviceUnmanagedConnected|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * The status of a device changed from online to offline. That is, the `device.properties.online` property changed from `true` to `false`.
     */
    final class DeviceDisconnected extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): DeviceDisconnected|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_errors: \Seam\Parse::to_list(
                    $json->connected_account_errors ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\DeviceDisconnected\ConnectedAccountErrors::from_json(
                        $c,
                    ),
                ),
                connected_account_id: $json->connected_account_id ?? null,
                connected_account_warnings: \Seam\Parse::to_list(
                    $json->connected_account_warnings ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\DeviceDisconnected\ConnectedAccountWarnings::from_json(
                        $c,
                    ),
                ),
                created_at: $json->created_at ?? null,
                device_errors: \Seam\Parse::to_list(
                    $json->device_errors ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\DeviceDisconnected\DeviceErrors::from_json(
                        $d,
                    ),
                ),
                device_id: $json->device_id ?? null,
                device_warnings: \Seam\Parse::to_list(
                    $json->device_warnings ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\DeviceDisconnected\DeviceWarnings::from_json(
                        $d,
                    ),
                ),
                error_code: $json->error_code ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Errors associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\DeviceDisconnected\ConnectedAccountErrors>
             */
            public array $connected_account_errors,
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Warnings associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\DeviceDisconnected\ConnectedAccountWarnings>
             */
            public array $connected_account_warnings,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * Errors associated with the device.
             *
             * @var list<\Seam\Resources\Event\DeviceDisconnected\DeviceErrors>
             */
            public array $device_errors,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * Warnings associated with the device.
             *
             * @var list<\Seam\Resources\Event\DeviceDisconnected\DeviceWarnings>
             */
            public array $device_warnings,
            /**
             * Error code associated with the disconnection event, if any.
             *
             * @var value-of<\Seam\Resources\Event\DeviceDisconnected\ErrorCode>|string|null
             */
            public string|null $error_code,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * The status of an [unmanaged device](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices) changed from online to offline. That is, the `device.properties.online` property changed from `true` to `false`.
     */
    final class DeviceUnmanagedDisconnected extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): DeviceUnmanagedDisconnected|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_errors: \Seam\Parse::to_list(
                    $json->connected_account_errors ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\DeviceUnmanagedDisconnected\ConnectedAccountErrors::from_json(
                        $c,
                    ),
                ),
                connected_account_id: $json->connected_account_id ?? null,
                connected_account_warnings: \Seam\Parse::to_list(
                    $json->connected_account_warnings ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\DeviceUnmanagedDisconnected\ConnectedAccountWarnings::from_json(
                        $c,
                    ),
                ),
                created_at: $json->created_at ?? null,
                device_errors: \Seam\Parse::to_list(
                    $json->device_errors ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\DeviceUnmanagedDisconnected\DeviceErrors::from_json(
                        $d,
                    ),
                ),
                device_id: $json->device_id ?? null,
                device_warnings: \Seam\Parse::to_list(
                    $json->device_warnings ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\DeviceUnmanagedDisconnected\DeviceWarnings::from_json(
                        $d,
                    ),
                ),
                error_code: $json->error_code ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Errors associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\DeviceUnmanagedDisconnected\ConnectedAccountErrors>
             */
            public array $connected_account_errors,
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Warnings associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\DeviceUnmanagedDisconnected\ConnectedAccountWarnings>
             */
            public array $connected_account_warnings,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * Errors associated with the device.
             *
             * @var list<\Seam\Resources\Event\DeviceUnmanagedDisconnected\DeviceErrors>
             */
            public array $device_errors,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * Warnings associated with the device.
             *
             * @var list<\Seam\Resources\Event\DeviceUnmanagedDisconnected\DeviceWarnings>
             */
            public array $device_warnings,
            /**
             * Error code associated with the disconnection event, if any.
             *
             * @var value-of<\Seam\Resources\Event\DeviceUnmanagedDisconnected\ErrorCode>|string|null
             */
            public string|null $error_code,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A device detected that it was tampered with, for example, opened or moved.
     */
    final class DeviceTampered extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): DeviceTampered|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A device battery level dropped below the low threshold.
     */
    final class DeviceLowBattery extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): DeviceLowBattery|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                battery_level: $json->battery_level ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                accessory_keypad_battery_level: $json->accessory_keypad_battery_level ??
                    null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_battery_level: $json->device_battery_level ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Number in the range 0 to 1.0 indicating the level of the battery whose drop triggered this event.
             *
             * @deprecated Use device_battery_level and accessory_keypad_battery_level, which distinguish the device's own battery from a paired accessory keypad's battery.
             */
            public float|null $battery_level,
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Number in the range 0 to 1.0 indicating the battery level of the affected device's paired accessory keypad, when the device has one and its level is known.
             */
            public float|null $accessory_keypad_battery_level = null,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Number in the range 0 to 1.0 indicating the affected device's own battery level, when known.
             */
            public float|null $device_battery_level = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A device battery status changed since the last `battery_status_changed` event.
     */
    final class DeviceBatteryStatusChanged extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): DeviceBatteryStatusChanged|null {
            if (!$json) {
                return null;
            }
            return new self(
                battery_level: $json->battery_level ?? null,
                battery_status: $json->battery_status ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Number in the range 0 to 1.0 indicating the amount of battery in the affected device, as reported by the device.
             */
            public float|null $battery_level,
            /**
             * Battery status of the affected device, calculated from the numeric `battery_level` value.
             *
             * @var value-of<\Seam\Resources\Event\DeviceBatteryStatusChanged\BatteryStatus>|string|null
             */
            public string|null $battery_status,
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A device was removed externally from the connected account.
     */
    final class DeviceRemoved extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): DeviceRemoved|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A device was deleted.
     */
    final class DeviceDeleted extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): DeviceDeleted|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                device_name: $json->device_name ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Name of the deleted device, captured at deletion time. The device record no longer exists when this event fires, so the name is preserved here. Null when the device had no resolvable name.
             */
            public string|null $device_name = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * Seam detected that a device is using a third-party integration that will interfere with Seam device management.
     */
    final class DeviceThirdPartyIntegrationDetected extends
        \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): DeviceThirdPartyIntegrationDetected|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * Seam detected that a device is no longer using a third-party integration that was interfering with Seam device management.
     */
    final class DeviceThirdPartyIntegrationNoLongerDetected extends
        \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): DeviceThirdPartyIntegrationNoLongerDetected|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A [Salto device](https://docs.seam.co/device-and-system-integration-guides/salto-locks) activated privacy mode.
     */
    final class DeviceSaltoPrivacyModeActivated extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): DeviceSaltoPrivacyModeActivated|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A [Salto device](https://docs.seam.co/device-and-system-integration-guides/salto-locks) deactivated privacy mode.
     */
    final class DeviceSaltoPrivacyModeDeactivated extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): DeviceSaltoPrivacyModeDeactivated|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * Seam detected a flaky device connection.
     */
    final class DeviceConnectionBecameFlaky extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): DeviceConnectionBecameFlaky|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_errors: \Seam\Parse::to_list(
                    $json->connected_account_errors ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\DeviceConnectionBecameFlaky\ConnectedAccountErrors::from_json(
                        $c,
                    ),
                ),
                connected_account_id: $json->connected_account_id ?? null,
                connected_account_warnings: \Seam\Parse::to_list(
                    $json->connected_account_warnings ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\DeviceConnectionBecameFlaky\ConnectedAccountWarnings::from_json(
                        $c,
                    ),
                ),
                created_at: $json->created_at ?? null,
                device_errors: \Seam\Parse::to_list(
                    $json->device_errors ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\DeviceConnectionBecameFlaky\DeviceErrors::from_json(
                        $d,
                    ),
                ),
                device_id: $json->device_id ?? null,
                device_warnings: \Seam\Parse::to_list(
                    $json->device_warnings ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\DeviceConnectionBecameFlaky\DeviceWarnings::from_json(
                        $d,
                    ),
                ),
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Errors associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\DeviceConnectionBecameFlaky\ConnectedAccountErrors>
             */
            public array $connected_account_errors,
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Warnings associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\DeviceConnectionBecameFlaky\ConnectedAccountWarnings>
             */
            public array $connected_account_warnings,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * Errors associated with the device.
             *
             * @var list<\Seam\Resources\Event\DeviceConnectionBecameFlaky\DeviceErrors>
             */
            public array $device_errors,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * Warnings associated with the device.
             *
             * @var list<\Seam\Resources\Event\DeviceConnectionBecameFlaky\DeviceWarnings>
             */
            public array $device_warnings,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * Seam detected that a previously-flaky device connection stabilized.
     */
    final class DeviceConnectionStabilized extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): DeviceConnectionStabilized|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A third-party subscription is required to use all device features.
     */
    final class DeviceErrorSubscriptionRequired extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): DeviceErrorSubscriptionRequired|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_errors: \Seam\Parse::to_list(
                    $json->connected_account_errors ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\DeviceErrorSubscriptionRequired\ConnectedAccountErrors::from_json(
                        $c,
                    ),
                ),
                connected_account_id: $json->connected_account_id ?? null,
                connected_account_warnings: \Seam\Parse::to_list(
                    $json->connected_account_warnings ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\DeviceErrorSubscriptionRequired\ConnectedAccountWarnings::from_json(
                        $c,
                    ),
                ),
                created_at: $json->created_at ?? null,
                device_errors: \Seam\Parse::to_list(
                    $json->device_errors ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\DeviceErrorSubscriptionRequired\DeviceErrors::from_json(
                        $d,
                    ),
                ),
                device_id: $json->device_id ?? null,
                device_warnings: \Seam\Parse::to_list(
                    $json->device_warnings ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\DeviceErrorSubscriptionRequired\DeviceWarnings::from_json(
                        $d,
                    ),
                ),
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Errors associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\DeviceErrorSubscriptionRequired\ConnectedAccountErrors>
             */
            public array $connected_account_errors,
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Warnings associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\DeviceErrorSubscriptionRequired\ConnectedAccountWarnings>
             */
            public array $connected_account_warnings,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * Errors associated with the device.
             *
             * @var list<\Seam\Resources\Event\DeviceErrorSubscriptionRequired\DeviceErrors>
             */
            public array $device_errors,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * Warnings associated with the device.
             *
             * @var list<\Seam\Resources\Event\DeviceErrorSubscriptionRequired\DeviceWarnings>
             */
            public array $device_warnings,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A third-party subscription is active or no longer required to use all device features.
     */
    final class DeviceErrorSubscriptionRequiredResolved extends
        \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): DeviceErrorSubscriptionRequiredResolved|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An accessory keypad was connected to a device.
     */
    final class DeviceAccessoryKeypadConnected extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): DeviceAccessoryKeypadConnected|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * An accessory keypad was disconnected from a device.
     */
    final class DeviceAccessoryKeypadDisconnected extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): DeviceAccessoryKeypadDisconnected|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_errors: \Seam\Parse::to_list(
                    $json->connected_account_errors ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\DeviceAccessoryKeypadDisconnected\ConnectedAccountErrors::from_json(
                        $c,
                    ),
                ),
                connected_account_id: $json->connected_account_id ?? null,
                connected_account_warnings: \Seam\Parse::to_list(
                    $json->connected_account_warnings ?? null,
                    fn(
                        $c,
                    ) => \Seam\Resources\Event\DeviceAccessoryKeypadDisconnected\ConnectedAccountWarnings::from_json(
                        $c,
                    ),
                ),
                created_at: $json->created_at ?? null,
                device_errors: \Seam\Parse::to_list(
                    $json->device_errors ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\DeviceAccessoryKeypadDisconnected\DeviceErrors::from_json(
                        $d,
                    ),
                ),
                device_id: $json->device_id ?? null,
                device_warnings: \Seam\Parse::to_list(
                    $json->device_warnings ?? null,
                    fn(
                        $d,
                    ) => \Seam\Resources\Event\DeviceAccessoryKeypadDisconnected\DeviceWarnings::from_json(
                        $d,
                    ),
                ),
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Errors associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\DeviceAccessoryKeypadDisconnected\ConnectedAccountErrors>
             */
            public array $connected_account_errors,
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Warnings associated with the connected account.
             *
             * @var list<\Seam\Resources\Event\DeviceAccessoryKeypadDisconnected\ConnectedAccountWarnings>
             */
            public array $connected_account_warnings,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * Errors associated with the device.
             *
             * @var list<\Seam\Resources\Event\DeviceAccessoryKeypadDisconnected\DeviceErrors>
             */
            public array $device_errors,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * Warnings associated with the device.
             *
             * @var list<\Seam\Resources\Event\DeviceAccessoryKeypadDisconnected\DeviceWarnings>
             */
            public array $device_warnings,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * Extended periods of noise or noise exceeding a [threshold](https://docs.seam.co/capability-guides/noise-sensors#what-is-a-threshold) were detected.
     */
    final class NoiseSensorNoiseThresholdTriggered extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): NoiseSensorNoiseThresholdTriggered|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
                minut_metadata: $json->minut_metadata ?? null,
                noise_level_decibels: $json->noise_level_decibels ?? null,
                noise_level_nrs: $json->noise_level_nrs ?? null,
                noise_threshold_id: $json->noise_threshold_id ?? null,
                noise_threshold_name: $json->noise_threshold_name ?? null,
                noiseaware_metadata: $json->noiseaware_metadata ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
            /**
             * Metadata from Minut.
             *
             * @var array<string, mixed>|\stdClass|null
             */
            public array|\stdClass|null $minut_metadata = null,
            /**
             * Detected noise level in decibels.
             */
            public float|null $noise_level_decibels = null,
            /**
             * Detected noise level in Noiseaware Noise Risk Score (NRS).
             */
            public float|null $noise_level_nrs = null,
            /**
             * ID of the noise threshold that was triggered.
             */
            public string|null $noise_threshold_id = null,
            /**
             * Name of the noise threshold that was triggered.
             */
            public string|null $noise_threshold_name = null,
            /**
             * Metadata from Noiseaware.
             *
             * @var array<string, mixed>|\stdClass|null
             */
            public array|\stdClass|null $noiseaware_metadata = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A [lock](https://docs.seam.co/low-level-apis/smart-locks) was locked.
     */
    final class LockLocked extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): LockLocked|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                method: $json->method ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                access_code_id: $json->access_code_id ?? null,
                access_code_is_managed: $json->access_code_is_managed ?? null,
                action_attempt_id: $json->action_attempt_id ?? null,
                code: $json->code ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
                is_via_bluetooth: $json->is_via_bluetooth ?? null,
                is_via_nfc: $json->is_via_nfc ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Method by which the lock was locked. `keycode`: an access code was used (see `access_code_id`). `manual`: a physical action such as a thumbturn or button press. `remote`: a remote action via an app, Bluetooth, or the Seam API (see `action_attempt_id` if Seam-initiated; see `is_via_bluetooth` or `is_via_nfc` for the transport). `automatic`: triggered automatically, for example by an auto-relock timer. `unknown`: could not be determined.
             *
             * @var value-of<\Seam\Resources\Event\LockLocked\Method>|string|null
             */
            public string|null $method,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the access code that was used to lock the device.
             */
            public string|null $access_code_id = null,
            /**
             * Whether the access code is managed by Seam (true) or unmanaged (false). Only present when access_code_id is set.
             */
            public bool|null $access_code_is_managed = null,
            /**
             * ID of the Seam action attempt that triggered this lock. Present only when the lock was initiated through Seam (via a `LOCK_DOOR` action attempt).
             */
            public string|null $action_attempt_id = null,
            /**
             * Code (PIN) that was used to lock the device, if known. Taken from the matched managed or unmanaged access code, or from the code reported by the provider when no access code matched.
             */
            public string|null $code = null,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
            /**
             * Whether the lock action was performed over Bluetooth by a remote client (such as the provider's mobile app), rather than a direct physical interaction or a Seam-initiated remote action.
             */
            public bool|null $is_via_bluetooth = null,
            /**
             * Whether the lock action was performed by an NFC credential tap (such as an Apple Home Key or an NFC key fob) presented to the lock, rather than a direct physical interaction or a Seam-initiated remote action.
             */
            public bool|null $is_via_nfc = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A [lock](https://docs.seam.co/low-level-apis/smart-locks) was unlocked.
     */
    final class LockUnlocked extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): LockUnlocked|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                method: $json->method ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                access_code_id: $json->access_code_id ?? null,
                access_code_is_managed: $json->access_code_is_managed ?? null,
                action_attempt_id: $json->action_attempt_id ?? null,
                code: $json->code ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                device_id: $json->device_id ?? null,
                event_description: $json->event_description ?? null,
                is_via_bluetooth: $json->is_via_bluetooth ?? null,
                is_via_nfc: $json->is_via_nfc ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Method by which the lock was unlocked. `keycode`: an [access code](https://docs.seam.co/low-level-apis/smart-locks/access-codes) was used (see `access_code_id`). `manual`: a physical action such as a thumbturn or handle press. `remote`: a remote action via an app, Bluetooth, or the Seam API (see `action_attempt_id` if Seam-initiated; see `is_via_bluetooth` or `is_via_nfc` for the transport). `automatic`: triggered automatically, for example by a time-based schedule. `unknown`: could not be determined.
             *
             * @var value-of<\Seam\Resources\Event\LockUnlocked\Method>|string|null
             */
            public string|null $method,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the access code that was used to unlock the affected device.
             */
            public string|null $access_code_id = null,
            /**
             * Whether the access code is managed by Seam (true) or unmanaged (false). Only present when access_code_id is set.
             */
            public bool|null $access_code_is_managed = null,
            /**
             * ID of the Seam action attempt that triggered this unlock. Present only when the unlock was initiated through Seam (via an `UNLOCK_DOOR` action attempt).
             */
            public string|null $action_attempt_id = null,
            /**
             * Code (PIN) that was used to unlock the affected device, if known. Taken from the matched managed or unmanaged access code, or from the code reported by the provider when no access code matched.
             */
            public string|null $code = null,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * ID of the affected device.
             */
            public string|null $device_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
            /**
             * Whether the unlock action was performed over Bluetooth by a remote client (such as the provider's mobile app), rather than a direct physical interaction or a Seam-initiated remote action.
             */
            public bool|null $is_via_bluetooth = null,
            /**
             * Whether the unlock action was performed by an NFC credential tap (such as an Apple Home Key or an NFC key fob) presented to the lock, rather than a direct physical interaction or a Seam-initiated remote action.
             */
            public bool|null $is_via_nfc = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * The [lock](https://docs.seam.co/low-level-apis/smart-locks) denied access to a user after one or more consecutive invalid attempts to unlock the device.
     */
    final class LockAccessDenied extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): LockAccessDenied|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                access_code_id: $json->access_code_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                device_id: $json->device_id ?? null,
                event_description: $json->event_description ?? null,
                reason: isset($json->reason)
                    ? \Seam\Resources\Event\LockAccessDenied\Reason::from_json(
                        $json->reason,
                    )
                    : null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * ID of the access code that was used in the unlock attempts.
             */
            public string|null $access_code_id = null,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * ID of the affected device.
             */
            public string|null $device_id = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
            /**
             * Why access was denied, when the provider reports a determinable cause. Omitted when unknown.
             */
            public \Seam\Resources\Event\LockAccessDenied\Reason|null $reason = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A thermostat [climate preset](https://docs.seam.co/capability-guides/thermostats/creating-and-managing-climate-presets) was activated.
     */
    final class ThermostatClimatePresetActivated extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ThermostatClimatePresetActivated|null {
            if (!$json) {
                return null;
            }
            return new self(
                climate_preset_key: $json->climate_preset_key ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                is_fallback_climate_preset: $json->is_fallback_climate_preset ??
                    null,
                occurred_at: $json->occurred_at ?? null,
                thermostat_schedule_id: $json->thermostat_schedule_id ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Key of the climate preset that was activated.
             */
            public string|null $climate_preset_key,
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Indicates whether the climate preset that was activated is the fallback climate preset for the thermostat.
             */
            public bool|null $is_fallback_climate_preset,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the thermostat schedule that prompted the affected climate preset to be activated.
             */
            public string|null $thermostat_schedule_id,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A [thermostat](https://docs.seam.co/capability-guides/thermostats) was adjusted manually.
     */
    final class ThermostatManuallyAdjusted extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ThermostatManuallyAdjusted|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                method: $json->method ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                cooling_set_point_celsius: $json->cooling_set_point_celsius ??
                    null,
                cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
                fan_mode_setting: $json->fan_mode_setting ?? null,
                heating_set_point_celsius: $json->heating_set_point_celsius ??
                    null,
                heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ??
                    null,
                hvac_mode_setting: $json->hvac_mode_setting ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Method used to adjust the affected thermostat manually. `seam` indicates that the Seam API, Seam CLI, or Seam Console was used to adjust the thermostat.
             *
             * @var value-of<\Seam\Resources\Event\ThermostatManuallyAdjusted\Method>|string|null
             */
            public string|null $method,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * Temperature to which the thermostat should cool (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $cooling_set_point_celsius = null,
            /**
             * Temperature to which the thermostat should cool (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $cooling_set_point_fahrenheit = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
            /**
             * Desired [fan mode setting](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings#fan-mode-settings), such as `on`, `auto`, or `circulate`.
             *
             * @var value-of<\Seam\Resources\Event\ThermostatManuallyAdjusted\FanModeSetting>|string|null
             */
            public string|null $fan_mode_setting = null,
            /**
             * Temperature to which the thermostat should heat (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $heating_set_point_celsius = null,
            /**
             * Temperature to which the thermostat should heat (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $heating_set_point_fahrenheit = null,
            /**
             * Desired [HVAC mode](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/hvac-mode) setting, such as `heat`, `cool`, `heat_cool`, or `off`.
             *
             * @var value-of<\Seam\Resources\Event\ThermostatManuallyAdjusted\HvacModeSetting>|string|null
             */
            public string|null $hvac_mode_setting = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A [thermostat's](https://docs.seam.co/capability-guides/thermostats) temperature reading exceeded the set [threshold](https://docs.seam.co/capability-guides/thermostats/setting-and-monitoring-temperature-thresholds).
     */
    final class ThermostatTemperatureThresholdExceeded extends
        \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ThermostatTemperatureThresholdExceeded|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                lower_limit_celsius: $json->lower_limit_celsius ?? null,
                lower_limit_fahrenheit: $json->lower_limit_fahrenheit ?? null,
                occurred_at: $json->occurred_at ?? null,
                temperature_celsius: $json->temperature_celsius ?? null,
                temperature_fahrenheit: $json->temperature_fahrenheit ?? null,
                upper_limit_celsius: $json->upper_limit_celsius ?? null,
                upper_limit_fahrenheit: $json->upper_limit_fahrenheit ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Lower temperature limit, in °C, defined by the set threshold.
             */
            public float|null $lower_limit_celsius,
            /**
             * Lower temperature limit, in °F, defined by the set threshold.
             */
            public float|null $lower_limit_fahrenheit,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * Temperature, in °C, reported by the affected thermostat.
             */
            public float|null $temperature_celsius,
            /**
             * Temperature, in °F, reported by the affected thermostat.
             */
            public float|null $temperature_fahrenheit,
            /**
             * Upper temperature limit, in °C, defined by the set threshold.
             */
            public float|null $upper_limit_celsius,
            /**
             * Upper temperature limit, in °F, defined by the set threshold.
             */
            public float|null $upper_limit_fahrenheit,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A [thermostat's](https://docs.seam.co/capability-guides/thermostats) temperature reading no longer exceeds the set [threshold](https://docs.seam.co/capability-guides/thermostats/setting-and-monitoring-temperature-thresholds).
     */
    final class ThermostatTemperatureThresholdNoLongerExceeded extends
        \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ThermostatTemperatureThresholdNoLongerExceeded|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                lower_limit_celsius: $json->lower_limit_celsius ?? null,
                lower_limit_fahrenheit: $json->lower_limit_fahrenheit ?? null,
                occurred_at: $json->occurred_at ?? null,
                temperature_celsius: $json->temperature_celsius ?? null,
                temperature_fahrenheit: $json->temperature_fahrenheit ?? null,
                upper_limit_celsius: $json->upper_limit_celsius ?? null,
                upper_limit_fahrenheit: $json->upper_limit_fahrenheit ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Lower temperature limit, in °C, defined by the set threshold.
             */
            public float|null $lower_limit_celsius,
            /**
             * Lower temperature limit, in °F, defined by the set threshold.
             */
            public float|null $lower_limit_fahrenheit,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * Temperature, in °C, reported by the affected thermostat.
             */
            public float|null $temperature_celsius,
            /**
             * Temperature, in °F, reported by the affected thermostat.
             */
            public float|null $temperature_fahrenheit,
            /**
             * Upper temperature limit, in °C, defined by the set threshold.
             */
            public float|null $upper_limit_celsius,
            /**
             * Upper temperature limit, in °F, defined by the set threshold.
             */
            public float|null $upper_limit_fahrenheit,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A [thermostat's](https://docs.seam.co/capability-guides/thermostats) temperature reading is within 1 °C of the configured cooling or heating [set point](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
     */
    final class ThermostatTemperatureReachedSetPoint extends
        \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ThermostatTemperatureReachedSetPoint|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                temperature_celsius: $json->temperature_celsius ?? null,
                temperature_fahrenheit: $json->temperature_fahrenheit ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                desired_temperature_celsius: $json->desired_temperature_celsius ??
                    null,
                desired_temperature_fahrenheit: $json->desired_temperature_fahrenheit ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * Temperature, in °C, reported by the affected thermostat.
             */
            public float|null $temperature_celsius,
            /**
             * Temperature, in °F, reported by the affected thermostat.
             */
            public float|null $temperature_fahrenheit,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Desired temperature, in °C, defined by the affected thermostat's cooling or heating set point.
             */
            public float|null $desired_temperature_celsius = null,
            /**
             * Desired temperature, in °F, defined by the affected thermostat's cooling or heating set point.
             */
            public float|null $desired_temperature_fahrenheit = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A [thermostat's](https://docs.seam.co/capability-guides/thermostats) reported temperature changed by at least 1 °C.
     */
    final class ThermostatTemperatureChanged extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): ThermostatTemperatureChanged|null {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                temperature_celsius: $json->temperature_celsius ?? null,
                temperature_fahrenheit: $json->temperature_fahrenheit ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * Temperature, in °C, reported by the affected thermostat.
             */
            public float|null $temperature_celsius,
            /**
             * Temperature, in °F, reported by the affected thermostat.
             */
            public float|null $temperature_fahrenheit,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * The name of a device was changed.
     */
    final class DeviceNameChanged extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): DeviceNameChanged|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                device_name: $json->device_name ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * The new name of the affected device.
             */
            public string|null $device_name,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A camera was activated, for example, by motion detection.
     */
    final class CameraActivated extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): CameraActivated|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                activation_reason: $json->activation_reason ?? null,
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
                image_url: $json->image_url ?? null,
                motion_sub_type: $json->motion_sub_type ?? null,
                video_url: $json->video_url ?? null,
            );
        }

        public function __construct(
            /**
             * The reason the camera was activated.
             *
             * @var value-of<\Seam\Resources\Event\CameraActivated\ActivationReason>|string|null
             */
            public string|null $activation_reason,
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
            /**
             * URL to a thumbnail image captured at the time of activation.
             */
            public string|null $image_url = null,
            /**
             * Sub-type of motion detected, if available.
             *
             * @var value-of<\Seam\Resources\Event\CameraActivated\MotionSubType>|string|null
             */
            public string|null $motion_sub_type = null,
            /**
             * URL to a short video clip captured at the time of activation.
             */
            public string|null $video_url = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A doorbell button was pressed on a device.
     */
    final class DeviceDoorbellRang extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): DeviceDoorbellRang|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                connected_account_id: $json->connected_account_id ?? null,
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                customer_key: $json->customer_key ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
                image_url: $json->image_url ?? null,
                video_url: $json->video_url ?? null,
            );
        }

        public function __construct(
            /**
             * ID of the connected account associated with the event.
             */
            public string|null $connected_account_id,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the connected account, present when connected_account_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $connected_account_custom_metadata = null,
            /**
             * The customer key associated with the device, if any.
             */
            public string|null $customer_key = null,
            /**
             * Custom metadata of the device, present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
            /**
             * URL to a thumbnail image captured at the time the doorbell was pressed.
             */
            public string|null $image_url = null,
            /**
             * URL to a short video clip captured at the time the doorbell was pressed.
             */
            public string|null $video_url = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A phone device was deactivated.
     */
    final class PhoneDeactivated extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): PhoneDeactivated|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                device_id: $json->device_id ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                workspace_id: $json->workspace_id ?? null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                event_description: $json->event_description ?? null,
            );
        }

        public function __construct(
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * ID of the affected phone device.
             */
            public string|null $device_id,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Custom metadata of the device; present when device_id is provided.
             *
             * @var array<string, string|bool>|\stdClass|null
             */
            public array|\stdClass|null $device_custom_metadata = null,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A device was added or removed from a space.
     */
    final class SpaceDeviceMembershipChanged extends \Seam\Resources\Event
    {
        public static function from_json(
            mixed $json,
        ): SpaceDeviceMembershipChanged|null {
            if (!$json) {
                return null;
            }
            return new self(
                acs_entrance_ids: $json->acs_entrance_ids ?? null,
                created_at: $json->created_at ?? null,
                device_ids: $json->device_ids ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                space_id: $json->space_id ?? null,
                workspace_id: $json->workspace_id ?? null,
                event_description: $json->event_description ?? null,
                space_key: $json->space_key ?? null,
            );
        }

        public function __construct(
            /**
             * IDs of all ACS entrances currently attached to the space.
             *
             * @var list<string>|null
             */
            public array|null $acs_entrance_ids,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * IDs of all devices currently attached to the space.
             *
             * @var list<string>|null
             */
            public array|null $device_ids,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the affected space.
             */
            public string|null $space_id,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
            /**
             * Unique key for the space within the workspace.
             */
            public string|null $space_key = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A space was created.
     */
    final class SpaceCreated extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): SpaceCreated|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_entrance_ids: $json->acs_entrance_ids ?? null,
                created_at: $json->created_at ?? null,
                device_ids: $json->device_ids ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                space_id: $json->space_id ?? null,
                workspace_id: $json->workspace_id ?? null,
                event_description: $json->event_description ?? null,
                space_key: $json->space_key ?? null,
            );
        }

        public function __construct(
            /**
             * IDs of all ACS entrances attached to the space when it was created.
             *
             * @var list<string>|null
             */
            public array|null $acs_entrance_ids,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * IDs of all devices attached to the space when it was created.
             *
             * @var list<string>|null
             */
            public array|null $device_ids,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the affected space.
             */
            public string|null $space_id,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
            /**
             * Unique key for the space within the workspace.
             */
            public string|null $space_key = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    /**
     * A space was deleted.
     */
    final class SpaceDeleted extends \Seam\Resources\Event
    {
        public static function from_json(mixed $json): SpaceDeleted|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                acs_entrance_ids: $json->acs_entrance_ids ?? null,
                created_at: $json->created_at ?? null,
                device_ids: $json->device_ids ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                occurred_at: $json->occurred_at ?? null,
                space_id: $json->space_id ?? null,
                workspace_id: $json->workspace_id ?? null,
                event_description: $json->event_description ?? null,
                space_key: $json->space_key ?? null,
            );
        }

        public function __construct(
            /**
             * IDs of all ACS entrances currently attached to the space when it was deleted.
             *
             * @var list<string>|null
             */
            public array|null $acs_entrance_ids,
            /**
             * Date and time at which the event was created.
             */
            string|null $created_at,
            /**
             * IDs of all devices attached to the space when it was deleted.
             *
             * @var list<string>|null
             */
            public array|null $device_ids,
            /**
             * ID of the event.
             */
            string|null $event_id,
            /**
             * @var value-of<\Seam\Resources\Event\EventType>|string|null
             */
            string|null $event_type,
            /**
             * Date and time at which the event occurred.
             */
            string|null $occurred_at,
            /**
             * ID of the affected space.
             */
            public string|null $space_id,
            /**
             * ID of the workspace associated with the event.
             */
            string|null $workspace_id,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            string|null $event_description = null,
            /**
             * Unique key for the space within the workspace.
             */
            public string|null $space_key = null,
        ) {
            parent::__construct(
                created_at: $created_at,
                event_description: $event_description,
                event_id: $event_id,
                event_type: $event_type,
                occurred_at: $occurred_at,
                workspace_id: $workspace_id,
            );
        }
    }

    enum EventType: string
    {
        case ACCESS_CODE_CREATED = "access_code.created";
        case ACCESS_CODE_CHANGED = "access_code.changed";
        case ACCESS_CODE_NAME_CHANGED = "access_code.name_changed";
        case ACCESS_CODE_CODE_CHANGED = "access_code.code_changed";
        case ACCESS_CODE_TIME_FRAME_CHANGED = "access_code.time_frame_changed";
        case ACCESS_CODE_MUTATIONS_REQUESTED = "access_code.mutations_requested";
        case ACCESS_CODE_SCHEDULED_ON_DEVICE = "access_code.scheduled_on_device";
        case ACCESS_CODE_SET_ON_DEVICE = "access_code.set_on_device";
        case ACCESS_CODE_REMOVED_FROM_DEVICE = "access_code.removed_from_device";
        case ACCESS_CODE_DELAY_IN_SETTING_ON_DEVICE = "access_code.delay_in_setting_on_device";
        case ACCESS_CODE_FAILED_TO_SET_ON_DEVICE = "access_code.failed_to_set_on_device";
        case ACCESS_CODE_DELETED = "access_code.deleted";
        case ACCESS_CODE_DELAY_IN_REMOVING_FROM_DEVICE = "access_code.delay_in_removing_from_device";
        case ACCESS_CODE_FAILED_TO_REMOVE_FROM_DEVICE = "access_code.failed_to_remove_from_device";
        case ACCESS_CODE_MODIFIED_EXTERNAL_TO_SEAM = "access_code.modified_external_to_seam";
        case ACCESS_CODE_DELETED_EXTERNAL_TO_SEAM = "access_code.deleted_external_to_seam";
        case ACCESS_CODE_BACKUP_ACCESS_CODE_PULLED = "access_code.backup_access_code_pulled";
        case ACCESS_CODE_UNMANAGED_CONVERTED_TO_MANAGED = "access_code.unmanaged.converted_to_managed";
        case ACCESS_CODE_UNMANAGED_FAILED_TO_CONVERT_TO_MANAGED = "access_code.unmanaged.failed_to_convert_to_managed";
        case ACCESS_CODE_UNMANAGED_CREATED = "access_code.unmanaged.created";
        case ACCESS_CODE_UNMANAGED_REMOVED = "access_code.unmanaged.removed";
        case ACCESS_GRANT_CREATED = "access_grant.created";
        case ACCESS_GRANT_DELETED = "access_grant.deleted";
        case ACCESS_GRANT_ACCESS_GRANTED_TO_ALL_DOORS = "access_grant.access_granted_to_all_doors";
        case ACCESS_GRANT_ACCESS_GRANTED_TO_DOOR = "access_grant.access_granted_to_door";
        case ACCESS_GRANT_ACCESS_TO_DOOR_LOST = "access_grant.access_to_door_lost";
        case ACCESS_GRANT_ACCESS_TIMES_CHANGED = "access_grant.access_times_changed";
        case ACCESS_GRANT_COULD_NOT_CREATE_REQUESTED_ACCESS_METHODS = "access_grant.could_not_create_requested_access_methods";
        case ACCESS_METHOD_ISSUED = "access_method.issued";
        case ACCESS_METHOD_REVOKED = "access_method.revoked";
        case ACCESS_METHOD_CARD_ENCODING_REQUIRED = "access_method.card_encoding_required";
        case ACCESS_METHOD_DELETED = "access_method.deleted";
        case ACCESS_METHOD_REISSUED = "access_method.reissued";
        case ACCESS_METHOD_CREATED = "access_method.created";
        case ACCESS_METHOD_DELAY_IN_ISSUING = "access_method.delay_in_issuing";
        case ACCESS_METHOD_FAILED_TO_ISSUE = "access_method.failed_to_issue";
        case ACS_SYSTEM_CONNECTED = "acs_system.connected";
        case ACS_SYSTEM_ADDED = "acs_system.added";
        case ACS_SYSTEM_DISCONNECTED = "acs_system.disconnected";
        case ACS_CREDENTIAL_DELETED = "acs_credential.deleted";
        case ACS_CREDENTIAL_ISSUED = "acs_credential.issued";
        case ACS_CREDENTIAL_REISSUED = "acs_credential.reissued";
        case ACS_CREDENTIAL_INVALIDATED = "acs_credential.invalidated";
        case ACS_USER_CREATED = "acs_user.created";
        case ACS_USER_DELETED = "acs_user.deleted";
        case ACS_ENCODER_ADDED = "acs_encoder.added";
        case ACS_ENCODER_REMOVED = "acs_encoder.removed";
        case ACS_ACCESS_GROUP_DELETED = "acs_access_group.deleted";
        case ACS_ENTRANCE_ADDED = "acs_entrance.added";
        case ACS_ENTRANCE_REMOVED = "acs_entrance.removed";
        case CLIENT_SESSION_DELETED = "client_session.deleted";
        case CONNECTED_ACCOUNT_CONNECTED = "connected_account.connected";
        case CONNECTED_ACCOUNT_CREATED = "connected_account.created";
        case CONNECTED_ACCOUNT_SUCCESSFUL_LOGIN = "connected_account.successful_login";
        case CONNECTED_ACCOUNT_DISCONNECTED = "connected_account.disconnected";
        case CONNECTED_ACCOUNT_COMPLETED_FIRST_SYNC = "connected_account.completed_first_sync";
        case CONNECTED_ACCOUNT_DELETED = "connected_account.deleted";
        case CONNECTED_ACCOUNT_COMPLETED_FIRST_SYNC_AFTER_RECONNECTION = "connected_account.completed_first_sync_after_reconnection";
        case CONNECTED_ACCOUNT_REAUTHORIZATION_REQUESTED = "connected_account.reauthorization_requested";
        case ACTION_ATTEMPT_LOCK_DOOR_SUCCEEDED = "action_attempt.lock_door.succeeded";
        case ACTION_ATTEMPT_LOCK_DOOR_FAILED = "action_attempt.lock_door.failed";
        case ACTION_ATTEMPT_UNLOCK_DOOR_SUCCEEDED = "action_attempt.unlock_door.succeeded";
        case ACTION_ATTEMPT_UNLOCK_DOOR_FAILED = "action_attempt.unlock_door.failed";
        case ACTION_ATTEMPT_SIMULATE_KEYPAD_CODE_ENTRY_SUCCEEDED = "action_attempt.simulate_keypad_code_entry.succeeded";
        case ACTION_ATTEMPT_SIMULATE_KEYPAD_CODE_ENTRY_FAILED = "action_attempt.simulate_keypad_code_entry.failed";
        case ACTION_ATTEMPT_SIMULATE_MANUAL_LOCK_VIA_KEYPAD_SUCCEEDED = "action_attempt.simulate_manual_lock_via_keypad.succeeded";
        case ACTION_ATTEMPT_SIMULATE_MANUAL_LOCK_VIA_KEYPAD_FAILED = "action_attempt.simulate_manual_lock_via_keypad.failed";
        case CONNECT_WEBVIEW_LOGIN_SUCCEEDED = "connect_webview.login_succeeded";
        case CONNECT_WEBVIEW_LOGIN_FAILED = "connect_webview.login_failed";
        case DEVICE_CONNECTED = "device.connected";
        case DEVICE_ADDED = "device.added";
        case DEVICE_CONVERTED_TO_UNMANAGED = "device.converted_to_unmanaged";
        case DEVICE_UNMANAGED_CONVERTED_TO_MANAGED = "device.unmanaged.converted_to_managed";
        case DEVICE_UNMANAGED_CONNECTED = "device.unmanaged.connected";
        case DEVICE_DISCONNECTED = "device.disconnected";
        case DEVICE_UNMANAGED_DISCONNECTED = "device.unmanaged.disconnected";
        case DEVICE_TAMPERED = "device.tampered";
        case DEVICE_LOW_BATTERY = "device.low_battery";
        case DEVICE_BATTERY_STATUS_CHANGED = "device.battery_status_changed";
        case DEVICE_REMOVED = "device.removed";
        case DEVICE_DELETED = "device.deleted";
        case DEVICE_THIRD_PARTY_INTEGRATION_DETECTED = "device.third_party_integration_detected";
        case DEVICE_THIRD_PARTY_INTEGRATION_NO_LONGER_DETECTED = "device.third_party_integration_no_longer_detected";
        case DEVICE_SALTO_PRIVACY_MODE_ACTIVATED = "device.salto.privacy_mode_activated";
        case DEVICE_SALTO_PRIVACY_MODE_DEACTIVATED = "device.salto.privacy_mode_deactivated";
        case DEVICE_CONNECTION_BECAME_FLAKY = "device.connection_became_flaky";
        case DEVICE_CONNECTION_STABILIZED = "device.connection_stabilized";
        case DEVICE_ERROR_SUBSCRIPTION_REQUIRED = "device.error.subscription_required";
        case DEVICE_ERROR_SUBSCRIPTION_REQUIRED_RESOLVED = "device.error.subscription_required.resolved";
        case DEVICE_ACCESSORY_KEYPAD_CONNECTED = "device.accessory_keypad_connected";
        case DEVICE_ACCESSORY_KEYPAD_DISCONNECTED = "device.accessory_keypad_disconnected";
        case NOISE_SENSOR_NOISE_THRESHOLD_TRIGGERED = "noise_sensor.noise_threshold_triggered";
        case LOCK_LOCKED = "lock.locked";
        case LOCK_UNLOCKED = "lock.unlocked";
        case LOCK_ACCESS_DENIED = "lock.access_denied";
        case THERMOSTAT_CLIMATE_PRESET_ACTIVATED = "thermostat.climate_preset_activated";
        case THERMOSTAT_MANUALLY_ADJUSTED = "thermostat.manually_adjusted";
        case THERMOSTAT_TEMPERATURE_THRESHOLD_EXCEEDED = "thermostat.temperature_threshold_exceeded";
        case THERMOSTAT_TEMPERATURE_THRESHOLD_NO_LONGER_EXCEEDED = "thermostat.temperature_threshold_no_longer_exceeded";
        case THERMOSTAT_TEMPERATURE_REACHED_SET_POINT = "thermostat.temperature_reached_set_point";
        case THERMOSTAT_TEMPERATURE_CHANGED = "thermostat.temperature_changed";
        case DEVICE_NAME_CHANGED = "device.name_changed";
        case CAMERA_ACTIVATED = "camera.activated";
        case DEVICE_DOORBELL_RANG = "device.doorbell_rang";
        case PHONE_DEACTIVATED = "phone.deactivated";
        case SPACE_DEVICE_MEMBERSHIP_CHANGED = "space.device_membership_changed";
        case SPACE_CREATED = "space.created";
        case SPACE_DELETED = "space.deleted";
    }
}

namespace Seam\Resources\Event\AccessCodeChanged {
    /**
     * List of properties that changed on the access code.
     */
    class ChangedProperties
    {
        public static function from_json(mixed $json): ChangedProperties|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                from: $json->from ?? null,
                property: $json->property ?? null,
                to: $json->to ?? null,
            );
        }

        public function __construct(
            /**
             * Previous value of the property, or null if not set.
             */
            public string|null $from,
            /**
             * Name of the property that changed (e.g. `code`).
             */
            public string|null $property,
            /**
             * New value of the property, or null if cleared.
             */
            public string|null $to,
        ) {}
    }
}

namespace Seam\Resources\Event\AccessCodeNameChanged {
    /**
     * Previous access code name configuration.
     */
    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(name: $json->name ?? null);
        }

        public function __construct(
            /**
             * Previous name of the access code.
             */
            public string|null $name,
        ) {}
    }

    /**
     * New access code name configuration.
     */
    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(name: $json->name ?? null);
        }

        public function __construct(
            /**
             * New name of the access code.
             */
            public string|null $name,
        ) {}
    }
}

namespace Seam\Resources\Event\AccessCodeCodeChanged {
    /**
     * Previous pin code configuration.
     */
    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(code: $json->code ?? null);
        }

        public function __construct(
            /**
             * Previous pin code.
             */
            public string|null $code,
        ) {}
    }

    /**
     * New pin code configuration.
     */
    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(code: $json->code ?? null);
        }

        public function __construct(
            /**
             * New pin code.
             */
            public string|null $code,
        ) {}
    }
}

namespace Seam\Resources\Event\AccessCodeTimeFrameChanged {
    /**
     * Previous time frame configuration.
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
             * Previous end time.
             */
            public string|null $ends_at,
            /**
             * Previous start time.
             */
            public string|null $starts_at,
        ) {}
    }

    /**
     * New time frame configuration.
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
             * New end time.
             */
            public string|null $ends_at,
            /**
             * New start time.
             */
            public string|null $starts_at,
        ) {}
    }
}

namespace Seam\Resources\Event\AccessCodeMutationsRequested {
    /**
     * Array of mutations requested on the access code, each containing the mutation type and from/to values.
     */
    class RequestedMutations
    {
        public static function from_json(mixed $json): RequestedMutations|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                mutation_code: $json->mutation_code ?? null,
                from: $json->from ?? null,
                to: $json->to ?? null,
            );
        }

        public function __construct(
            /**
             * Code identifying the type of mutation requested, such as `updating_name`, `updating_code`, `updating_time_frame`, or `deleting`.
             *
             * @var value-of<\Seam\Resources\Event\AccessCodeMutationsRequested\RequestedMutations\MutationCode>|string|null
             */
            public string|null $mutation_code,
            /**
             * Previous property values before the requested change. Keys depend on the mutation type. Absent for non-property mutations like `deleting`.
             *
             * @var array<string, mixed>|\stdClass|null
             */
            public array|\stdClass|null $from = null,
            /**
             * New property values after the requested change. Keys depend on the mutation type. Absent for non-property mutations like `deleting`.
             *
             * @var array<string, mixed>|\stdClass|null
             */
            public array|\stdClass|null $to = null,
        ) {}
    }
}

namespace Seam\Resources\Event\AccessCodeMutationsRequested\RequestedMutations {
    enum MutationCode: string
    {
        case UPDATING_NAME = "updating_name";
        case UPDATING_CODE = "updating_code";
        case UPDATING_TIME_FRAME = "updating_time_frame";
        case DELETING = "deleting";
        case CREATING = "creating";
        case DEFERRING_CREATION = "deferring_creation";
    }
}

namespace Seam\Resources\Event\AccessCodeDelayInSettingOnDevice {
    /**
     * Errors associated with the access code.
     */
    class AccessCodeErrors
    {
        public static function from_json(mixed $json): AccessCodeErrors|null
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the access code.
     */
    class AccessCodeWarnings
    {
        public static function from_json(mixed $json): AccessCodeWarnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    /**
     * Errors associated with the connected account.
     */
    class ConnectedAccountErrors
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountErrors|null {
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the connected account.
     */
    class ConnectedAccountWarnings
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountWarnings|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    /**
     * Errors associated with the device.
     */
    class DeviceErrors
    {
        public static function from_json(mixed $json): DeviceErrors|null
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the device.
     */
    class DeviceWarnings
    {
        public static function from_json(mixed $json): DeviceWarnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\Event\AccessCodeFailedToSetOnDevice {
    /**
     * Errors associated with the access code.
     */
    class AccessCodeErrors
    {
        public static function from_json(mixed $json): AccessCodeErrors|null
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the access code.
     */
    class AccessCodeWarnings
    {
        public static function from_json(mixed $json): AccessCodeWarnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    /**
     * Errors associated with the connected account.
     */
    class ConnectedAccountErrors
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountErrors|null {
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the connected account.
     */
    class ConnectedAccountWarnings
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountWarnings|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    /**
     * Errors associated with the device.
     */
    class DeviceErrors
    {
        public static function from_json(mixed $json): DeviceErrors|null
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the device.
     */
    class DeviceWarnings
    {
        public static function from_json(mixed $json): DeviceWarnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\Event\AccessCodeDelayInRemovingFromDevice {
    /**
     * Errors associated with the access code.
     */
    class AccessCodeErrors
    {
        public static function from_json(mixed $json): AccessCodeErrors|null
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the access code.
     */
    class AccessCodeWarnings
    {
        public static function from_json(mixed $json): AccessCodeWarnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    /**
     * Errors associated with the connected account.
     */
    class ConnectedAccountErrors
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountErrors|null {
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the connected account.
     */
    class ConnectedAccountWarnings
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountWarnings|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    /**
     * Errors associated with the device.
     */
    class DeviceErrors
    {
        public static function from_json(mixed $json): DeviceErrors|null
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the device.
     */
    class DeviceWarnings
    {
        public static function from_json(mixed $json): DeviceWarnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\Event\AccessCodeFailedToRemoveFromDevice {
    /**
     * Errors associated with the access code.
     */
    class AccessCodeErrors
    {
        public static function from_json(mixed $json): AccessCodeErrors|null
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the access code.
     */
    class AccessCodeWarnings
    {
        public static function from_json(mixed $json): AccessCodeWarnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    /**
     * Errors associated with the connected account.
     */
    class ConnectedAccountErrors
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountErrors|null {
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the connected account.
     */
    class ConnectedAccountWarnings
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountWarnings|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    /**
     * Errors associated with the device.
     */
    class DeviceErrors
    {
        public static function from_json(mixed $json): DeviceErrors|null
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the device.
     */
    class DeviceWarnings
    {
        public static function from_json(mixed $json): DeviceWarnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\Event\AccessCodeUnmanagedFailedToConvertToManaged {
    /**
     * Errors associated with the access code.
     */
    class AccessCodeErrors
    {
        public static function from_json(mixed $json): AccessCodeErrors|null
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the access code.
     */
    class AccessCodeWarnings
    {
        public static function from_json(mixed $json): AccessCodeWarnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    /**
     * Errors associated with the connected account.
     */
    class ConnectedAccountErrors
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountErrors|null {
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the connected account.
     */
    class ConnectedAccountWarnings
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountWarnings|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    /**
     * Errors associated with the device.
     */
    class DeviceErrors
    {
        public static function from_json(mixed $json): DeviceErrors|null
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the device.
     */
    class DeviceWarnings
    {
        public static function from_json(mixed $json): DeviceWarnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\Event\AcsSystemDisconnected {
    /**
     * Errors associated with the access control system.
     */
    class AcsSystemErrors
    {
        public static function from_json(mixed $json): AcsSystemErrors|null
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the access control system.
     */
    class AcsSystemWarnings
    {
        public static function from_json(mixed $json): AcsSystemWarnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    /**
     * Errors associated with the connected account.
     */
    class ConnectedAccountErrors
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountErrors|null {
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the connected account.
     */
    class ConnectedAccountWarnings
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountWarnings|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\Event\ConnectedAccountDisconnected {
    /**
     * Errors associated with the connected account.
     */
    class ConnectedAccountErrors
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountErrors|null {
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the connected account.
     */
    class ConnectedAccountWarnings
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountWarnings|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\Event\ConnectedAccountReauthorizationRequested {
    /**
     * Errors associated with the connected account.
     */
    class ConnectedAccountErrors
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountErrors|null {
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the connected account.
     */
    class ConnectedAccountWarnings
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountWarnings|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\Event\DeviceDisconnected {
    /**
     * Errors associated with the connected account.
     */
    class ConnectedAccountErrors
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountErrors|null {
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the connected account.
     */
    class ConnectedAccountWarnings
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountWarnings|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    /**
     * Errors associated with the device.
     */
    class DeviceErrors
    {
        public static function from_json(mixed $json): DeviceErrors|null
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the device.
     */
    class DeviceWarnings
    {
        public static function from_json(mixed $json): DeviceWarnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    enum ErrorCode: string
    {
        case ACCOUNT_DISCONNECTED = "account_disconnected";
        case HUB_DISCONNECTED = "hub_disconnected";
        case DEVICE_DISCONNECTED = "device_disconnected";
    }
}

namespace Seam\Resources\Event\DeviceUnmanagedDisconnected {
    /**
     * Errors associated with the connected account.
     */
    class ConnectedAccountErrors
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountErrors|null {
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the connected account.
     */
    class ConnectedAccountWarnings
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountWarnings|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    /**
     * Errors associated with the device.
     */
    class DeviceErrors
    {
        public static function from_json(mixed $json): DeviceErrors|null
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the device.
     */
    class DeviceWarnings
    {
        public static function from_json(mixed $json): DeviceWarnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    enum ErrorCode: string
    {
        case ACCOUNT_DISCONNECTED = "account_disconnected";
        case HUB_DISCONNECTED = "hub_disconnected";
        case DEVICE_DISCONNECTED = "device_disconnected";
    }
}

namespace Seam\Resources\Event\DeviceBatteryStatusChanged {
    enum BatteryStatus: string
    {
        case CRITICAL = "critical";
        case LOW = "low";
        case GOOD = "good";
        case FULL = "full";
    }
}

namespace Seam\Resources\Event\DeviceConnectionBecameFlaky {
    /**
     * Errors associated with the connected account.
     */
    class ConnectedAccountErrors
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountErrors|null {
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the connected account.
     */
    class ConnectedAccountWarnings
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountWarnings|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    /**
     * Errors associated with the device.
     */
    class DeviceErrors
    {
        public static function from_json(mixed $json): DeviceErrors|null
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the device.
     */
    class DeviceWarnings
    {
        public static function from_json(mixed $json): DeviceWarnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\Event\DeviceErrorSubscriptionRequired {
    /**
     * Errors associated with the connected account.
     */
    class ConnectedAccountErrors
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountErrors|null {
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the connected account.
     */
    class ConnectedAccountWarnings
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountWarnings|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    /**
     * Errors associated with the device.
     */
    class DeviceErrors
    {
        public static function from_json(mixed $json): DeviceErrors|null
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the device.
     */
    class DeviceWarnings
    {
        public static function from_json(mixed $json): DeviceWarnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\Event\DeviceAccessoryKeypadDisconnected {
    /**
     * Errors associated with the connected account.
     */
    class ConnectedAccountErrors
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountErrors|null {
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the connected account.
     */
    class ConnectedAccountWarnings
    {
        public static function from_json(
            mixed $json,
        ): ConnectedAccountWarnings|null {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }

    /**
     * Errors associated with the device.
     */
    class DeviceErrors
    {
        public static function from_json(mixed $json): DeviceErrors|null
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
             */
            public string|null $error_code,
            /**
             * Detailed description of the error. Provides insights into the issue and potentially how to rectify it.
             */
            public string|null $message,
        ) {}
    }

    /**
     * Warnings associated with the device.
     */
    class DeviceWarnings
    {
        public static function from_json(mixed $json): DeviceWarnings|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                created_at: $json->created_at ?? null,
                message: $json->message ?? null,
                warning_code: $json->warning_code ?? null,
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
            public string|null $warning_code,
        ) {}
    }
}

namespace Seam\Resources\Event\LockLocked {
    enum Method: string
    {
        case KEYCODE = "keycode";
        case MANUAL = "manual";
        case AUTOMATIC = "automatic";
        case UNKNOWN = "unknown";
        case REMOTE = "remote";
        case CARD = "card";
    }
}

namespace Seam\Resources\Event\LockUnlocked {
    enum Method: string
    {
        case KEYCODE = "keycode";
        case MANUAL = "manual";
        case AUTOMATIC = "automatic";
        case UNKNOWN = "unknown";
        case REMOTE = "remote";
        case CARD = "card";
    }
}

namespace Seam\Resources\Event\LockAccessDenied {
    /**
     * Why access was denied, when the provider reports a determinable cause. Omitted when unknown.
     */
    class Reason
    {
        public static function from_json(mixed $json): Reason|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                message: $json->message ?? null,
                reason_code: $json->reason_code ?? null,
            );
        }

        public function __construct(
            /**
             * Human-readable explanation of why access was denied.
             */
            public string|null $message,
            /**
             * Normalized reason a lock denied access. Provider-agnostic; not all providers report every value.
             *
             * @var value-of<\Seam\Resources\Event\LockAccessDenied\Reason\ReasonCode>|string|null
             */
            public string|null $reason_code,
        ) {}
    }
}

namespace Seam\Resources\Event\LockAccessDenied\Reason {
    enum ReasonCode: string
    {
        case UNKNOWN_CODE = "unknown_code";
        case EXPIRED_CODE = "expired_code";
        case BLOCKLISTED_CODE = "blocklisted_code";
        case TOO_MANY_ATTEMPTS = "too_many_attempts";
        case BLOCKED_BY_PRIVACY_MODE = "blocked_by_privacy_mode";
        case CREDENTIAL_ERROR = "credential_error";
    }
}

namespace Seam\Resources\Event\ThermostatManuallyAdjusted {
    enum FanModeSetting: string
    {
        case AUTO = "auto";
        case ON = "on";
        case CIRCULATE = "circulate";
    }

    enum HvacModeSetting: string
    {
        case OFF = "off";
        case HEAT = "heat";
        case COOL = "cool";
        case HEAT_COOL = "heat_cool";
        case ECO = "eco";
    }

    enum Method: string
    {
        case SEAM = "seam";
        case EXTERNAL = "external";
    }
}

namespace Seam\Resources\Event\CameraActivated {
    enum ActivationReason: string
    {
        case MOTION_DETECTED = "motion_detected";
    }

    enum MotionSubType: string
    {
        case HUMAN = "human";
        case VEHICLE = "vehicle";
        case PACKAGE = "package";
        case OTHER = "other";
    }
}
