<?php

namespace App\Domain\AppUser\UseCase\UpdateAppUser;

use App\Domain\AppUser\Entity\AppUser;
use App\Domain\AppUser\Entity\AppUserRepositoryInterface;
use Assert\Assert;
use Assert\LazyAssertionException;

class UpdateAppUser
{
    private $appUserRepository;

    public function __construct(AppUserRepositoryInterface $appUserRepository)
    {
        $this->appUserRepository = $appUserRepository;
    }

    public function execute(UpdateAppUserRequest $request, UpdateAppUserPresenter $presenter)
    {
        $response = new UpdateAppUserResponse();
        $oldAppUser = $this->appUserRepository->getAppUserById($request->id);
        
        $isValid = $this->checkAppUserExist($oldAppUser, $response);
        $isValid = $isValid && $this->checkRequest($request, $response);
        $isValid = $isValid && $this->checkAppUserEnable($oldAppUser, $response);

        if ($isValid) {
            $updatedAppUser = $this->updateAppUser($request, $oldAppUser);

            $this->appUserRepository->updateAppUser($updatedAppUser);

            $response->setAppUser($updatedAppUser);
        }

        $presenter->present($response);
    }

    private function checkRequest(UpdateAppUserRequest $request, UpdateAppUserResponse $response)
    {
        try {
            Assert::lazy()
                ->that($request->email, 'email')->satisfy(function($value) {
                    if (!is_null($value)) {
                        $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);
                        return is_string($value) && $value !== "" && $isEmail;
                    }
                }, 'Email must be a string of valid email and not empty string')
                ->that($request->lastname, 'lastname')->satisfy(function($value) {
                    if (!is_null($value)) {
                        return is_string($value) && $value !== "";
                    }
                }, 'Lastname must be a string or not empty string')
                ->that($request->firstname, 'firstname')->satisfy(function($value) {
                    if (!is_null($value)) {
                        return is_string($value) && $value !== "";
                    }
                }, 'Firstname must be a string or not empty string')
                ->that($request->company, 'company')->satisfy(function($value) {
                    if (!is_null($value)) {
                        return is_string($value) && $value !== "";
                    }
                }, 'Company must be a string or not empty string')
                ->verifyNow();

            return true;
        } catch (LazyAssertionException $e) {
            foreach ($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage(), 422);
            }

            return false;
        }
    }

    private function checkAppUserExist(?AppUser $appUser, UpdateAppUserResponse $response): bool
    {
        if ($appUser) {
            return true;
        }

        $response->addError('id', 'User doesn\'t exist with this id', 404);
        return false;
    }

    private function checkAppUserEnable(AppUser $oldAppUser, UpdateAppUserResponse $response)
    {
        if (!$oldAppUser->getIsEnable()) {
            $response->addError('email', 'Disabled user', 423);

            return false;
        }

        return true;
    }

    private function updateAppUser(UpdateAppUserRequest $request, AppUser $oldAppUser): AppUser
    {
        if (is_null($request->email)) {
            $email = $oldAppUser->getEmail();
        } else {
            $email = $request->email;
        }
        
        if (is_null($request->lastname)) {
            $lastname = $oldAppUser->getLastname();
        } else {
            $lastname = $request->lastname;
        }

        if (is_null($request->firstname)) {
            $firstname = $oldAppUser->getFirstname();
        } else {
            $firstname = $request->firstname;
        }

        if (is_null($request->company)) {
            $company = $oldAppUser->getCompany();
        } else {
            $company = $request->company;
        }

        $appUser = new AppUser(
            $email,
            $oldAppUser->getPassword(),
            $lastname,
            $firstname,
            $company,
            $oldAppUser->getRole(),
            $oldAppUser->getIsEnable()
        );
        $appUser->setId($oldAppUser->getId());

        return $appUser;
    }
}