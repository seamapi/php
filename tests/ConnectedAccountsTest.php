<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;

final class ConnectedAccountsTest extends TestCase
{
    public function testGetAndListConnectedAccounts(): void
    {
        $seam = Fixture::getTestServer();
        $connected_accounts = $seam->connected_accounts->list();
        $this->assertIsArray($connected_accounts);

        $connected_account_id = $connected_accounts[0]->connected_account_id;
        $connected_account = $seam->connected_accounts->get(
            connected_account_id: $connected_account_id
        );
        $this->assertTrue(
            $connected_account->connected_account_id === $connected_account_id
        );
    }

    public function testDeleteConnectedAccount(): void
    {
        $seam = Fixture::getTestServer();
        $connected_accounts = $seam->connected_accounts->list();

        $connected_account_id = $connected_accounts[0]->connected_account_id;

        $connected_account = $seam->connected_accounts->get(
            connected_account_id: $connected_account_id
        );
        $this->assertTrue(
            $connected_account->connected_account_id === $connected_account_id
        );

        $seam->connected_accounts->delete(
            connected_account_id: $connected_account_id
        );

        try {
            $connected_account = $seam->connected_accounts->get(
                connected_account_id: $connected_account_id
            );
            $this->fail("Expected the account to be deleted");
        } catch (\Seam\HttpApiError $exception) {
            $this->assertTrue(
                str_contains(
                    $exception->getErrorCode(),
                    "connected_account_not_found"
                )
            );
        }
    }

    public function testUpdateConnectedAccount(): void
    {
        $seam = Fixture::getTestServer();
        $connected_accounts = $seam->connected_accounts->list();

        $connected_account_id = $connected_accounts[0]->connected_account_id;

        $connected_account = $seam->connected_accounts->get(
            connected_account_id: $connected_account_id
        );
        $this->assertTrue(
            $connected_account->automatically_manage_new_devices === true
        );

        $seam->connected_accounts->update(
            connected_account_id: $connected_account_id,
            automatically_manage_new_devices: false
        );

        $connected_account = $seam->connected_accounts->get(
            connected_account_id: $connected_account_id
        );
        $this->assertTrue(
            $connected_account->automatically_manage_new_devices === false
        );
    }
}
