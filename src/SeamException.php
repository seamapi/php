<?php

namespace Seam;

/**
 * Implemented by every exception this SDK raises, so callers can catch
 * anything originating from Seam with a single catch block.
 */
interface SeamException extends \Throwable {}
