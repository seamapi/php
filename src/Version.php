<?php

namespace Seam;

class Version
{
    /**
     * The version of this package.
     *
     * Injected from package.json when a version is cut, by the version
     * lifecycle script in package.json. Do not edit by hand.
     */
    public const VERSION = "4.4.0";

    public static function get(): string
    {
        return self::VERSION;
    }
}
