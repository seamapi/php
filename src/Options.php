<?php

namespace Seam;

use Seam\Utils\PackageVersion;

define("DEFAULT_ENDPOINT", "https://connect.getseam.com");

class SeamInvalidOptionsError extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Seam received invalid options: {$message}");
    }
}

class Options
{
    public static function parseOptions(
        ?string $api_key = null,
        ?string $personal_access_token = null,
        ?string $workspace_id = null,
        ?string $endpoint = null
    ): array {
        if ($personal_access_token === null && $api_key === null) {
            $api_key = getenv("SEAM_API_KEY") ?: null;
        }

        $auth_headers = Auth::getAuthHeaders(
            $api_key,
            $personal_access_token,
            $workspace_id
        );
        $sdk_version = PackageVersion::get();

        $headers = array_merge($auth_headers, [
            "User-Agent" => "Seam PHP Client " . $sdk_version,
            "seam-sdk-name" => "seamapi/php",
            "seam-sdk-version" => $sdk_version,
        ]);

        $endpoint = self::getEndpoint($endpoint);

        return [
            "headers" => $headers,
            "endpoint" => $endpoint,
        ];
    }

    public static function getEndpoint(?string $endpoint = null): string
    {
        return $endpoint ?? (self::getEndpointFromEnv() ?? DEFAULT_ENDPOINT);
    }

    public static function getEndpointFromEnv(): ?string
    {
        $seam_api_url = getenv("SEAM_API_URL");
        $seam_endpoint = getenv("SEAM_ENDPOINT");

        if ($seam_api_url) {
            trigger_error(
                "Using the SEAM_API_URL environment variable is deprecated. Support will be removed in a later major version. Use SEAM_ENDPOINT instead.",
                E_USER_DEPRECATED
            );
        }

        if ($seam_api_url && $seam_endpoint) {
            trigger_error(
                "Detected both the SEAM_API_URL and SEAM_ENDPOINT environment variables. Using SEAM_ENDPOINT.",
                E_USER_NOTICE
            );
        }

        return $seam_endpoint ?: $seam_api_url;
    }

    public static function isSeamOptionsWithApiKey(
        ?string $api_key,
        ?string $personal_access_token
    ): bool {
        if ($api_key === null) {
            return false;
        }

        if ($personal_access_token !== null) {
            throw new SeamInvalidOptionsError(
                "The personal_access_token option cannot be used with the api_key option"
            );
        }

        return true;
    }

    public static function isSeamOptionsWithPersonalAccessToken(
        ?string $personal_access_token,
        ?string $api_key,
        ?string $workspace_id
    ): bool {
        if ($personal_access_token === null) {
            return false;
        }

        if ($api_key !== null) {
            throw new SeamInvalidOptionsError(
                "The api_key option cannot be used with the personal_access_token option"
            );
        }

        if ($workspace_id === null) {
            throw new SeamInvalidOptionsError(
                "Must pass a workspace_id when using a personal_access_token"
            );
        }

        return true;
    }
}
