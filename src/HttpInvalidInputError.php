<?php

namespace Seam;

/**
 * Raised when the Seam API rejects the request parameters.
 */
class HttpInvalidInputError extends HttpApiError
{
    private object $rawValidationErrors;

    /**
     * Validation errors, one entry per failed request parameter.
     *
     * @var list<ValidationError>
     */
    public readonly array $validation_errors;

    public function __construct(
        object $error,
        int $statusCode,
        ?string $requestId,
    ) {
        parent::__construct($error, $statusCode, $requestId);
        $this->errorCode = "invalid_input";
        $this->rawValidationErrors = $error->validation_errors ?? (object) [];

        $validationErrors = [];
        foreach (
            get_object_vars($this->rawValidationErrors)
            as $paramName => $_
        ) {
            if ($paramName !== "_errors") {
                $validationErrors[] = new ValidationError(
                    $paramName,
                    $this->getValidationErrorMessages($paramName),
                );
            }
        }
        $this->validation_errors = $validationErrors;
    }

    /**
     * The validation messages for a request parameter, or an empty array when
     * that parameter has none.
     *
     * @return string[]
     */
    public function getValidationErrorMessages(string $paramName): array
    {
        return $this->rawValidationErrors->{$paramName}->_errors ?? [];
    }
}
