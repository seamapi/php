<?php

namespace Seam;

/**
 * Predicates for recognizing the kinds of token the Seam API issues.
 *
 * Only API keys and personal access tokens authenticate this SDK. The other
 * kinds are recognized so that passing one produces a specific error instead
 * of an opaque 401 from the server.
 */
final class Token
{
    public const TOKEN_PREFIX = "seam_";

    public const ACCESS_TOKEN_PREFIX = "seam_at";

    public const CLIENT_SESSION_TOKEN_PREFIX = "seam_cst";

    public const PUBLISHABLE_KEY_PREFIX = "seam_pk";

    public const JWT_PREFIX = "ey";

    public static function is_seam_token(string $token): bool
    {
        return str_starts_with($token, self::TOKEN_PREFIX);
    }

    public static function is_access_token(string $token): bool
    {
        return str_starts_with($token, self::ACCESS_TOKEN_PREFIX);
    }

    public static function is_client_session_token(string $token): bool
    {
        return str_starts_with($token, self::CLIENT_SESSION_TOKEN_PREFIX);
    }

    public static function is_publishable_key(string $token): bool
    {
        return str_starts_with($token, self::PUBLISHABLE_KEY_PREFIX);
    }

    public static function is_jwt(string $token): bool
    {
        return str_starts_with($token, self::JWT_PREFIX);
    }

    public static function is_console_session_token(string $token): bool
    {
        return self::is_jwt($token);
    }

    public static function is_personal_access_token(string $token): bool
    {
        return self::is_access_token($token);
    }

    public static function is_api_key(string $token): bool
    {
        return !self::is_client_session_token($token) &&
            !self::is_jwt($token) &&
            !self::is_access_token($token) &&
            !self::is_publishable_key($token) &&
            self::is_seam_token($token);
    }
}
