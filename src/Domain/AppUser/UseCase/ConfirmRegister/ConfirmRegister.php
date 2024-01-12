<?php

namespace App\Domain\AppUser\UseCase\ConfirmRegister;

use App\Domain\AppUser\Entity\AppUser;
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
        $isValid = $this->checkAppuserExist($appUser, $response);
        
        if ($isValid) {
            if ($appUser->getIsEnable()) {
                $response->addError('user', 'User is already active', 409);
            } else {
                $appUser->setIsEnable(true);
                $this->appUserRepository->updateAppUser($appUser);
    
                $response->setAppUser($appUser);
            }
        }

        $presenter->present($response);
    }

    public function checkAppuserExist(?AppUser $appUser, ConfirmRegisterResponse $response): bool
    {
        if ($appUser) {
            return true;
        }

        $response->addError('id', 'User with this id doesn\'t exist', 404);
        return false;
    }
}