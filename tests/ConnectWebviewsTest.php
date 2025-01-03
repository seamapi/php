<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Tests\Fixture;

final class ConnectWebviewsTest extends TestCase
{
    public function testCreateWebview(): void
    {
        $seam = Fixture::getTestServer();
        $connect_webview = $seam->connect_webviews->create(
            accepted_providers: ["august"]
        );
        $this->assertIsString($connect_webview->connect_webview_id);
        $this->assertIsString($connect_webview->url);
        $this->assertNull($connect_webview->custom_redirect_url);
        $this->assertNull($connect_webview->custom_redirect_failure_url);
        $this->assertTrue($connect_webview->status == "pending");
    }

    public function testCreateWebviewWithRedirectUrl(): void
    {
        $seam = Fixture::getTestServer();
        $connect_webview = $seam->connect_webviews->create(
            accepted_providers: ["august"],
            custom_redirect_url: "https://seam.co/"
        );
        $this->assertIsString($connect_webview->connect_webview_id);
        $this->assertIsString($connect_webview->url);
        $this->assertEquals(
            $connect_webview->custom_redirect_url,
            "https://seam.co/"
        );
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
        $this->assertTrue(
            count(
                array_filter($connect_webviews, function ($connect_webview) {
                    return !empty($connect_webview->connected_account_id ?? "");
                })
            ) > 0
        );
    }
}
