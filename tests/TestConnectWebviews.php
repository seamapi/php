<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;
use GuzzleHttp\Client;


final class TestConnectWebviews extends TestCase
{
  public function testCreateWebview(): void
  {
    $seam = Fixture::getTestServer(true);
    $connect_webview = $seam->connect_webviews->create(accepted_providers: ["august"]);
    $this->assertIsString($connect_webview->connect_webview_id);
    $this->assertIsString($connect_webview->url);
    $this->assertTrue($connect_webview->status == 'pending');
  }

  public function testGetAndListConnectWebviews(): void
  {
    $seam = Fixture::getTestServer(true);
    $connect_webviews  = $seam->connect_webviews->list();
    $this->assertIsArray($connect_webviews);
    print_r($connect_webviews);

    $connect_webview_id = $connect_webviews[0]->connect_webview_id;
    $connect_webview = $seam->connect_webviews->get(connect_webview_id: $connect_webview_id);
    $this->assertTrue($connect_webview->connect_webview_id === $connect_webview_id);
  }
}
