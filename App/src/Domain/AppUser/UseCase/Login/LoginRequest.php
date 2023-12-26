<?php

namespace App\Domain\AppUser\UseCase\Login;

use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request:"login",
    required:"true",
    content: new OA\JsonContent(
        properties:[
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
            )
        ]
    )
)]
class LoginRequest
{
    public $email;
    public $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}