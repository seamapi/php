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
            created_at: $json->created_at,
            customization_profile_id: $json->customization_profile_id,
            workspace_id: $json->workspace_id,
            customer_portal_theme: isset($json->customer_portal_theme)
                ? CustomizationProfileCustomerPortalTheme::from_json(
                    $json->customer_portal_theme,
                )
                : null,
            logo_url: $json->logo_url ?? null,
            primary_color: $json->primary_color ?? null,
            secondary_color: $json->secondary_color ?? null,
            name: $json->name ?? null,
        );
    }

    public function __construct(
        public string $created_at,
        public string $customization_profile_id,
        public string $workspace_id,
        public CustomizationProfileCustomerPortalTheme|null $customer_portal_theme,
        public string|null $logo_url,
        public string|null $primary_color,
        public string|null $secondary_color,
        public string|null $name,
    ) {}
}
