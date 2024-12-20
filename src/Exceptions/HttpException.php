<?php

namespace Seam\Exceptions;

class HttpException extends \Exception
{
     public function __construct(
        protected string $method, 
        protected string $path, 
        protected string $error, 
        protected string $request_id,
        protected int $status_code, 
    ) {      
        $message = "HTTP Error: " .
            $error .
            " [" .
            $status_code .
            "] " .
            $method .
            " " .
            $path .
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

    public function getError(): string
    {
        return $this->error;
    }

    public function getStatusCode(): string
    {
        return $this->status_code;
    }
}