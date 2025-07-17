<?php

namespace Seam\Objects;

class WorkspaceConnectWebviewCustomization
{
    public static function from_json(
        mixed $json,
    ): WorkspaceConnectWebviewCustomization|null {
        if (!$json) {
            return null;
        }
        return new self(
            inviter_logo_url: $json->inviter_logo_url ?? null,
            logo_shape: $json->logo_shape ?? null,
            primary_button_color: $json->primary_button_color ?? null,
            primary_button_text_color: $json->primary_button_text_color ?? null,
            success_message: $json->success_message ?? null,
        );
    }

    public function __construct(
        public string|null $inviter_logo_url,
        public string|null $logo_shape,
        public string|null $primary_button_color,
        public string|null $primary_button_text_color,
        public string|null $success_message,
    ) {}
}
