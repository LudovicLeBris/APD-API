<?php

namespace App\SharedKernel\Service;

class TokenGenerator
{
    private $token;

    public function __construct(int $length = 16)
    {
        $string = sha1(rand());
        $randomString = substr($string, 0, $length);

        $this->token = $randomString;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}