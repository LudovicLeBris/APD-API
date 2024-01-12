<?php

namespace App\Tests\Domain\AppUser\UseCase\Register;

use App\Domain\AppUser\UseCase\Register\RegisterRequest;

class RegisterRequestBuilder extends RegisterRequest
{
    const EMAIL = 'toto@toto.to';
    const PASSWORD = 'totoTOTO!t0t0';
    const LASTNAME = 'De Toto';
    const FIRSTNAME = 'Toto';
    const COMPANY = 'Toto&Cie';

    public static function aRequest()
    {
        $request = new static();
        $request->email = self::EMAIL;
        $request->password = self::PASSWORD;
        $request->lastname = self::LASTNAME;
        $request->firstname = self::FIRSTNAME;
        $request->company = self::COMPANY;

        return $request;
    }

    public function build()
    {
        $request = new RegisterRequest();
        $request->email = $this->email;
        $request->password = $this->password;
        $request->lastname = $this->lastname;
        $request->firstname = $this->firstname;
        $request->company = $this->company;

        return $request;
    }

    public function empty()
    {
        $this->email = null;
        $this->password = null;
        $this->lastname = null;
        $this->firstname = null;
        $this->company = null;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function setFirstnam(string $firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }


    public function setCompany(string $company)
    {
        $this->company = $company;

        return $this;
    }
}