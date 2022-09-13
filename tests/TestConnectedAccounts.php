<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use GuzzleHttp\Client;


final class TestConnectedAccounts extends TestCase
{
  public function testGetAndListConnectedAccounts(): void
  {
    $seam = Fixture::getTestServer(true);
    $connected_accounts = $seam->connected_accounts->list();
    $this->assertIsArray($connected_accounts);

    $connected_account_id = $connected_accounts[0]->connected_account_id;
    $connected_account = $seam->connected_accounts->get(connected_account_id: $connected_account_id);
    $this->assertTrue($connected_account->connected_account_id === $connected_account_id);
  }
}
