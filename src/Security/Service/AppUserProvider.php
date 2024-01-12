<?php

namespace App\Security\Service;

use Symfony\Component\Security\Core\User\UserInterface;
use App\Domain\AppUser\Entity\AppUserRepositoryInterface;
use App\Security\User;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AppUserProvider implements UserProviderInterface
{
    private $appUserRepository;

    public function __construct(AppUserRepositoryInterface $appUserRepository)
    {
        $this->appUserRepository = $appUserRepository;
    }
    
    
    /**
     * Refreshes the user.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @return UserInterface
     *
     * @psalm-return TUser
     *
     * @throws UnsupportedUserException if the user is not supported
     * @throws UserNotFoundException    if the user is not found
     */
    public function refreshUser(UserInterface $user)
    {
        $appUser = $this->appUserRepository->getAppUserByEmail($user->getUserIdentifier());

        if ($appUser === null || !$appUser->getIsEnable()) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @return bool
     */
    public function supportsClass(string $class)
    {
        return User::class === $class;
    }

    /**
     * Loads the user for the given user identifier (e.g. username or email).
     *
     * This method must throw UserNotFoundException if the user is not found.
     *
     * @return TUser
     *
     * @throws UserNotFoundException
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $appUser = $this->appUserRepository->getAppUserByEmail($identifier);

        if ($appUser === null || !$appUser->getIsEnable()) {
            throw new UserNotFoundException();
        }

        return new User($appUser);
    }
}