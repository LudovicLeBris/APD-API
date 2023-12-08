<?php

namespace App\Presentation;

class JsonModel
{
    private $message;
    private $data;
    private $httpStatus;
    private $header;
    private $context;

    public function __construct(
        string $message,
        Object|array $data,
        int $httpStatus,
        array $header = [],
        array $context = []
    )
    {
        $this->message = $message;
        $this->data = $data;
        $this->httpStatus = $httpStatus;
        $this->header = $header;
        $this->context = $context;
    }

    public function getJsonResponse(): array
    {
        return [
            ["message" => $this->message, "content" => $this->data],
            $this->httpStatus,
            $this->header,
            $this->context
        ];
    }
    
    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    public function setHttpStatus($httpStatus)
    {
        $this->httpStatus = $httpStatus;

        return $this;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }
}