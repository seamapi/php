<?php

namespace Seam\Exceptions;

class MissingInnerObjectException extends \Exception
{
 
    public function __construct(
        protected string $method, 
        protected string $path, 
        protected string $inner_object, 
        protected string $request_id,
    ) {
        $message = 'Missing Inner Object "' .
            $this->inner_object .
            '" for ' .
            $this->method .
            " " .
            $this->path .
            " [Request ID: " .
            $this->request_id .
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

    public function getInnerObjectName(): string
    {
        return $this->inner_object;
    }
}