<?php

namespace App\Domain\AppUser\UseCase\Register;

use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request:"register",
    required:"true",
    content: new OA\JsonContent(
        properties: [
            new OA\Property(
                property:"email",
                title:"email",
                description:"User's email",
                type:"string",
                format:"email",
                example:"email@email.test"
            ),
            new OA\Property(
                property:"password",
                title:"password",
                description:"User's password - must have minimum 8 characters, 1 uppercase letter, 1 lowercase letter, 1 digit and 1 special character",
                type:"string",
                pattern:"/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%&^*-]).{8,}$/",
                example:"Azert?123"
            ),
            new OA\Property(
                property:"lastname",
                title:"lastname",
                description:"User's last name",
                type:"string",
                example:"Doe"
            ),
            new OA\Property(
                property:"firstname",
                title:"firstname",
                description:"User's first name",
                type:"string",
                example:"John"
            ),
            new OA\Property(
                property:"company",
                title:"company",
                description:"User's company - optional",
                type:"string",
                example:"Johns&Cie",
                nullable:"true"
            )
        ]
    )
)]
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