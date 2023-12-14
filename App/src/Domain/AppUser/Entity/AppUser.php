<?php

namespace App\Domain\AppUser\Entity;

class AppUser
{
    public int $id;
    private string $email;
    private string $password;
    private string $lastname;
    private string $firstname;
    private ?string $company;
    private string $role;
    private bool $isEnable;

    public function __construct(
        string $email,
        string $password,
        string $lastname,
        string $firstname,
        string $company = null,
        string $role,
        bool $isEnable

    )
    {
        $this->email = $email;
        $this->password = $password;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->company = $company;
        $this->role = $role;
        $this->isEnable = $isEnable;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getIsEnable(): bool
    {
        return $this->isEnable;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }
    
    public function setIsEnable(bool $isEnable): static
    {
        $this->isEnable = $isEnable;
        
        return $this;
    }
}