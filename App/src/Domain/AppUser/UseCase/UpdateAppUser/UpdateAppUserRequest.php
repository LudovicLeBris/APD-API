<?php

namespace App\Domain\AppUser\UseCase\UpdateAppUser;

use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request:"updateAppUser",
    required:"true",
    content: new OA\JsonContent(
        properties:[
            new OA\Property(
                property:"email",
                title:"email",
                description:"User's email",
                type:"string",
                format:"email",
                example:"email@email.test",
                nullable:"true"
            ),
            new OA\Property(
                property:"lastname",
                title:"lastname",
                description:"User's last name",
                type:"string",
                example:"Doe",
                nullable:"true"
            ),
            new OA\Property(
                property:"firstname",
                title:"firstname",
                description:"User's first name",
                type:"string",
                example:"John",
                nullable:"true"
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