<?php

namespace Seam;

/**
 * Error thrown when a webhook payload passes signature verification but does
 * not carry a readable Seam event.
 */
class InvalidWebhookPayloadError extends \UnexpectedValueException implements
    SeamException {}
