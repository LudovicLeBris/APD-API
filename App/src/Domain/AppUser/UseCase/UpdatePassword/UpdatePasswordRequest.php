<?php

namespace App\Domain\AppUser\UseCase\UpdatePassword;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request:"recoverPassword",
    required:"true",
    content: new OA\JsonContent(
        properties:[
            new OA\Property(
                property:"newPassword",
                title:"password",
                description:"User's new password - must have minimum 8 characters, 1 uppercase letter, 1 lowercase letter, 1 digit and 1 special character",
                type:"string",
                pattern:"/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%&^*-]).{8,}$/",
                example:"Azert?123"
            )
        ]
    )
)]
#[OA\RequestBody(
    request:"updatePassword",
    required:"true",
    content: new OA\JsonContent(
        properties:[
            new OA\Property(
                property:"oldPpassword",
                title:"oldPassword",
                description:"User's old password",
                type:"string",
                pattern:"/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%&^*-]).{8,}$/",
                example:"Azert?123"
            ),
            new OA\Property(
                property:"newPassword",
                title:"newPassword",
                description:"User's new password - must have minimum 8 characters, 1 uppercase letter, 1 lowercase letter, 1 digit and 1 special character",
                type:"string",
                pattern:"/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%&^*-]).{8,}$/",
                example:"Azert123?"
            )
        ]
    )
)]
class UpdatePasswordRequest
{
    public $id = null;
    public $guid = null;
    public $oldPassword = null;
    public $newPassword;

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

    public function setId(int $id): static
    {
        $this->id = $id;
        $this->guid = null;

        return $this;
    }

    public function setGuid(string $guid): static
    {
        $this->guid = $guid;
        $this->id = null;

        return $this;
    }
}