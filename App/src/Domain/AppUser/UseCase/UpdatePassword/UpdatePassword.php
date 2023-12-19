<?php

namespace App\Domain\AppUser\UseCase\UpdatePassword;

use Assert\Assert;
use Assert\LazyAssertionException;
use App\Domain\AppUser\Entity\AppUser;
use App\SharedKernel\Service\PasswordHasher;
use App\Domain\AppUser\Entity\AppUserRepositoryInterface;
use App\Domain\AppUser\Entity\RecoveryPasswordRepositoryInterface;

class UpdatePassword
{
    private $appUserRepository;
    private $recoveryPasswordRepository;
    private $passwordHasher;

    public function __construct(
        AppUserRepositoryInterface $appUserRepository,
        RecoveryPasswordRepositoryInterface $recoveryPasswordRepository,
        PasswordHasher $passwordHasher
    )
    {
        $this->appUserRepository = $appUserRepository;
        $this->recoveryPasswordRepository = $recoveryPasswordRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function execute(UpdatePasswordRequest $request, UpdatePasswordPresenter $presenter)
    {
        $response = new UpdatePasswordResponse();
        $isValid = $this->checkRequest($request, $response);

        if (is_null($request->guid)) {
            $this->updatePassword($request, $response, $isValid);
        } else {
            $this->recoverPassword($request, $response, $isValid);
        }

        $presenter->present($response);
    }

    private function checkRequest(UpdatePasswordRequest $request, UpdatePasswordResponse $response)
    {
        try {
            Assert::lazy()
                ->that($request->newPassword, 'newPassword')
                    ->notEmpty('New password is empty')
                    ->string()
                    ->satisfy(function($value) {
                        $passwordPattern = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%&^*-]).{8,}$/";
                        return boolval(preg_match($passwordPattern, $value));
                    }, 'The password must have minimum 8 characters, 1 uppercase letter, 1 lowercase letter, 1 digit and 1 special character')
                ->verifyNow();

            return true;
        } catch (LazyAssertionException $e) {
            foreach ($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage());
            }

            return false;
        }
    }

    private function checkOldPassword(UpdatePasswordRequest $request, AppUser $appUser, UpdatePasswordResponse $response)
    {
        if (is_null($request->oldPassword)) {
            $response->addError('oldPassword', 'The oldPassword is missing');

            return false;
        }
        
        if (!$this->passwordHasher->isPasswordValid($request->oldPassword, $appUser->getPassword())) {
            $response->addError('password', 'Invalid password');

            return false;
        }

        return true;
    }

    private function recoverPassword(UpdatePasswordRequest $request, UpdatePasswordResponse $response, bool $isValid)
    {
        $recoveryPassword = $this->recoveryPasswordRepository->getRecoveryPasswordByGuid($request->guid);
        if (is_null($recoveryPassword)) {
            $response->addError('guid', 'The guid token doesn\'t match');

            return $this;
        }
        
        if (!$recoveryPassword->getIsEnable()) {
            $response->addError('recover time', 'The recovery time is exceeded');
            $isValid = false;
        }

        $oldAppUser = $this->appUserRepository->getAppUserById($recoveryPassword->getAppUserId());

        if (!$oldAppUser) {
            $isValid = false;
            $response->addError('id', 'User id don\'t match in recovery database');
        }

        if ($isValid) {

            $hashedPassword = $this->passwordHasher->hash($request->newPassword);

            $appUser = new AppUser(
                $oldAppUser->getEmail(),
                $hashedPassword,
                $oldAppUser->getLastname(),
                $oldAppUser->getFirstname(),
                $oldAppUser->getCompany(),
                $oldAppUser->getRole(),
                true
            );
            $appUser->setId($oldAppUser->getId());

            $this->appUserRepository->updateAppUser($appUser);
            $this->recoveryPasswordRepository->deleteRecoveryPassword($request->guid);

            $response->setIsDone(true);
        }

    }

    private function updatePassword(UpdatePasswordRequest $request, UpdatePasswordResponse $response, bool $isValid)
    {
        $oldAppUser = $this->appUserRepository->getAppUserById($request->id);
        $isValid = true;
        if (!$oldAppUser) {
            $isValid = false;
            $response->addError('id', 'User doesn\'t exist with this id');
        }
        
        $isValid = $isValid && $this->checkOldPassword($request, $oldAppUser, $response);

        if ($isValid) {
            $hashedPassword = $this->passwordHasher->hash($request->newPassword);

            $appUser = new AppUser(
                $oldAppUser->getEmail(),
                $hashedPassword,
                $oldAppUser->getLastname(),
                $oldAppUser->getFirstname(),
                $oldAppUser->getCompany(),
                $oldAppUser->getRole(),
                $oldAppUser->getIsEnable()
            );

            $appUser->setId($oldAppUser->getId());

            $this->appUserRepository->updateAppUser($appUser);

            $response->setIsDone(true);
        }
    }
}