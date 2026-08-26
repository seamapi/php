<?php

namespace Seam\Routes;

use GuzzleHttp\ClientInterface;
use Seam\Http\Body;
use Seam\Resources\CustomerPortal;

class CustomersClient
{
    private ClientInterface $client;

    /**
     * @var array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}}
     */
    private array $defaults;

    /**
     * @param array{wait_for_action_attempt: bool|array{timeout?: float, polling_interval?: float}} $defaults
     */
    public function __construct(ClientInterface $client, array $defaults)
    {
        $this->client = $client;
        $this->defaults = $defaults;
    }

    /**
     * Creates a new customer portal magic link with configurable features.
     *
     * @param mixed $customer_data
     * @param list<array<string, mixed>|\stdClass> $customer_resources_filters Filter configuration for resources based on their custom_metadata. Each filter specifies a field, operation, and value to match against resource custom_metadata.
     * @param string $customization_profile_id The ID of the customization profile to use for the portal.
     * @param mixed $deep_link Deep link target resource for initial redirect. When set, the portal will navigate directly to the specified resource.
     * @param bool $exclude_locale_picker Whether to exclude the option to select a locale within the portal UI.
     * @param mixed $features
     * @param bool $is_embedded Whether the portal is embedded in another application.
     * @param mixed $landing_page Configuration for the landing page when the portal loads.
     * @param string $locale The locale to use for the portal.
     * @param string $navigation_mode Navigation mode for the portal. 'restricted' tells frontend to hide navigation UI, typically used for embedded deep links.
     * @param bool $read_only Whether the portal is read-only. When true, the customer can browse the portal but cannot perform any mutating action; write requests made with the portal's client session are rejected.
     * @return CustomerPortal OK
     */
    public function create_portal(
        mixed $customer_data = null,
        ?array $customer_resources_filters = null,
        ?string $customization_profile_id = null,
        mixed $deep_link = null,
        ?bool $exclude_locale_picker = null,
        mixed $features = null,
        ?bool $is_embedded = null,
        mixed $landing_page = null,
        ?string $locale = null,
        ?string $navigation_mode = null,
        ?bool $read_only = null,
    ): CustomerPortal {
        $request_payload = [];

        if ($customer_data !== null) {
            $request_payload["customer_data"] = $customer_data;
        }
        if ($customer_resources_filters !== null) {
            $request_payload[
                "customer_resources_filters"
            ] = $customer_resources_filters;
        }
        if ($customization_profile_id !== null) {
            $request_payload[
                "customization_profile_id"
            ] = $customization_profile_id;
        }
        if ($deep_link !== null) {
            $request_payload["deep_link"] = $deep_link;
        }
        if ($exclude_locale_picker !== null) {
            $request_payload["exclude_locale_picker"] = $exclude_locale_picker;
        }
        if ($features !== null) {
            $request_payload["features"] = $features;
        }
        if ($is_embedded !== null) {
            $request_payload["is_embedded"] = $is_embedded;
        }
        if ($landing_page !== null) {
            $request_payload["landing_page"] = $landing_page;
        }
        if ($locale !== null) {
            $request_payload["locale"] = $locale;
        }
        if ($navigation_mode !== null) {
            $request_payload["navigation_mode"] = $navigation_mode;
        }
        if ($read_only !== null) {
            $request_payload["read_only"] = $read_only;
        }

        $res = Body::decode(
            $this->client->request("POST", "/customers/create_portal", [
                "json" => (object) $request_payload,
            ]),
        );

        return CustomerPortal::from_json(
            Body::read($res, "customer_portal", "/customers/create_portal"),
        );
    }

    /**
     * Deletes customer data including resources like spaces, properties, rooms, users, etc.
     * This will delete the partner resources and any related Seam resources (user identities, access grants, spaces).
     *
     * @param list<string> $access_grant_keys List of access grant keys to delete.
     * @param list<string> $booking_keys List of booking keys to delete.
     * @param list<string> $building_keys List of building keys to delete.
     * @param list<string> $common_area_keys List of common area keys to delete.
     * @param list<string> $customer_keys List of customer keys to delete all data for.
     * @param list<string> $facility_keys List of facility keys to delete.
     * @param list<string> $guest_keys List of guest keys to delete.
     * @param list<string> $listing_keys List of listing keys to delete.
     * @param list<string> $property_keys List of property keys to delete.
     * @param list<string> $property_listing_keys List of property listing keys to delete.
     * @param list<string> $reservation_keys List of reservation keys to delete.
     * @param list<string> $resident_keys List of resident keys to delete.
     * @param list<string> $room_keys List of room keys to delete.
     * @param list<string> $space_keys List of space keys to delete.
     * @param list<string> $staff_member_keys List of staff member keys to delete.
     * @param list<string> $tenant_keys List of tenant keys to delete.
     * @param list<string> $unit_keys List of unit keys to delete.
     * @param list<string> $user_identity_keys List of user identity keys to delete.
     * @param list<string> $user_keys List of user keys to delete.
     * @return void OK
     */
    public function delete_data(
        ?array $access_grant_keys = null,
        ?array $booking_keys = null,
        ?array $building_keys = null,
        ?array $common_area_keys = null,
        ?array $customer_keys = null,
        ?array $facility_keys = null,
        ?array $guest_keys = null,
        ?array $listing_keys = null,
        ?array $property_keys = null,
        ?array $property_listing_keys = null,
        ?array $reservation_keys = null,
        ?array $resident_keys = null,
        ?array $room_keys = null,
        ?array $space_keys = null,
        ?array $staff_member_keys = null,
        ?array $tenant_keys = null,
        ?array $unit_keys = null,
        ?array $user_identity_keys = null,
        ?array $user_keys = null,
    ): void {
        $request_payload = [];

        if ($access_grant_keys !== null) {
            $request_payload["access_grant_keys"] = $access_grant_keys;
        }
        if ($booking_keys !== null) {
            $request_payload["booking_keys"] = $booking_keys;
        }
        if ($building_keys !== null) {
            $request_payload["building_keys"] = $building_keys;
        }
        if ($common_area_keys !== null) {
            $request_payload["common_area_keys"] = $common_area_keys;
        }
        if ($customer_keys !== null) {
            $request_payload["customer_keys"] = $customer_keys;
        }
        if ($facility_keys !== null) {
            $request_payload["facility_keys"] = $facility_keys;
        }
        if ($guest_keys !== null) {
            $request_payload["guest_keys"] = $guest_keys;
        }
        if ($listing_keys !== null) {
            $request_payload["listing_keys"] = $listing_keys;
        }
        if ($property_keys !== null) {
            $request_payload["property_keys"] = $property_keys;
        }
        if ($property_listing_keys !== null) {
            $request_payload["property_listing_keys"] = $property_listing_keys;
        }
        if ($reservation_keys !== null) {
            $request_payload["reservation_keys"] = $reservation_keys;
        }
        if ($resident_keys !== null) {
            $request_payload["resident_keys"] = $resident_keys;
        }
        if ($room_keys !== null) {
            $request_payload["room_keys"] = $room_keys;
        }
        if ($space_keys !== null) {
            $request_payload["space_keys"] = $space_keys;
        }
        if ($staff_member_keys !== null) {
            $request_payload["staff_member_keys"] = $staff_member_keys;
        }
        if ($tenant_keys !== null) {
            $request_payload["tenant_keys"] = $tenant_keys;
        }
        if ($unit_keys !== null) {
            $request_payload["unit_keys"] = $unit_keys;
        }
        if ($user_identity_keys !== null) {
            $request_payload["user_identity_keys"] = $user_identity_keys;
        }
        if ($user_keys !== null) {
            $request_payload["user_keys"] = $user_keys;
        }

        $this->client->request("DELETE", "/customers/delete_data", [
            "query" => $request_payload,
        ]);
    }

    /**
     * Pushes customer data including resources like spaces, properties, rooms, users, etc.
     *
     * @param string $customer_key Your unique identifier for the customer.
     * @param list<array<string, mixed>|\stdClass> $access_grants List of access grants.
     * @param list<array<string, mixed>|\stdClass> $bookings List of bookings.
     * @param list<array<string, mixed>|\stdClass> $buildings List of buildings.
     * @param list<array<string, mixed>|\stdClass> $common_areas List of shared common areas.
     * @param list<array<string, mixed>|\stdClass> $facilities List of gym or fitness facilities.
     * @param list<array<string, mixed>|\stdClass> $guests List of guests.
     * @param list<array<string, mixed>|\stdClass> $listings List of property listings.
     * @param list<array<string, mixed>|\stdClass> $properties List of short-term rental properties.
     * @param list<array<string, mixed>|\stdClass> $property_listings List of property listings.
     * @param list<array<string, mixed>|\stdClass> $reservations List of reservations.
     * @param list<array<string, mixed>|\stdClass> $residents List of residents.
     * @param list<array<string, mixed>|\stdClass> $rooms List of hotel or hospitality rooms.
     * @param list<array<string, mixed>|\stdClass> $sites List of general sites or areas.
     * @param list<array<string, mixed>|\stdClass> $spaces List of general spaces or areas.
     * @param list<array<string, mixed>|\stdClass> $staff_members List of staff members.
     * @param list<array<string, mixed>|\stdClass> $tenants List of tenants.
     * @param list<array<string, mixed>|\stdClass> $units List of multi-family residential units.
     * @param list<array<string, mixed>|\stdClass> $user_identities List of user identities.
     * @param list<array<string, mixed>|\stdClass> $users List of users.
     * @return void OK
     */
    public function push_data(
        string $customer_key,
        ?array $access_grants = null,
        ?array $bookings = null,
        ?array $buildings = null,
        ?array $common_areas = null,
        ?array $facilities = null,
        ?array $guests = null,
        ?array $listings = null,
        ?array $properties = null,
        ?array $property_listings = null,
        ?array $reservations = null,
        ?array $residents = null,
        ?array $rooms = null,
        ?array $sites = null,
        ?array $spaces = null,
        ?array $staff_members = null,
        ?array $tenants = null,
        ?array $units = null,
        ?array $user_identities = null,
        ?array $users = null,
    ): void {
        $request_payload = [];

        $request_payload["customer_key"] = $customer_key;
        if ($access_grants !== null) {
            $request_payload["access_grants"] = $access_grants;
        }
        if ($bookings !== null) {
            $request_payload["bookings"] = $bookings;
        }
        if ($buildings !== null) {
            $request_payload["buildings"] = $buildings;
        }
        if ($common_areas !== null) {
            $request_payload["common_areas"] = $common_areas;
        }
        if ($facilities !== null) {
            $request_payload["facilities"] = $facilities;
        }
        if ($guests !== null) {
            $request_payload["guests"] = $guests;
        }
        if ($listings !== null) {
            $request_payload["listings"] = $listings;
        }
        if ($properties !== null) {
            $request_payload["properties"] = $properties;
        }
        if ($property_listings !== null) {
            $request_payload["property_listings"] = $property_listings;
        }
        if ($reservations !== null) {
            $request_payload["reservations"] = $reservations;
        }
        if ($residents !== null) {
            $request_payload["residents"] = $residents;
        }
        if ($rooms !== null) {
            $request_payload["rooms"] = $rooms;
        }
        if ($sites !== null) {
            $request_payload["sites"] = $sites;
        }
        if ($spaces !== null) {
            $request_payload["spaces"] = $spaces;
        }
        if ($staff_members !== null) {
            $request_payload["staff_members"] = $staff_members;
        }
        if ($tenants !== null) {
            $request_payload["tenants"] = $tenants;
        }
        if ($units !== null) {
            $request_payload["units"] = $units;
        }
        if ($user_identities !== null) {
            $request_payload["user_identities"] = $user_identities;
        }
        if ($users !== null) {
            $request_payload["users"] = $users;
        }

        $this->client->request("POST", "/customers/push_data", [
            "json" => (object) $request_payload,
        ]);
    }
}
