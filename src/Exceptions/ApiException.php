<?php

namespace Seam\Exceptions;

class ApiException extends \Exception
{
     public function __construct(
        protected string $method, 
        protected string $path, 
        protected string $type, 
        protected string $error, 
        protected string $request_id,
    ) {      
        $message = "Error Calling \"" .
            $method .
            " " .
            $path .
            "\" : " .
            ($type ?? "") .
            ": " .
            $error .
            " [Request ID: " .
            $request_id .
            "]";

        parent::__construct($message, 400);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getRequestId(): string
    {
        return $this->request_id;
    }

    public function getErrorType(): string
    {
        return $this->type;
    }

    public function getError(): string
    {
        return $this->error;
    }
}