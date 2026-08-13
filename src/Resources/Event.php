<?php

namespace Seam\Resources {
    class Event
    {
        public static function from_json(mixed $json): Event|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                access_code_errors: array_map(
                    fn($a) => Event\AccessCodeErrors::from_json($a),
                    $json->access_code_errors ?? [],
                ),
                access_code_id: $json->access_code_id ?? null,
                access_code_is_managed: $json->access_code_is_managed ?? null,
                access_code_warnings: array_map(
                    fn($a) => Event\AccessCodeWarnings::from_json($a),
                    $json->access_code_warnings ?? [],
                ),
                access_grant_id: $json->access_grant_id ?? null,
                access_grant_ids: $json->access_grant_ids ?? null,
                access_grant_key: $json->access_grant_key ?? null,
                access_grant_keys: $json->access_grant_keys ?? null,
                access_method_id: $json->access_method_id ?? null,
                acs_access_group_id: $json->acs_access_group_id ?? null,
                acs_credential_id: $json->acs_credential_id ?? null,
                acs_encoder_id: $json->acs_encoder_id ?? null,
                acs_entrance_id: $json->acs_entrance_id ?? null,
                acs_entrance_ids: $json->acs_entrance_ids ?? null,
                acs_system_errors: array_map(
                    fn($a) => Event\AcsSystemErrors::from_json($a),
                    $json->acs_system_errors ?? [],
                ),
                acs_system_id: $json->acs_system_id ?? null,
                acs_system_warnings: array_map(
                    fn($a) => Event\AcsSystemWarnings::from_json($a),
                    $json->acs_system_warnings ?? [],
                ),
                acs_user_id: $json->acs_user_id ?? null,
                action_attempt_id: $json->action_attempt_id ?? null,
                action_type: $json->action_type ?? null,
                activation_reason: $json->activation_reason ?? null,
                backup_access_code_id: $json->backup_access_code_id ?? null,
                battery_level: $json->battery_level ?? null,
                battery_status: $json->battery_status ?? null,
                change_reason: $json->change_reason ?? null,
                changed_properties: array_map(
                    fn($c) => Event\ChangedProperties::from_json($c),
                    $json->changed_properties ?? [],
                ),
                client_session_id: $json->client_session_id ?? null,
                climate_preset_key: $json->climate_preset_key ?? null,
                code: $json->code ?? null,
                connect_webview_id: $json->connect_webview_id ?? null,
                connected_account_custom_metadata: $json->connected_account_custom_metadata ??
                    null,
                connected_account_errors: array_map(
                    fn($c) => Event\ConnectedAccountErrors::from_json($c),
                    $json->connected_account_errors ?? [],
                ),
                connected_account_id: $json->connected_account_id ?? null,
                connected_account_warnings: array_map(
                    fn($c) => Event\ConnectedAccountWarnings::from_json($c),
                    $json->connected_account_warnings ?? [],
                ),
                cooling_set_point_celsius: $json->cooling_set_point_celsius ??
                    null,
                cooling_set_point_fahrenheit: $json->cooling_set_point_fahrenheit ??
                    null,
                created_at: $json->created_at ?? null,
                customer_key: $json->customer_key ?? null,
                description: $json->description ?? null,
                desired_temperature_celsius: $json->desired_temperature_celsius ??
                    null,
                desired_temperature_fahrenheit: $json->desired_temperature_fahrenheit ??
                    null,
                device_custom_metadata: $json->device_custom_metadata ?? null,
                device_errors: array_map(
                    fn($d) => Event\DeviceErrors::from_json($d),
                    $json->device_errors ?? [],
                ),
                device_id: $json->device_id ?? null,
                device_ids: $json->device_ids ?? null,
                device_name: $json->device_name ?? null,
                device_warnings: array_map(
                    fn($d) => Event\DeviceWarnings::from_json($d),
                    $json->device_warnings ?? [],
                ),
                ends_at: $json->ends_at ?? null,
                error_code: $json->error_code ?? null,
                error_message: $json->error_message ?? null,
                event_description: $json->event_description ?? null,
                event_id: $json->event_id ?? null,
                event_type: $json->event_type ?? null,
                fan_mode_setting: $json->fan_mode_setting ?? null,
                from: isset($json->from)
                    ? Event\From::from_json($json->from)
                    : null,
                heating_set_point_celsius: $json->heating_set_point_celsius ??
                    null,
                heating_set_point_fahrenheit: $json->heating_set_point_fahrenheit ??
                    null,
                hvac_mode_setting: $json->hvac_mode_setting ?? null,
                image_url: $json->image_url ?? null,
                is_backup_code: $json->is_backup_code ?? null,
                is_fallback_climate_preset: $json->is_fallback_climate_preset ??
                    null,
                is_via_bluetooth: $json->is_via_bluetooth ?? null,
                is_via_nfc: $json->is_via_nfc ?? null,
                lower_limit_celsius: $json->lower_limit_celsius ?? null,
                lower_limit_fahrenheit: $json->lower_limit_fahrenheit ?? null,
                method: $json->method ?? null,
                minut_metadata: $json->minut_metadata ?? null,
                missing_device_ids: $json->missing_device_ids ?? null,
                motion_sub_type: $json->motion_sub_type ?? null,
                noise_level_decibels: $json->noise_level_decibels ?? null,
                noise_level_nrs: $json->noise_level_nrs ?? null,
                noise_threshold_id: $json->noise_threshold_id ?? null,
                noise_threshold_name: $json->noise_threshold_name ?? null,
                noiseaware_metadata: $json->noiseaware_metadata ?? null,
                occurred_at: $json->occurred_at ?? null,
                reason: isset($json->reason)
                    ? Event\Reason::from_json($json->reason)
                    : null,
                requested_mutations: array_map(
                    fn($r) => Event\RequestedMutations::from_json($r),
                    $json->requested_mutations ?? [],
                ),
                space_id: $json->space_id ?? null,
                space_key: $json->space_key ?? null,
                starts_at: $json->starts_at ?? null,
                status: $json->status ?? null,
                temperature_celsius: $json->temperature_celsius ?? null,
                temperature_fahrenheit: $json->temperature_fahrenheit ?? null,
                thermostat_schedule_id: $json->thermostat_schedule_id ?? null,
                to: isset($json->to) ? Event\To::from_json($json->to) : null,
                upper_limit_celsius: $json->upper_limit_celsius ?? null,
                upper_limit_fahrenheit: $json->upper_limit_fahrenheit ?? null,
                video_url: $json->video_url ?? null,
                workspace_id: $json->workspace_id ?? null,
            );
        }

        public function __construct(
            /**
             * Errors associated with the access code.
             */
            public array $access_code_errors,
            public string|null $access_code_id,
            /**
             * Whether the access code is managed by Seam (true) or unmanaged (false). Only present when access_code_id is set.
             */
            public bool|null $access_code_is_managed,
            /**
             * Warnings associated with the access code.
             */
            public array $access_code_warnings,
            /**
             * ID of the affected Access Grant.
             */
            public string|null $access_grant_id,
            /**
             * IDs of the access grants associated with this access method.
             */
            public array|null $access_grant_ids,
            /**
             * Key of the affected Access Grant (if present).
             */
            public string|null $access_grant_key,
            /**
             * Keys of the access grants associated with this access method (if present).
             */
            public array|null $access_grant_keys,
            /**
             * ID of the affected access method.
             */
            public string|null $access_method_id,
            /**
             * ID of the affected access group.
             */
            public string|null $acs_access_group_id,
            /**
             * ID of the affected credential.
             */
            public string|null $acs_credential_id,
            /**
             * ID of the affected encoder.
             */
            public string|null $acs_encoder_id,
            public string|null $acs_entrance_id,
            public array|null $acs_entrance_ids,
            /**
             * Errors associated with the access control system.
             */
            public array $acs_system_errors,
            /**
             * ID of the access system.
             */
            public string|null $acs_system_id,
            /**
             * Warnings associated with the access control system.
             */
            public array $acs_system_warnings,
            /**
             * ID of the affected access system user.
             */
            public string|null $acs_user_id,
            public string|null $action_attempt_id,
            /**
             * Type of the action.
             */
            public string|null $action_type,
            /**
             * The reason the camera was activated.
             */
            public string|null $activation_reason,
            /**
             * ID of the backup access code that was pulled from the pool.
             */
            public string|null $backup_access_code_id,
            /**
             * Number in the range 0 to 1.0 indicating the amount of battery in the affected device, as reported by the device.
             */
            public float|null $battery_level,
            /**
             * Battery status of the affected device, calculated from the numeric `battery_level` value.
             */
            public string|null $battery_status,
            /**
             * Human-readable reason for the change (e.g. `ongoing code auto-renewed`).
             */
            public string|null $change_reason,
            /**
             * List of properties that changed on the access code.
             */
            public array $changed_properties,
            /**
             * ID of the affected client session.
             */
            public string|null $client_session_id,
            /**
             * Key of the climate preset that was activated.
             */
            public string|null $climate_preset_key,
            public string|null $code,
            public string|null $connect_webview_id,
            public mixed $connected_account_custom_metadata,
            /**
             * Errors associated with the connected account.
             */
            public array $connected_account_errors,
            public string|null $connected_account_id,
            /**
             * Warnings associated with the connected account.
             */
            public array $connected_account_warnings,
            /**
             * Temperature to which the thermostat should cool (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $cooling_set_point_celsius,
            /**
             * Temperature to which the thermostat should cool (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $cooling_set_point_fahrenheit,
            /**
             * Date and time at which the event was created.
             */
            public string|null $created_at,
            public string|null $customer_key,
            /**
             * Human-readable description of the change and its source.
             */
            public string|null $description,
            /**
             * Desired temperature, in °C, defined by the affected thermostat's cooling or heating set point.
             */
            public float|null $desired_temperature_celsius,
            /**
             * Desired temperature, in °F, defined by the affected thermostat's cooling or heating set point.
             */
            public float|null $desired_temperature_fahrenheit,
            public mixed $device_custom_metadata,
            /**
             * Errors associated with the device.
             */
            public array $device_errors,
            public string|null $device_id,
            public array|null $device_ids,
            public string|null $device_name,
            /**
             * Warnings associated with the device.
             */
            public array $device_warnings,
            /**
             * The new end time for the access grant.
             */
            public string|null $ends_at,
            /**
             * Error code associated with the disconnection event, if any.
             */
            public string|null $error_code,
            /**
             * Description of why the access methods could not be created.
             */
            public string|null $error_message,
            /**
             * Human-readable description of the event. Persisted when the event is created (so the creating code, including a provider, can supply a tailored description) and otherwise derived from the event.
             */
            public string|null $event_description,
            /**
             * ID of the event.
             */
            public string|null $event_id,
            /**
             * Type of the event.
             */
            public string|null $event_type,
            /**
             * Desired [fan mode setting](https://docs.seam.co/capability-guides/thermostats/configure-current-climate-settings#fan-mode-settings), such as `on`, `auto`, or `circulate`.
             */
            public string|null $fan_mode_setting,
            public Event\From|null $from,
            /**
             * Temperature to which the thermostat should heat (in °C). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $heating_set_point_celsius,
            /**
             * Temperature to which the thermostat should heat (in °F). See also [Set Points](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/set-points).
             */
            public float|null $heating_set_point_fahrenheit,
            /**
             * Desired [HVAC mode](https://docs.seam.co/capability-guides/thermostats/understanding-thermostat-concepts/hvac-mode) setting, such as `heat`, `cool`, `heat_cool`, or `off`.
             */
            public string|null $hvac_mode_setting,
            public string|null $image_url,
            /**
             * Indicates whether the code is a backup code (only present when mode is 'code' and a backup code was used).
             */
            public bool|null $is_backup_code,
            /**
             * Indicates whether the climate preset that was activated is the fallback climate preset for the thermostat.
             */
            public bool|null $is_fallback_climate_preset,
            public bool|null $is_via_bluetooth,
            public bool|null $is_via_nfc,
            /**
             * Lower temperature limit, in °C, defined by the set threshold.
             */
            public float|null $lower_limit_celsius,
            /**
             * Lower temperature limit, in °F, defined by the set threshold.
             */
            public float|null $lower_limit_fahrenheit,
            public string|null $method,
            /**
             * Metadata from Minut.
             */
            public mixed $minut_metadata,
            /**
             * IDs of the devices that did not receive a requested access method. Use these to identify which specific devices failed without having to fetch the Access Grant.
             */
            public array|null $missing_device_ids,
            /**
             * Sub-type of motion detected, if available.
             */
            public string|null $motion_sub_type,
            /**
             * Detected noise level in decibels.
             */
            public float|null $noise_level_decibels,
            /**
             * Detected noise level in Noiseaware Noise Risk Score (NRS).
             */
            public float|null $noise_level_nrs,
            /**
             * ID of the noise threshold that was triggered.
             */
            public string|null $noise_threshold_id,
            /**
             * Name of the noise threshold that was triggered.
             */
            public string|null $noise_threshold_name,
            /**
             * Metadata from Noiseaware.
             */
            public mixed $noiseaware_metadata,
            /**
             * Date and time at which the event occurred.
             */
            public string|null $occurred_at,
            /**
             * Why access was denied, when the provider reports a determinable cause. Omitted when unknown.
             */
            public Event\Reason|null $reason,
            /**
             * Array of mutations requested on the access code, each containing the mutation type and from/to values.
             */
            public array $requested_mutations,
            /**
             * ID of the affected space.
             */
            public string|null $space_id,
            /**
             * Unique key for the space within the workspace.
             */
            public string|null $space_key,
            /**
             * The new start time for the access grant.
             */
            public string|null $starts_at,
            /**
             * Status of the action.
             */
            public string|null $status,
            /**
             * Temperature, in °C, reported by the affected thermostat.
             */
            public float|null $temperature_celsius,
            /**
             * Temperature, in °F, reported by the affected thermostat.
             */
            public float|null $temperature_fahrenheit,
            /**
             * ID of the thermostat schedule that prompted the affected climate preset to be activated.
             */
            public string|null $thermostat_schedule_id,
            public Event\To|null $to,
            /**
             * Upper temperature limit, in °C, defined by the set threshold.
             */
            public float|null $upper_limit_celsius,
            /**
             * Upper temperature limit, in °F, defined by the set threshold.
             */
            public float|null $upper_limit_fahrenheit,
            public string|null $video_url,
            /**
             * ID of the workspace associated with the event.
             */
            public string|null $workspace_id,
        ) {}
    }
}

namespace Seam\Resources\Event {
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

    class From
    {
        public static function from_json(mixed $json): From|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                code: $json->code ?? null,
                ends_at: $json->ends_at ?? null,
                name: $json->name ?? null,
                starts_at: $json->starts_at ?? null,
            );
        }

        public function __construct(
            /**
             * Previous pin code.
             */
            public string|null $code,
            /**
             * Previous end time.
             */
            public string|null $ends_at,
            /**
             * Previous name of the access code.
             */
            public string|null $name,
            /**
             * Previous start time.
             */
            public string|null $starts_at,
        ) {}
    }

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
             */
            public string|null $reason_code,
        ) {}
    }

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
                from: $json->from ?? null,
                mutation_code: $json->mutation_code ?? null,
                to: $json->to ?? null,
            );
        }

        public function __construct(
            /**
             * Previous property values before the requested change. Keys depend on the mutation type. Absent for non-property mutations like `deleting`.
             */
            public mixed $from,
            /**
             * Code identifying the type of mutation requested, such as `updating_name`, `updating_code`, `updating_time_frame`, or `deleting`.
             */
            public string|null $mutation_code,
            /**
             * New property values after the requested change. Keys depend on the mutation type. Absent for non-property mutations like `deleting`.
             */
            public mixed $to,
        ) {}
    }

    class To
    {
        public static function from_json(mixed $json): To|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                code: $json->code ?? null,
                ends_at: $json->ends_at ?? null,
                name: $json->name ?? null,
                starts_at: $json->starts_at ?? null,
            );
        }

        public function __construct(
            /**
             * New pin code.
             */
            public string|null $code,
            /**
             * New end time.
             */
            public string|null $ends_at,
            /**
             * New name of the access code.
             */
            public string|null $name,
            /**
             * New start time.
             */
            public string|null $starts_at,
        ) {}
    }
}
