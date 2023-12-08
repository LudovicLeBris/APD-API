<?php

namespace App\Domain\AppUser\UseCase\Login;

use App\Domain\AppUser\Entity\AppUser;
use App\Domain\AppUser\Entity\AppUserRepositoryInterface;
use App\SharedKernel\Service\PasswordHasher;
use Assert\Assert;
use Assert\LazyAssertionException;

class Login
{
    private $appUserRepository;
    private $passwordHasher;

    public function __construct(AppUserRepositoryInterface $appUserRepository, PasswordHasher $passwordHasher)
    {
        $this->appUserRepository = $appUserRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function execute(LoginRequest $request, LoginPresenter $presenter)
    {
        $response = new LoginResponse();
        $appUser = $this->appUserRepository->getAppUserByEmail($request->email);

        $isValid = $this->checkRequest($request, $response);
        $isValid = $isValid && $this->checkAppUser($request, $appUser, $response);
        $isValid = $isValid && $this->checkAppUserEnable($appUser, $response);

        if ($isValid) {
            $response->setAppUser($appUser);
        }

        $presenter->present($response);

    }

    private function checkRequest(LoginRequest $request, LoginResponse $response)
    {
        try {
            Assert::lazy()
                ->that($request->email, 'email')->notEmpty('Email is empty')->string()->email()
                ->that($request->password, 'password')->notEmpty('Password is empty')->string()
                ->verifyNow();

            return true;
        } catch (LazyAssertionException $e) {
            foreach ($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage());
            }
            return false;
        }
    }
    
    private function checkAppUser(LoginRequest $request, ?AppUser $appUser, LoginResponse $response): bool
    {
        $isPasswordValid = $this->passwordHasher->isPasswordValid($request->password, $appUser->getPassword());

        if ($appUser === null || !$isPasswordValid) {
            $response->addError('credentials : email or password', 'Incorrect e-mail address or password');

            return false;
        }

        return true;
    }

    private function checkAppUserEnable(AppUser $appUser, LoginResponse $response): bool
    {
        if (!$appUser->getIsEnable()) {
            $response->addError('email', 'User is disable');

            return false;
        }

        return true;
    }
}