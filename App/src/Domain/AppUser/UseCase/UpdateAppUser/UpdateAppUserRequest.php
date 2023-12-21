<?php

namespace App\Domain\AppUser\UseCase\UpdateAppUser;

class UpdateAppUserRequest
{
    public $id;
    public $email;
    public $lastname;
    public $firstname;
    public $company;

    public function __construct(int $AppUserId)
    {
        $this->id = $AppUserId;
    }

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