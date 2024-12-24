<?php

namespace Seam\Exceptions;

class SeamHttpApiError extends \Exception {
    public string $errorCode;
    public int $statusCode;
    public string $requestId;
    public ?object $data;

    public function __construct(object $error, int $statusCode, string $requestId) {
        $message = $error->message ?? 'Unknown error';
        parent::__construct($message);
        $this->errorCode = $error->type ?? 'unknown';
        $this->statusCode = $statusCode;
        $this->requestId = $requestId;  
        $this->data = $error->data ?? null;
    }
}

class SeamHttpUnauthorizedError extends SeamHttpApiError {
    public function __construct(string $requestId) {
        $error = (object)[
            'type' => 'unauthorized',
            'message' => 'Unauthorized'
        ];
        parent::__construct($error, 401, $requestId);
    }
}

class SeamHttpInvalidInputError extends SeamHttpApiError {
    private object $validationErrors;

    public function __construct(object $error, int $statusCode, string $requestId) {
        parent::__construct($error, $statusCode, $requestId);
        $this->errorCode = 'invalid_input';
        $this->validationErrors = $error->validation_errors ?? (object)[];
    }

    public function getValidationErrorMessages(string $paramName): array {
        return $this->validationErrors->{$paramName}->_errors ?? [];
    }
}
