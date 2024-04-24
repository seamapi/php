<?php

namespace Seam\Utils;

class PackageVersionException extends \Exception
{
}

class PackageVersion
{
    public static function get()
    {
        $filePath = __DIR__ . "/../../package.json";

        if (!file_exists($filePath)) {
            throw new PackageVersionException(
                "Can't get package version. File package.json does not exist."
            );
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new PackageVersionException(
                "Unable to read package.json file to get package version."
            );
        }

        $json = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new PackageVersionException(
                "JSON decode error occurred when decoding package.json: " .
                    json_last_error_msg()
            );
        }

        if (!isset($json["version"])) {
            throw new PackageVersionException(
                "Version not set in package.json"
            );
        }

        return $json["version"];
    }
}
