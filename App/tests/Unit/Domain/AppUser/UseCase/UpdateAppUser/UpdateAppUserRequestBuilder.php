<?php

namespace App\Tests\Domain\AppUser\UseCase\UpdateAppUser;

use App\Domain\AppUser\UseCase\UpdateAppUser\UpdateAppUserRequest;

class UpdateAppUserRequestBuilder extends UpdateAppUserRequest
{
    const APPUSER_ID = 1;
    const EMAIL = 'email2@test.io';
    const LASTNAME = 'De Tata';
    const FIRSTNAME = 'Tata';
    const COMPANY = 'Tata&Cie';

    public static function aRequest()
    {
        $request = new static(self::APPUSER_ID);

        $request->email = self::EMAIL;
        $request->lastname = self::LASTNAME;
        $request->firstname = self::FIRSTNAME;
        $request->company = self::COMPANY;

        return $request;
    }

    public function build()
    {
        $request = new UpdateAppUserRequest($this->id);
        
        $request->email = $this->email;
        $request->lastname = $this->lastname;
        $request->firstname = $this->firstname;
        $request->company = $this->company;

        return $request;
    }

    public function empty()
    {
        $this->email = null;
        $this->lastname = null;
        $this->firstname = null;
        $this->company = null;
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
}