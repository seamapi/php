<?php

namespace Seam\Exceptions;

/**
 * Raised when the Seam API rejects the request parameters.
 */
class HttpInvalidInputError extends HttpApiError
{
    private object $validationErrors;

    public function __construct(
        object $error,
        int $statusCode,
        ?string $requestId,
    ) {
        parent::__construct($error, $statusCode, $requestId);
        $this->errorCode = "invalid_input";
        $this->validationErrors = $error->validation_errors ?? (object) [];
    }

    /**
     * The validation messages for a request parameter, or an empty array when
     * that parameter has none.
     *
     * @return string[]
     */
    public function getValidationErrorMessages(string $paramName): array
    {
        return $this->validationErrors->{$paramName}->_errors ?? [];
    }
}
