<?php

namespace App\Domain\AppUser\Entity;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema:"appUser",
    title:"app user"
)]
class AppUser
{
    #[OA\Property(
        title:"id",
        description:"user id",
        type:"integer",
        example:10
    )]
    public int $id;

    #[OA\Property(
        title:"email",
        description:"User's email",
        type:"string",
        format:"email",
        example:"email@email.test"
    )]
    private string $email;

    #[OA\Property(
        title:"password",
        description:"User's password - must have minimum 8 characters, 1 uppercase letter, 1 lowercase letter, 1 digit and 1 special character",
        type:"string",
        pattern:"/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%&^*-]).{8,}$/",
        example:"$2y$10$./m7/hhKacEd85Vu5tZeHOd9r4sUYF375GHRqEhaen/sfoixxYtRi"
    )]
    private string $password;

    #[OA\Property(
        title:"lastname",
        description:"User's last name",
        type:"string",
        example:"Doe"
    )]
    private string $lastname;

    #[OA\Property(
        title:"firstname",
        description:"User's first name",
        type:"string",
        example:"John"
    )]
    private string $firstname;

    #[OA\Property(
        title:"company",
        description:"User's company - optional",
        type:"string",
        example:"Johns&Cie",
        nullable:"true"
    )]
    private ?string $company;

    #[OA\Property(
        title:"role",
        description:"User's role",
        type:"string",
        example:"appUser"
    )]
    private string $role;

    #[OA\Property(
        title:"isEnable",
        description:"Is user enable",
        type:"boolean",
        example:true
    )]
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