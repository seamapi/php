<?php

namespace Seam\Objects;

class CustomizationProfile
{
    public static function from_json(mixed $json): CustomizationProfile|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            customer_portal_theme: isset($json->customer_portal_theme)
                ? CustomizationProfileCustomerPortalTheme::from_json(
                    $json->customer_portal_theme,
                )
                : null,
            customization_profile_id: $json->customization_profile_id ?? null,
            logo_url: $json->logo_url ?? null,
            message_overrides: $json->message_overrides ?? null,
            name: $json->name ?? null,
            primary_color: $json->primary_color ?? null,
            secondary_color: $json->secondary_color ?? null,
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        public string|null $created_at,
        public CustomizationProfileCustomerPortalTheme|null $customer_portal_theme,
        public string|null $customization_profile_id,
        public string|null $logo_url,
        public mixed $message_overrides,
        public string|null $name,
        public string|null $primary_color,
        public string|null $secondary_color,
        public string|null $workspace_id,
    ) {}
}
