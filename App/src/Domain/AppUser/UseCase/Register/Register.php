<?php

namespace App\Domain\AppUser\UseCase\Register;

use Assert\Assert;
use Assert\LazyAssertionException;
use App\Domain\AppUser\Entity\Role;
use App\Domain\AppUser\Entity\AppUser;
use App\SharedKernel\Service\PasswordHasher;
use App\Domain\AppUser\Entity\AppUserRepositoryInterface;
use App\SharedKernel\Service\ValidateRegisterMailer;
use Symfony\Component\Mailer\MailerInterface;

class Register
{
    private $appUserRepository;
    private $passwordHasher;
    private $mailer;

    public function __construct(
        AppUserRepositoryInterface $appUserRepository,
        PasswordHasher $passwordHasher,
        MailerInterface $mailer
    )
    {
        $this->appUserRepository = $appUserRepository;
        $this->passwordHasher = $passwordHasher;
        $this->mailer = $mailer;
    }

    public function execute(RegisterRequest $request, RegisterPresenter $presenter)
    {
        $response = new RegisterResponse();
        $isValid = $this->validateRequest($request, $response);
        $isValid = $isValid && $this->validateAppUser($request, $response);

        if ($isValid) {
            $this->saveAppUser($request, $response);
        }

        $presenter->present($response);
    }

    private function validateAppUser(RegisterRequest $request, RegisterResponse $response): bool
    {
        if ($request->email === null) {
            $response->addError('email', 'Email is missing');
            return false;
        }

        $existingAppUser = $this->appUserRepository->getAppUserByEmail((string)$request->email);

        if ($existingAppUser !== null) {
            $response->addError('email', 'Email already used');
            return false;
        }

        return true;
    }

    private function validateRequest(RegisterRequest $request, RegisterResponse $response): bool
    {
        try {
            Assert::lazy()
                ->that($request->email, 'email')->notEmpty('Email is empty or missing')->email('Invalid email')
                ->that($request->password, 'password')
                    ->notEmpty('Password is empty or missing')
                    ->string()
                    ->satisfy(function($value) {
                        $passwordPattern = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%&^*-]).{8,}$/";
                        return boolval(preg_match($passwordPattern, $value));
                    }, 'The password must have minimum 8 characters, 1 uppercase letter, 1 lowercase letter, 1 digit and 1 special character')
                ->that($request->lastname, 'lastname')->notEmpty('Lastname is empty or missing')->string()
                ->that($request->firstname, 'firstname')->notEmpty('Firstname is empty or missing')->string()
                ->that($request->company, 'company')->satisfy(function($value) {
                    if (!is_null($value)) {
                        return is_string($value) && $value !== "";
                    }
                }, 'Company must be a string value')
                ->verifyNow();
            
            return true;
        } catch (LazyAssertionException $e) {
            foreach ($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage());
            }
            return false;
        }
    }

    private function saveAppUser(RegisterRequest $request, RegisterResponse $response): void
    {
        $hashedPassword = $this->passwordHasher->hash($request->password);

        $appUser = new AppUser(
            $request->email,
            $hashedPassword,
            $request->lastname,
            $request->firstname,
            $request->company,
            Role::$appUser,
            false
        );

        $this->appUserRepository->addAppUser($appUser);

        $confirmEmail = new ValidateRegisterMailer($this->mailer, $appUser);
        $confirmEmail->setTo($appUser->getEmail());
        $confirmEmail->sendEmail();

        $response->setAppUser($appUser);
    }
}