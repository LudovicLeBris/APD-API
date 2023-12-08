<?php

namespace App\Domain\AppUser\UseCase\GetAppUser;

use App\Domain\AppUser\Entity\AppUserRepositoryInterface;

class GetAppUser
{
    private $appUserRepository;

    public function __construct(AppUserRepositoryInterface $appUserRepository)
    {
        $this->appUserRepository = $appUserRepository;
    }

    public function execute(GetAppUserRequest $request, GetAppUserPresenter $presenter)
    {
        $response = new GetAppUserResponse();

        $appUser = $this->appUserRepository->getAppUserById($request->appUserId);

        $response->setAppUser($appUser);

        $presenter->present($response);
    }
}