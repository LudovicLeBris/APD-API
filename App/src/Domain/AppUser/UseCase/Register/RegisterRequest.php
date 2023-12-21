<?php

namespace App\Domain\AppUser\UseCase\Register;

class RegisterRequest
{
    public $email;
    public $password;
    public $lastname;
    public $firstname;
    public $company = null;

    public function setContent($requestContent)
    {
        if ($requestContent) {
            foreach ($requestContent as $field => $value) {
                if (property_exists($this, $field)) {
                    $this->$field = $value;
                }
            }
        }

        return $this;
    }
}