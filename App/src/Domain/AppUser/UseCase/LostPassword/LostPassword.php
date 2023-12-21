<?php

namespace App\Domain\AppUser\UseCase\LostPassword;

use App\Domain\AppUser\Entity\AppUserRepositoryInterface;
use App\Domain\AppUser\Entity\RecoveryPassword;
use App\Domain\AppUser\Entity\RecoveryPasswordRepositoryInterface;
use App\SharedKernel\Service\RecoveryPasswordMailer;
use App\SharedKernel\Service\TokenGenerator;
use Assert\Assert;
use Assert\LazyAssertionException;
use DateTimeImmutable;
use Symfony\Component\Mailer\MailerInterface;

class LostPassword
{
    private $appUserRepository;
    private $recoveryPasswordRepository;
    private $mailer;

    public function __construct(
        AppUserRepositoryInterface $appUserRepository,
        RecoveryPasswordRepositoryInterface $recoveryPasswordRepository,
        MailerInterface $mailer
    )
    {
        $this->appUserRepository = $appUserRepository;
        $this->recoveryPasswordRepository = $recoveryPasswordRepository;
        $this->mailer = $mailer;
    }

    public function execute(LostPasswordRequest $request, LostPasswordPresenter $presenter)
    {
        $response = new LostPasswordResponse();
        $isValid = $this->checkRequest($request, $response);
        $isValid = $isValid && $this->checkAppUser($request, $response);

        if ($isValid) {
            $appUser = $this->appUserRepository->getAppUserByEmail($request->email);
            $token = new TokenGenerator(16);

            $recoveryPassword = new RecoveryPassword(
                $token->getToken(),
                $appUser->getId(),
                $appUser->getEmail(),
                new DateTimeImmutable()
            );
            $this->recoveryPasswordRepository->addRecoveryPassword($recoveryPassword);

            $recoveryEmail = new RecoveryPasswordMailer($this->mailer, $recoveryPassword);
            $recoveryEmail->setTo($appUser->getEmail());
            $recoveryEmail->sendEmail();

            $appUser->setIsEnable(false);
            $this->appUserRepository->updateAppUser($appUser);

            $response->setAppUser($appUser);
        }

        $presenter->present($response);
    }

    private function checkRequest(LostPasswordRequest $request, LostPasswordResponse $response): bool
    {
        try {
            Assert::lazy()
                ->that($request->email, 'email')->notEmpty('Email is empty')->string()->email()
                ->verifyNow();

            return true;
        } catch (LazyAssertionException $e) {
            foreach ($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage(), 422);
            }
            return false;
        }
    }

    private function checkAppUser(LostPasswordRequest $request, LostPasswordResponse $response)
    {
        if ($request->email ===null) {
            $response->addError('email', 'Email is missing', 400);

            return false;
        }

        $existingAppUser = $this->appUserRepository->getAppUserByEmail($request->email);

        if ($existingAppUser === null) {
            $response->addError('email', 'User is not registered', 423);

            return false;
        }

        if (!$existingAppUser->getIsEnable()) {
            $response->addError('email', 'User is not enable', 423);

            return false;
        }

        return true;
    }
}