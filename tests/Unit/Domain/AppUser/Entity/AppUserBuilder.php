<?php

namespace App\Tests\Domain\AppUser\Entity;

use App\Domain\AppUser\Entity\AppUser;
use App\SharedKernel\Service\PasswordHasher;

class AppUserBuilder 
{
    private $id = null;
    private $email = 'toto@toto.to';
    private $password = '$2y$10$m4UvbA/.hB5NTxaNp.lekOvaiF9P/Revx1hoVpJdSbjaODLJs7CS.';
    private $lastname = 'De Toto';
    private $firstname = 'Toto';
    private $company = 'Toto&Cie';
    private $role = 'appUser';
    private $isEnable = true;

    public function build(): AppUser
    {
        $appUser = new AppUser(
            $this->email,
            $this->password,
            $this->lastname,
            $this->firstname,
            $this->company,
            $this->role,
            $this->isEnable
        );

        $appUser->setId($this->id);

        return $appUser;
    }

    public static function anAppUser()
    {
        return new AppUserBuilder;
    }

    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(string $password)
    {
        $this->password = (new PasswordHasher)->hash($password);

        return $this;
    }

    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function setCompany(string $company)
    {
        $this->company = $company;

        return $this;
    }

    public function setRole(string $role)
    {
        $this->role = $role;

        return $this;
    }

    public function setIsEnable(bool $isEnable)
    {
        $this->isEnable = $isEnable;

        return $this;
    }
}