<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;

final class AccessCodesTest extends TestCase
{
    public function testAccessCodes(): void
    {
        $seam = Fixture::getTestServer();

        $devices = $seam->devices->list();
        $first_device_id = $devices[0]->device_id;
        $second_device_id = $devices[1]->device_id;

        $access_codes = $seam->access_codes->list(device_id: $first_device_id);
        $this->assertIsArray($access_codes);

        $seam->access_codes->create(device_id: $first_device_id, code: "1235");
        $seam->access_codes->create(device_id: $first_device_id, code: "3456");

        $access_codes = $seam->access_codes->list(device_id: $first_device_id);
        $this->assertTrue(count($access_codes) > 0);

        $access_code_id = $access_codes[0]->access_code_id;
        $access_codes = $seam->access_codes->list(
            device_id: $first_device_id,
            access_code_ids: [$access_code_id]
        );
        $this->assertTrue(count($access_codes) === 1);

        $access_code = $seam->access_codes->get(
            access_code_id: $access_code_id
        );
        $this->assertTrue($access_code->access_code_id === $access_code_id);

        $access_code = $seam->access_codes->get(
            device_id: $first_device_id,
            code: "1235"
        );
        $this->assertTrue($access_code->code === "1235");

        $seam->access_codes->update(
            access_code_id: $access_code_id,
            code: "5679"
        );
        $access_code = $seam->access_codes->get(
            access_code_id: $access_code_id
        );
        $this->assertTrue($access_code->code === "5679");

        $action_attempt = $seam->access_codes->delete(
            access_code_id: $access_code_id
        );

        try {
            $seam->access_codes->get(access_code_id: $access_code_id);

            $this->fail("Expected the code to be deleted");
        } catch (\Seam\HttpApiError $exception) {
            $this->assertTrue(
                str_contains($exception->getMessage(), "Access Code Not Found")
            );
        }

        $start_date = new DateTime("+2 months");
        $end_date = new DateTime("+3 months");

        $time_bound_access_code = $seam->access_codes->create(
            device_id: $first_device_id,
            code: "5679",
            starts_at: $start_date->format(DateTime::ATOM),
            ends_at: $end_date->format(DateTime::ATOM)
        );

        $formatted_starts_at = (new DateTime(
            $time_bound_access_code->starts_at
        ))->format("Y-m-d");
        $formatted_ends_at = (new DateTime(
            $time_bound_access_code->ends_at
        ))->format("Y-m-d");

        $seam->access_codes->update(
            access_code_id: $time_bound_access_code->access_code_id,
            type: "ongoing"
        );
        $updated_access_code = $seam->access_codes->get(
            access_code_id: $time_bound_access_code->access_code_id
        );
        $this->assertTrue($updated_access_code->type === "ongoing");

        $this->assertTrue(
            $formatted_starts_at === $start_date->format("Y-m-d")
        );
        $this->assertTrue($formatted_ends_at === $end_date->format("Y-m-d"));

        $access_codes = $seam->access_codes->create_multiple(
            device_ids: [$first_device_id, $second_device_id]
        );
        $this->assertTrue(count($access_codes) === 2);
    }

    public function testUnmanagedAccessCodes(): void
    {
        $seam = Fixture::getTestServer();

        $device_id = $seam->devices->list()[0]->device_id;

        $managed_access_codes_before_update = $seam->access_codes->list(
            device_id: $device_id
        );

        $seam->access_codes->simulate->create_unmanaged_access_code(
            device_id: $device_id,
            name: "Test Unmanaged Code",
            code: "1234"
        );

        $unmanaged_access_codes = $seam->access_codes->unmanaged->list(
            device_id: $device_id
        );
        $this->assertTrue(count($unmanaged_access_codes) === 1);
        $this->assertTrue($unmanaged_access_codes[0]->is_managed === false);

        $unmanaged_access_code_id = $unmanaged_access_codes[0]->access_code_id;
        $seam->access_codes->unmanaged->update(
            access_code_id: $unmanaged_access_code_id,
            is_managed: true
        );

        usleep(200000);

        $current_managed_access_codes = $seam->access_codes->list(
            device_id: $device_id
        );
        $this->assertTrue(
            count($managed_access_codes_before_update) + 1 ===
                count($current_managed_access_codes)
        );
        $this->assertTrue(
            !empty(
                array_filter($current_managed_access_codes, function ($ac) use (
                    $unmanaged_access_code_id
                ) {
                    return $ac->access_code_id === $unmanaged_access_code_id;
                })
            )
        );
    }
}
