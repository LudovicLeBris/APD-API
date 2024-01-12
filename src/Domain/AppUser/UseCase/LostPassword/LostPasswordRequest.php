<?php

namespace App\Domain\AppUser\UseCase\LostPassword;

use OpenApi\Annotations\JsonContent;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request:"lostPassword",
    required:"true",
    content: new OA\JsonContent(
        properties:[
            new OA\Property(
                property:"email",
                description:"User's email",
                type:"string",
                format:"email",
                example:"email@email.test"
            )
        ]
    )
)]
class LostPasswordRequest
{
    public $email = null;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}