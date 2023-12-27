<?php

namespace App\Security;

use App\Domain\AppUser\Entity\Role;
use App\Domain\AppUser\Entity\AppUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private $appUser;

    public function __construct(AppUser $appUser)
    {
        $this->appUser = $appUser;
    }
    
    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored in a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        switch ($this->appUser->getRole()) {
            case Role::$appUser:
                return ['ROLE_USER'];
            case Role::$admin:
                return ['ROLE_ADMIN'];
        }

        throw new \Exception('No role for client '.$this->appUser->getId());
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @return void
     */
    public function eraseCredentials()
    {
    }

    /**
     * Returns the identifier for this user (e.g. username or email address).
     */
    public function getUserIdentifier(): string
    {
        return $this->appUser->getEmail();
    }

    public function getPassword(): ?string
    {
        return $this->appUser->getPassword();
    }

    public function getId()
    {
        return $this->appUser->getId();
    }
}