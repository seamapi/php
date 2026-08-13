<?php

namespace Seam;

/**
 * Builds the authorization headers for a Seam client.
 *
 * Two authentication methods are supported: an API key, which is scoped to a
 * single workspace, and a personal access token, which is scoped to a Seam
 * Console user and must name the workspace it acts on.
 */
final class Auth
{
    /**
     * @return array<string, string>
     */
    public static function get_auth_headers(
        ?string $api_key = null,
        ?string $personal_access_token = null,
        ?string $workspace_id = null,
    ): array {
        // The environment is only consulted when no credential was passed at
        // all, so an explicit personal access token is not second guessed by
        // a stray SEAM_API_KEY.
        if ($api_key === null && $personal_access_token === null) {
            $api_key = Options::get_env("SEAM_API_KEY");
            $personal_access_token = Options::get_env(
                "SEAM_PERSONAL_ACCESS_TOKEN",
            );

            if ($api_key !== null && $personal_access_token !== null) {
                throw new InvalidOptionsError(
                    "Both SEAM_API_KEY and SEAM_PERSONAL_ACCESS_TOKEN environment variables are defined. " .
                        "Please use only one authentication method.",
                );
            }
        }

        $workspace_id ??= Options::get_env("SEAM_WORKSPACE_ID");

        if (
            Options::is_seam_options_with_api_key(
                $api_key,
                $personal_access_token,
            )
        ) {
            return self::get_auth_headers_for_api_key($api_key);
        }

        if (
            Options::is_seam_options_with_personal_access_token(
                $personal_access_token,
                $api_key,
                $workspace_id,
            )
        ) {
            return self::get_auth_headers_for_personal_access_token(
                $personal_access_token,
                $workspace_id,
            );
        }

        throw new InvalidOptionsError(
            "Must specify an api_key or personal_access_token. " .
                "Attempted reading configuration from the environment, but neither the " .
                "SEAM_API_KEY nor the SEAM_PERSONAL_ACCESS_TOKEN environment variable is set.",
        );
    }

    /**
     * Builds the headers for a client that is not scoped to a workspace,
     * falling back to the environment when no token is given.
     *
     * @return array<string, string>
     */
    public static function get_auth_headers_without_workspace(
        ?string $personal_access_token = null,
    ): array {
        $personal_access_token ??= Options::get_env(
            "SEAM_PERSONAL_ACCESS_TOKEN",
        );

        if ($personal_access_token === null) {
            throw new InvalidOptionsError(
                "Must specify a personal_access_token. " .
                    "Attempted reading configuration from the environment, " .
                    "but the environment variable SEAM_PERSONAL_ACCESS_TOKEN is not set.",
            );
        }

        return self::get_auth_headers_for_personal_access_token_without_workspace(
            $personal_access_token,
        );
    }

    /**
     * @return array<string, string>
     */
    public static function get_auth_headers_for_api_key(string $api_key): array
    {
        if (Token::is_client_session_token($api_key)) {
            throw new InvalidTokenError(
                "A Client Session Token cannot be used as an api_key",
            );
        }

        if (Token::is_jwt($api_key)) {
            throw new InvalidTokenError("A JWT cannot be used as an api_key");
        }

        if (Token::is_access_token($api_key)) {
            throw new InvalidTokenError(
                "An Access Token cannot be used as an api_key",
            );
        }

        if (Token::is_publishable_key($api_key)) {
            throw new InvalidTokenError(
                "A Publishable Key cannot be used as an api_key",
            );
        }

        if (!Token::is_seam_token($api_key)) {
            throw new InvalidTokenError("Unknown or invalid api_key format");
        }

        return ["authorization" => "Bearer " . $api_key];
    }

    /**
     * @return array<string, string>
     */
    public static function get_auth_headers_for_personal_access_token(
        string $personal_access_token,
        string $workspace_id,
    ): array {
        self::assert_personal_access_token($personal_access_token);

        return [
            "authorization" => "Bearer " . $personal_access_token,
            "seam-workspace" => $workspace_id,
        ];
    }

    /**
     * Headers for a personal access token that is not scoped to a workspace.
     *
     * @return array<string, string>
     */
    public static function get_auth_headers_for_personal_access_token_without_workspace(
        string $personal_access_token,
    ): array {
        self::assert_personal_access_token($personal_access_token);

        return ["authorization" => "Bearer " . $personal_access_token];
    }

    private static function assert_personal_access_token(string $token): void
    {
        if (Token::is_client_session_token($token)) {
            throw new InvalidTokenError(
                "A Client Session Token cannot be used as a personal_access_token",
            );
        }

        if (Token::is_jwt($token)) {
            throw new InvalidTokenError(
                "A JWT cannot be used as a personal_access_token",
            );
        }

        if (Token::is_publishable_key($token)) {
            throw new InvalidTokenError(
                "A Publishable Key cannot be used as a personal_access_token",
            );
        }

        if (!Token::is_access_token($token)) {
            throw new InvalidTokenError(
                "Unknown or invalid personal_access_token format",
            );
        }
    }
}
