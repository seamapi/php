<?php

namespace Seam\Resources {
    class DeviceProvider
    {
        public static function from_json(mixed $json): DeviceProvider|null
        {
            if (!$json) {
                return null;
            }
            return new self(
                device_provider_name: $json->device_provider_name ?? null,
                display_name: $json->display_name ?? null,
                image_url: $json->image_url ?? null,
                provider_categories: $json->provider_categories ?? null,
                can_configure_auto_lock: $json->can_configure_auto_lock ?? null,
                can_hvac_cool: $json->can_hvac_cool ?? null,
                can_hvac_heat: $json->can_hvac_heat ?? null,
                can_hvac_heat_cool: $json->can_hvac_heat_cool ?? null,
                can_program_offline_access_codes: $json->can_program_offline_access_codes ??
                    null,
                can_program_online_access_codes: $json->can_program_online_access_codes ??
                    null,
                can_program_thermostat_programs_as_different_each_day: $json->can_program_thermostat_programs_as_different_each_day ??
                    null,
                can_program_thermostat_programs_as_same_each_day: $json->can_program_thermostat_programs_as_same_each_day ??
                    null,
                can_program_thermostat_programs_as_weekday_weekend: $json->can_program_thermostat_programs_as_weekday_weekend ??
                    null,
                can_remotely_lock: $json->can_remotely_lock ?? null,
                can_remotely_unlock: $json->can_remotely_unlock ?? null,
                can_run_thermostat_programs: $json->can_run_thermostat_programs ??
                    null,
                can_simulate_connection: $json->can_simulate_connection ?? null,
                can_simulate_disconnection: $json->can_simulate_disconnection ??
                    null,
                can_simulate_hub_connection: $json->can_simulate_hub_connection ??
                    null,
                can_simulate_hub_disconnection: $json->can_simulate_hub_disconnection ??
                    null,
                can_simulate_paid_subscription: $json->can_simulate_paid_subscription ??
                    null,
                can_simulate_removal: $json->can_simulate_removal ?? null,
                can_turn_off_hvac: $json->can_turn_off_hvac ?? null,
                can_unlock_with_code: $json->can_unlock_with_code ?? null,
            );
        }

        public function __construct(
            /**
             * Name of the device provider.
             *
             * @var value-of<\Seam\Resources\DeviceProvider\DeviceProviderName>|string|null
             */
            public string|null $device_provider_name,
            /**
             * Display name for the device provider.
             */
            public string|null $display_name,
            /**
             * Image URL for the device provider.
             */
            public string|null $image_url,
            /**
             * List of provider categories to which the device provider belongs, such as `stable`, `consumer_smartlocks`, `thermostats`, and so on.
             *
             * @var list<string>|null
             */
            public array|null $provider_categories,
            /**
             * Indicates whether the lock supports configuring automatic locking.
             */
            public bool|null $can_configure_auto_lock = null,
            /**
             * Indicates whether the thermostat supports cooling.
             */
            public bool|null $can_hvac_cool = null,
            /**
             * Indicates whether the thermostat supports heating.
             */
            public bool|null $can_hvac_heat = null,
            /**
             * Indicates whether the thermostat supports simultaneous heating and cooling.
             */
            public bool|null $can_hvac_heat_cool = null,
            /**
             * Indicates whether the device supports programming offline access codes.
             */
            public bool|null $can_program_offline_access_codes = null,
            /**
             * Indicates whether the device supports programming online access codes.
             */
            public bool|null $can_program_online_access_codes = null,
            /**
             * Indicates whether the thermostat supports different climate programs for each day of the week.
             */
            public bool|null $can_program_thermostat_programs_as_different_each_day = null,
            /**
             * Indicates whether the thermostat supports a single climate program applied to every day.
             */
            public bool|null $can_program_thermostat_programs_as_same_each_day = null,
            /**
             * Indicates whether the thermostat supports weekday/weekend climate programs.
             */
            public bool|null $can_program_thermostat_programs_as_weekday_weekend = null,
            /**
             * Indicates whether the device supports remote locking.
             */
            public bool|null $can_remotely_lock = null,
            /**
             * Indicates whether the device supports remote unlocking.
             */
            public bool|null $can_remotely_unlock = null,
            /**
             * Indicates whether the thermostat supports running climate programs.
             */
            public bool|null $can_run_thermostat_programs = null,
            /**
             * Indicates whether the device supports simulating connection in a sandbox.
             */
            public bool|null $can_simulate_connection = null,
            /**
             * Indicates whether the device supports simulating disconnection in a sandbox.
             */
            public bool|null $can_simulate_disconnection = null,
            /**
             * Indicates whether the hub supports simulating connection in a sandbox.
             */
            public bool|null $can_simulate_hub_connection = null,
            /**
             * Indicates whether the hub supports simulating disconnection in a sandbox.
             */
            public bool|null $can_simulate_hub_disconnection = null,
            /**
             * Indicates whether the device supports simulating a paid subscription in a sandbox.
             */
            public bool|null $can_simulate_paid_subscription = null,
            /**
             * Indicates whether the device supports simulating removal in a sandbox.
             */
            public bool|null $can_simulate_removal = null,
            /**
             * Indicates whether the thermostat can be turned off.
             */
            public bool|null $can_turn_off_hvac = null,
            /**
             * Indicates whether the lock supports unlocking with an access code.
             */
            public bool|null $can_unlock_with_code = null,
        ) {}
    }
}

namespace Seam\Resources\DeviceProvider {
    enum DeviceProviderName: string
    {
        case HOTEK = "hotek";
        case DORMAKABA_COMMUNITY = "dormakaba_community";
        case LEGIC_CONNECT = "legic_connect";
        case AKUVOX = "akuvox";
        case AUGUST = "august";
        case AVIGILON_ALTA = "avigilon_alta";
        case BRIVO = "brivo";
        case BUTTERFLYMX = "butterflymx";
        case SCHLAGE = "schlage";
        case SMARTTHINGS = "smartthings";
        case YALE = "yale";
        case GENIE = "genie";
        case DOORKING = "doorking";
        case SALTO = "salto";
        case SALTO_KS = "salto_ks";
        case SALTO_KS_ACCEPT = "salto_ks_accept";
        case LOCKLY = "lockly";
        case TTLOCK = "ttlock";
        case LINEAR = "linear";
        case NOISEAWARE = "noiseaware";
        case NUKI = "nuki";
        case IGLOO = "igloo";
        case KWIKSET = "kwikset";
        case MINUT = "minut";
        case MY_2N = "my_2n";
        case CONTROLBYWEB = "controlbyweb";
        case NEST = "nest";
        case IGLOOHOME = "igloohome";
        case ECOBEE = "ecobee";
        case FOUR_SUITES = "four_suites";
        case DORMAKABA_ORACODE = "dormakaba_oracode";
        case PTI = "pti";
        case WYZE = "wyze";
        case SEAM_PASSPORT = "seam_passport";
        case VISIONLINE = "visionline";
        case ASSA_ABLOY_CREDENTIAL_SERVICE = "assa_abloy_credential_service";
        case TEDEE = "tedee";
        case HONEYWELL_RESIDEO = "honeywell_resideo";
        case FIRST_ALERT = "first_alert";
        case LATCH = "latch";
        case AKILES = "akiles";
        case ASSA_ABLOY_VOSTIO = "assa_abloy_vostio";
        case ASSA_ABLOY_VOSTIO_CREDENTIAL_SERVICE = "assa_abloy_vostio_credential_service";
        case TADO = "tado";
        case SALTO_SPACE = "salto_space";
        case SENSI = "sensi";
        case KEYNEST = "keynest";
        case KORELOCK = "korelock";
        case KEYINCODE = "keyincode";
        case DORMAKABA_AMBIANCE = "dormakaba_ambiance";
        case ULTRALOQ = "ultraloq";
        case YACAN = "yacan";
        case DUSAW = "dusaw";
        case SIFELY = "sifely";
        case THIRTY_THREE_LOCK = "thirty_three_lock";
        case RING = "ring";
        case ICAL = "ical";
        case LODGIFY = "lodgify";
        case HOSTAWAY = "hostaway";
        case GUESTY = "guesty";
        case ACUITY_SCHEDULING = "acuity_scheduling";
        case OMNITEC = "omnitec";
        case KISI = "kisi";
        case AQARA = "aqara";
    }
}
