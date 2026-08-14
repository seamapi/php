<?php

namespace Seam;

/**
 * Resolves the API endpoint and validates mutually exclusive authentication
 * options.
 */
final class Options
{
    public const DEFAULT_ENDPOINT = "https://connect.getseam.com";

    public static function get_endpoint(?string $endpoint = null): string
    {
        return $endpoint ??
            (self::get_endpoint_from_env() ?? self::DEFAULT_ENDPOINT);
    }

    public static function get_endpoint_from_env(): ?string
    {
        $seam_endpoint = self::get_env("SEAM_ENDPOINT");
        return $seam_endpoint
    }

    public static function is_seam_options_with_api_key(
        ?string $api_key = null,
        ?string $personal_access_token = null,
    ): bool {
        if ($api_key === null) {
            return false;
        }

        if ($personal_access_token !== null) {
            throw new InvalidOptionsError(
                "The personal_access_token option cannot be used with the api_key option",
            );
        }

        return true;
    }

    public static function is_seam_options_with_personal_access_token(
        ?string $personal_access_token = null,
        ?string $api_key = null,
        ?string $workspace_id = null,
    ): bool {
        if ($personal_access_token === null) {
            return false;
        }

        if ($api_key !== null) {
            throw new InvalidOptionsError(
                "The api_key option cannot be used with the personal_access_token option",
            );
        }

        if ($workspace_id === null) {
            throw new InvalidOptionsError(
                "Must pass a workspace_id when using a personal_access_token",
            );
        }

        return true;
    }

    /**
     * A preconfigured client carries its own endpoint and authorization, so an
     * option that would configure one is a mistake to combine with it, and is
     * rejected rather than silently ignored.
     *
     * @param array<string, mixed> $options The other options by name, where
     * null (or, for an options array, empty) means the option was not given.
     */
    public static function check_client_options(
        ?object $client,
        array $options,
    ): void {
        if ($client === null) {
            return;
        }

        foreach ($options as $name => $value) {
            if ($value !== null && $value !== []) {
                throw new InvalidOptionsError(
                    "The {$name} option cannot be used with the client option",
                );
            }
        }
    }

    /**
     * Reads an environment variable, treating an empty value as unset so that
     * an exported-but-blank variable does not override the default.
     */
    public static function get_env(string $name): ?string
    {
        $value = getenv($name);

        if ($value === false || $value === "") {
            return null;
        }

        return $value;
    }

    private static function warn(string $message): void
    {
        trigger_error($message, E_USER_WARNING);
    }
}
