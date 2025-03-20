<?php

namespace Seam;

class SeamInvalidTokenError extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Seam received an invalid token: {$message}");
    }
}

class Auth
{
    const TOKEN_PREFIX = "seam_";
    const ACCESS_TOKEN_PREFIX = "seam_at";
    const JWT_PREFIX = "ey";
    const CLIENT_SESSION_TOKEN_PREFIX = "seam_cst";
    const PUBLISHABLE_KEY_TOKEN_PREFIX = "seam_pk";

    public static function getAuthHeaders(
        ?string $api_key = null,
        ?string $personal_access_token = null,
        ?string $workspace_id = null
    ): array {
        if (
            Options::isSeamOptionsWithApiKey($api_key, $personal_access_token)
        ) {
            return self::getAuthHeadersForApiKey($api_key);
        }

        if (
            Options::isSeamOptionsWithPersonalAccessToken(
                $personal_access_token,
                $api_key,
                $workspace_id
            )
        ) {
            return self::getAuthHeadersForPersonalAccessToken(
                $personal_access_token,
                $workspace_id
            );
        }

        throw new SeamInvalidOptionsError(
            "Must specify an api_key or personal_access_token. " .
                "Attempted reading configuration from the environment, " .
                "but the environment variable SEAM_API_KEY is not set."
        );
    }

    public static function getAuthHeadersForApiKey(string $api_key): array
    {
        if (self::isClientSessionToken($api_key)) {
            throw new SeamInvalidTokenError(
                "A Client Session Token cannot be used as an api_key"
            );
        }

        if (self::isJwt($api_key)) {
            throw new SeamInvalidTokenError(
                "A JWT cannot be used as an api_key"
            );
        }

        if (self::isAccessToken($api_key)) {
            throw new SeamInvalidTokenError(
                "An Access Token cannot be used as an api_key"
            );
        }

        if (self::isPublishableKey($api_key)) {
            throw new SeamInvalidTokenError(
                "A Publishable Key cannot be used as an api_key"
            );
        }

        if (!self::isSeamToken($api_key)) {
            throw new SeamInvalidTokenError(
                "Unknown or invalid api_key format, expected token to start with " .
                    self::TOKEN_PREFIX
            );
        }

        return ["Authorization" => "Bearer {$api_key}"];
    }

    public static function getAuthHeadersForPersonalAccessToken(
        string $personal_access_token,
        string $workspace_id
    ): array {
        if (self::isJwt($personal_access_token)) {
            throw new SeamInvalidTokenError(
                "A JWT cannot be used as a personal_access_token"
            );
        }

        if (self::isClientSessionToken($personal_access_token)) {
            throw new SeamInvalidTokenError(
                "A Client Session Token cannot be used as a personal_access_token"
            );
        }

        if (self::isPublishableKey($personal_access_token)) {
            throw new SeamInvalidTokenError(
                "A Publishable Key cannot be used as a personal_access_token"
            );
        }

        if (!self::isAccessToken($personal_access_token)) {
            throw new SeamInvalidTokenError(
                "Unknown or invalid personal_access_token format, expected token to start with " .
                    self::ACCESS_TOKEN_PREFIX
            );
        }

        return [
            "Authorization" => "Bearer {$personal_access_token}",
            "Seam-Workspace-Id" => $workspace_id,
        ];
    }

    public static function getAuthHeadersForMultiWorkspacePersonalAccessToken(
        string $personal_access_token
    ): array {
        if (self::isJwt($personal_access_token)) {
            throw new SeamInvalidTokenError(
                "A JWT cannot be used as a personal_access_token"
            );
        }

        if (self::isClientSessionToken($personal_access_token)) {
            throw new SeamInvalidTokenError(
                "A Client Session Token cannot be used as a personal_access_token"
            );
        }

        if (self::isPublishableKey($personal_access_token)) {
            throw new SeamInvalidTokenError(
                "A Publishable Key cannot be used as a personal_access_token"
            );
        }

        if (!self::isAccessToken($personal_access_token)) {
            throw new SeamInvalidTokenError(
                "Unknown or invalid personal_access_token format, expected token to start with " .
                    self::ACCESS_TOKEN_PREFIX
            );
        }

        return ["Authorization" => "Bearer {$personal_access_token}"];
    }

    public static function isAccessToken(string $token): bool
    {
        return str_starts_with($token, self::ACCESS_TOKEN_PREFIX);
    }

    public static function isJwt(string $token): bool
    {
        return str_starts_with($token, self::JWT_PREFIX);
    }

    public static function isSeamToken(string $token): bool
    {
        return str_starts_with($token, self::TOKEN_PREFIX);
    }

    public static function isApiKey(string $token): bool
    {
        return !self::isClientSessionToken($token) &&
            !self::isJwt($token) &&
            !self::isAccessToken($token) &&
            !self::isPublishableKey($token) &&
            self::isSeamToken($token);
    }

    public static function isClientSessionToken(string $token): bool
    {
        return str_starts_with($token, self::CLIENT_SESSION_TOKEN_PREFIX);
    }

    public static function isPublishableKey(string $token): bool
    {
        return str_starts_with($token, self::PUBLISHABLE_KEY_TOKEN_PREFIX);
    }

    public static function isConsoleSessionToken(string $token): bool
    {
        return self::isJwt($token);
    }

    public static function isPersonalAccessToken(string $token): bool
    {
        return self::isAccessToken($token);
    }
}
