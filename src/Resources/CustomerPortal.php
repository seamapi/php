<?php

namespace Seam\Resources;

/**
 * Represents a Customer Portal. Customer Portal is a hosted, customizable interface for managing device access. It enables you to embed secure, pre-authenticated access flows into your product—either by sharing a link with users or embedding a view in an iframe.
 *
 * With Customer Portal, you no longer need to build out frontend experiences for physical access, thermostats, and sensors. Instead, you can ship enterprise-grade access control experiences in a fraction of the time, while maintaining your product's branding and user experience.
 *
 * Seam hosts these flows, handling everything from account connection and device mapping to full-featured device control.
 */
class CustomerPortal
{
    public static function from_json(mixed $json): CustomerPortal|null
    {
        if (!$json) {
            return null;
        }
        return new self(
            created_at: $json->created_at ?? null,
            customer_key: $json->customer_key ?? null,
            expires_at: $json->expires_at ?? null,
            url: $json->url ?? null,
            workspace_id: $json->workspace_id ?? null,
        );
    }

    public function __construct(
        /**
         * Date and time at which the customer portal link was created.
         */
        public string|null $created_at,
        /**
         * Customer key for the customer portal.
         */
        public string|null $customer_key,
        /**
         * Date and time at which the customer portal link expires.
         */
        public string|null $expires_at,
        /**
         * URL for the customer portal.
         */
        public string|null $url,
        /**
         * ID of the workspace associated with the customer portal.
         */
        public string|null $workspace_id,
    ) {}
}
