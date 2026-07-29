<?php

namespace Seam\Routes;

use Seam\Resources\ConnectWebview;
use Seam\SeamClient;

class ConnectWebviewsClient
{
    private SeamClient $seam;

    public function __construct(SeamClient $seam)
    {
        $this->seam = $seam;
    }

    /**
     * Creates a new [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews).
     *
     * To enable a user to connect their devices or systems to Seam, they must sign in to their device or system account. To enable a user to sign in, you create a `connect_webview`. After creating the Connect Webview, you receive a URL that you can use to display the visual component of this Connect Webview for your user. You can open an iframe or new window to display the Connect Webview.
     *
     * You should make a new `connect_webview` for each unique login request. Each `connect_webview` tracks the user that signed in with it. You receive an error if you reuse a Connect Webview for the same user twice or if you use the same Connect Webview for multiple users.
     *
     * See also: [Connect Webview Process](https://docs.seam.co/core-concepts/connect-webviews/connect-webview-process).
     *
     * @param array $accepted_capabilities List of accepted device capabilities that restrict the types of devices that can be connected through the Connect Webview. If not provided, defaults will be determined based on the accepted providers.
     * @param array $accepted_providers Accepted device provider keys as an alternative to `provider_category`. Use this parameter to specify accepted providers explicitly. See [Customize the Brands to Display in Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-brands-to-display-in-your-connect-webviews). To list all provider keys, use [`/devices/list_device_providers`](https://docs.seam.co/api/devices/list_device_providers) with no filters.
     * @param bool $automatically_manage_new_devices Indicates whether newly-added devices should appear as [managed devices](https://docs.seam.co/core-concepts/devices/managed-and-unmanaged-devices). See also: [Customize the Behavior Settings of Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-behavior-settings-of-your-connect-webviews).
     * @param mixed $custom_metadata Custom metadata that you want to associate with the Connect Webview. Supports up to 50 JSON key:value pairs. [Adding custom metadata to a Connect Webview](https://docs.seam.co/core-concepts/connect-webviews/attaching-custom-data-to-the-connect-webview) enables you to store custom information, like customer details or internal IDs from your application. The custom metadata is then transferred to any [connected accounts](https://docs.seam.co/core-concepts/connected-accounts) that were connected using the Connect Webview, making it easy to find and filter these resources in your [workspace](https://docs.seam.co/core-concepts/workspaces). You can also [filter Connect Webviews by custom metadata](https://docs.seam.co/core-concepts/connect-webviews/filtering-connect-webviews-by-custom-metadata).
     * @param string $custom_redirect_failure_url Alternative URL that you want to redirect the user to on an error. If you do not set this parameter, the Connect Webview falls back to the `custom_redirect_url`.
     * @param string $custom_redirect_url URL that you want to redirect the user to after the provider login is complete.
     * @param string $customer_key Associate the Connect Webview, the connected account, and all resources under the connected account with a customer. If the connected account already exists, it will be associated with the customer. If the connected account already exists, but is already associated with a customer, the Connect Webview will show an error.
     * @param array $excluded_providers List of provider keys to exclude from the Connect Webview. These providers will not be shown when the user tries to connect an account.
     * @param string $provider_category Specifies the category of providers that you want to include. To list all providers within a category, use [`/devices/list_device_providers`](https://docs.seam.co/api/devices/list_device_providers) with the desired `provider_category` filter.
     * @param bool $wait_for_device_creation Indicates whether Seam should finish syncing all devices in a newly-connected account before completing the associated Connect Webview. See also: [Customize the Behavior Settings of Your Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/customizing-connect-webviews#customize-the-behavior-settings-of-your-connect-webviews).
     * @return ConnectWebview OK
     */
    public function create(
        ?array $accepted_capabilities = null,
        ?array $accepted_providers = null,
        ?bool $automatically_manage_new_devices = null,
        mixed $custom_metadata = null,
        ?string $custom_redirect_failure_url = null,
        ?string $custom_redirect_url = null,
        ?string $customer_key = null,
        ?array $excluded_providers = null,
        ?string $provider_category = null,
        ?bool $wait_for_device_creation = null,
    ): ConnectWebview {
        $request_payload = [];

        if ($accepted_capabilities !== null) {
            $request_payload["accepted_capabilities"] = $accepted_capabilities;
        }
        if ($accepted_providers !== null) {
            $request_payload["accepted_providers"] = $accepted_providers;
        }
        if ($automatically_manage_new_devices !== null) {
            $request_payload[
                "automatically_manage_new_devices"
            ] = $automatically_manage_new_devices;
        }
        if ($custom_metadata !== null) {
            $request_payload["custom_metadata"] = $custom_metadata;
        }
        if ($custom_redirect_failure_url !== null) {
            $request_payload[
                "custom_redirect_failure_url"
            ] = $custom_redirect_failure_url;
        }
        if ($custom_redirect_url !== null) {
            $request_payload["custom_redirect_url"] = $custom_redirect_url;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($excluded_providers !== null) {
            $request_payload["excluded_providers"] = $excluded_providers;
        }
        if ($provider_category !== null) {
            $request_payload["provider_category"] = $provider_category;
        }
        if ($wait_for_device_creation !== null) {
            $request_payload[
                "wait_for_device_creation"
            ] = $wait_for_device_creation;
        }

        $res = $this->seam->request(
            "POST",
            "/connect_webviews/create",
            json: (object) $request_payload,
        );

        return ConnectWebview::from_json($res->connect_webview);
    }

    /**
     * Deletes a [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews).
     *
     * You do not need to delete a Connect Webview once a user completes it. Instead, you can simply ignore completed Connect Webviews.
     *
     * @param string $connect_webview_id ID of the Connect Webview that you want to delete.
     * @return void OK
     */
    public function delete(string $connect_webview_id): void
    {
        $request_payload = [];

        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }

        $this->seam->request(
            "POST",
            "/connect_webviews/delete",
            json: (object) $request_payload,
        );
    }

    /**
     * Returns a specified [Connect Webview](https://docs.seam.co/core-concepts/connect-webviews).
     *
     * Unless you're using a `custom_redirect_url`, you should poll a newly-created `connect_webview` to find out if the user has signed in or to get details about what devices they've connected.
     *
     * @param string $connect_webview_id ID of the Connect Webview that you want to get.
     * @return ConnectWebview OK
     */
    public function get(string $connect_webview_id): ConnectWebview
    {
        $request_payload = [];

        if ($connect_webview_id !== null) {
            $request_payload["connect_webview_id"] = $connect_webview_id;
        }

        $res = $this->seam->request(
            "POST",
            "/connect_webviews/get",
            json: (object) $request_payload,
        );

        return ConnectWebview::from_json($res->connect_webview);
    }

    /**
     * Returns a list of all [Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews).
     *
     * @param mixed $custom_metadata_has Custom metadata pairs by which you want to [filter Connect Webviews](https://docs.seam.co/core-concepts/connect-webviews/filtering-connect-webviews-by-custom-metadata). Returns Connect Webviews with `custom_metadata` that contains all of the provided key:value pairs.
     * @param string $customer_key Customer key for which you want to list connect webviews.
     * @param float $limit Maximum number of records to return per page.
     * @param string $page_cursor Identifies the specific page of results to return, obtained from the previous page's `next_page_cursor`.
     * @param string $search String for which to search. Filters returned Connect Webviews to include all records that satisfy a partial match using `connect_webview_id`, `accepted_providers`, `custom_metadata`, or `customer_key`.
     * @param string $user_identifier_key Your user ID for the user by which you want to filter Connect Webviews.
     * @return array OK
     */
    public function list(
        mixed $custom_metadata_has = null,
        ?string $customer_key = null,
        ?float $limit = null,
        ?string $page_cursor = null,
        ?string $search = null,
        ?string $user_identifier_key = null,
        ?callable $on_response = null,
    ): array {
        $request_payload = [];

        if ($custom_metadata_has !== null) {
            $request_payload["custom_metadata_has"] = $custom_metadata_has;
        }
        if ($customer_key !== null) {
            $request_payload["customer_key"] = $customer_key;
        }
        if ($limit !== null) {
            $request_payload["limit"] = $limit;
        }
        if ($page_cursor !== null) {
            $request_payload["page_cursor"] = $page_cursor;
        }
        if ($search !== null) {
            $request_payload["search"] = $search;
        }
        if ($user_identifier_key !== null) {
            $request_payload["user_identifier_key"] = $user_identifier_key;
        }

        $res = $this->seam->request(
            "POST",
            "/connect_webviews/list",
            json: (object) $request_payload,
        );

        if ($on_response !== null) {
            $on_response($res);
        }

        return array_map(
            fn($r) => ConnectWebview::from_json($r),
            $res->connect_webviews,
        );
    }
}
