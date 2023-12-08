<?php

namespace App\Domain;

class CoreResponse
{
    protected array $errors;

    protected function __construct()
    {
        $this->errors = [];
    }

    public function addError(string $fieldName, string $error)
    {
        $this->errors[] = ['field' => $fieldName, 'message' => $error];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}