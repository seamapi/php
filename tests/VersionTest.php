<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Seam\Version;

final class VersionTest extends TestCase
{
    public function testVersionMatchesPackageJson(): void
    {
        $path = __DIR__ . "/../package.json";
        $contents = file_get_contents($path);
        $this->assertIsString($contents, "Could not read $path");

        $package = json_decode($contents, true);
        $this->assertIsArray($package, "Could not decode $path");
        $this->assertArrayHasKey("version", $package);

        $this->assertSame(
            $package["version"],
            Version::get(),
            "Seam\\Version is out of date with package.json. " .
                "It is injected by the version lifecycle script when a " .
                "version is cut and should not be edited by hand.",
        );
    }

    public function testVersionIsUsedAsTheSdkVersionHeader(): void
    {
        $seam = new \Seam\Seam("seam_apikey1_token");
        $headers = $seam->client->getConfig("headers");

        $this->assertSame(Version::get(), $headers["seam-sdk-version"]);
    }
}
