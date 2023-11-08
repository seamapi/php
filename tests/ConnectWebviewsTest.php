<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use GuzzleHttp\Client;

final class ConnectWebviewsTest extends TestCase
{
  public function testCreateWebview(): void
  {
    $seam = Fixture::getTestServer();
    $connect_webview = $seam->connect_webviews->create(
      accepted_providers: ["august"]
    );
    echo json_encode($connect_webview, JSON_PRETTY_PRINT);
    $this->assertIsString($connect_webview->connect_webview_id);
    $this->assertIsString($connect_webview->url);
    $this->assertNull($connect_webview->custom_redirect_url);
    $this->assertNull($connect_webview->custom_redirect_failure_url);
    $this->assertTrue($connect_webview->status == "pending");
  }

  /*
   {
   url: 'http://localhost:3020/connect_webviews/view?connect_webview_id=3bd045f8-9316-46b6-9ec0-8fdba8557275&auth_token=L34mChm6t5YER1DiaiNikAJ4FcSx3PR8D',
   status: 'pending',
   created_at: '2023-10-27T12:43:02.20761+00:00',
   workspace_id: '821e7758-1baa-4b4e-ba7d-4764d714f560',
   custom_metadata: {},
   accepted_devices: [],
   login_successful: false,
   accepted_providers: [ 'august' ],
   any_device_allowed: null,
   connect_webview_id: '3bd045f8-9316-46b6-9ec0-8fdba8557275',
   custom_redirect_url: null,
   any_provider_allowed: false,
   device_selection_mode: 'none',
   third_party_account_id: null,
   wait_for_device_creation: false,
   custom_redirect_failure_url: null,
   automatically_manage_new_devices: true
 }
  */

  public function testCreateWebviewWithRedirectUrl(): void
  {
    $seam = Fixture::getTestServer();
    $connect_webview = $seam->connect_webviews->create(
      accepted_providers: ["august"],
      custom_redirect_url: "https://seam.co/"
    );
    $this->assertIsString($connect_webview->connect_webview_id);
    // $this->assertIsString($connect_webview->url);
    $this->assertEquals($connect_webview->custom_redirect_url, "https://seam.co/");
    $this->assertTrue($connect_webview->status == "pending");
  }


  public function testGetAndListConnectWebviews(): void
  {
    $seam = Fixture::getTestServer();
    $connect_webviews = $seam->connect_webviews->list();
    $this->assertIsArray($connect_webviews);

    $connect_webview_id = $connect_webviews[0]->connect_webview_id;
    $connect_webview = $seam->connect_webviews->get(
      connect_webview_id: $connect_webview_id
    );
    $this->assertTrue(
      $connect_webview->connect_webview_id === $connect_webview_id
    );
    $connect_webviews = $seam->connect_webviews->list();
    $this->assertTrue(count(array_filter($connect_webviews, 
      function($connect_webview) { 
        return !empty($connect_webview->connected_account_id ?? ''); 
      })) > 0);
  }
}
