<?php

namespace App\Domain\AppUser\UseCase\ConfirmRegister;

use App\Domain\AppUser\Entity\AppUserRepositoryInterface;

class ConfirmRegister
{
    private $appUserRepository;

    public function __construct(AppUserRepositoryInterface $appUserRepository)
    {
        $this->appUserRepository = $appUserRepository;
    }

    public function execute(ConfirmRegisterRequest $request, ConfirmRegisterPresenter $presenter)
    {
        $response = new ConfirmRegisterResponse();
        
        $appUser = $this->appUserRepository->getAppUserById($request->id);

        if ($appUser->getIsEnable()) {
            $response->addError('user', 'User is already active');
        } else {
            $appUser->setIsEnable(true);
            $this->appUserRepository->updateAppUser($appUser);

            $response->setAppUser($appUser);
        }

        $presenter->present($response);

    }
}