<?php

namespace App\Domain;

Abstract class CoreResponse
{
    protected array $errors;
    protected int $httpStatus;

    protected function __construct()
    {
        $this->errors = [];
    }

    public function addError(string $fieldName, string $error, int $httpStatus)
    {
        $this->errors[] = ['field' => $fieldName, 'message' => $error];
        $this->httpStatus = $httpStatus;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }
}