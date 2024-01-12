<?php

namespace App\SharedKernel\Service;

class PasswordHasher  
{
    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function isPasswordValid(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }
}