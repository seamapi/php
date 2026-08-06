<?php

namespace Seam;

/**
 * Resolves the API endpoint and validates mutually exclusive authentication
 * options, mirroring the option handling in the other Seam SDKs.
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
        $seam_api_url = self::get_env("SEAM_API_URL");
        $seam_endpoint = self::get_env("SEAM_ENDPOINT");

        if ($seam_api_url !== null) {
            self::warn(
                "Using the SEAM_API_URL environment variable is deprecated. " .
                    "Support will be removed in a later major version. Use SEAM_ENDPOINT instead.",
            );
        }

        if ($seam_api_url !== null && $seam_endpoint !== null) {
            self::warn(
                "Detected both the SEAM_API_URL and SEAM_ENDPOINT environment variables. " .
                    "Using SEAM_ENDPOINT.",
            );
        }

        return $seam_endpoint ?? $seam_api_url;
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
