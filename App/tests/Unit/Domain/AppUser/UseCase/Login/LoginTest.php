<?php

namespace App\Tests\Domain\AppUser\UseCase\Login;

use App\Domain\AppUser\UseCase\Login\Login;
use App\Domain\AppUser\UseCase\Login\LoginPresenter;
use App\Domain\AppUser\UseCase\Login\LoginRequest;
use App\Domain\AppUser\UseCase\Login\LoginResponse;
use App\SharedKernel\Service\PasswordHasher;
use App\Tests\_Mock\Domain\AppUser\Entity\InMemoryAppUserRepository;
use App\Tests\Domain\AppUser\Entity\AppUserBuilder;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase implements LoginPresenter
{
    const APPUSER_ID = 1; 
    const EMAIL = 'email@email.io';
    const PASSWORD = 'Password123?';

    private $response;
    private $appUserRepository;
    private $appUser;
    private $login;
    private $passwordHasher;

    public function setUp(): void
    {
        $this->appUserRepository = new InMemoryAppUserRepository;
        $this->passwordHasher = new PasswordHasher;
        $this->appUser = AppUserBuilder::anAppUser()
            ->setId(self::APPUSER_ID)
            ->setEmail(self::EMAIL)
            ->setPassword(self::PASSWORD)
            ->build();
        $this->appUserRepository->addAppUser($this->appUser);
        $this->login = new Login($this->appUserRepository, $this->passwordHasher);
    }

    public function present(LoginResponse $response): void
    {
        $this->response = $response;
    }

    public function test_if_login_correctly()
    {
        $this->login->execute(new LoginRequest(self::EMAIL, self::PASSWORD), $this);

        $this->assertNotNull($this->response->getAppUser());
    }

    public function test_fails_when_credentials_are_false()
    {
        $this->login->execute(new LoginRequest('test@test.test', self::PASSWORD), $this);

        $this->assertNull($this->response->getAppUser());
        
        $this->login->execute(new LoginRequest(self::EMAIL, 'wrong-password'), $this);
        
        $this->assertNull($this->response->getAppUser());
    }

    public function test_fails_when_email_is_invalid()
    {
        $this->login->execute(new LoginRequest('invalid.test', self::PASSWORD), $this);

        $shouldResponseBe = new LoginResponse();
        $shouldResponseBe->addError('email', 'Value "invalid.test" was expected to be a valid e-mail address.');

        $this->assertNull($this->response->getAppUser());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }

    public function test_fails_when_user_is_not_enable()
    {
        $disabledAppUser = AppUserBuilder::anAppUser()
            ->setId(42)
            ->setEmail('disable@test.io')
            ->setPassword('test')
            ->setIsEnable(false)
            ->build();
        $this->appUserRepository->addAppUser($disabledAppUser);

        $this->login->execute(new LoginRequest('disable@test.io', 'test'), $this);

        $shouldResponseBe = new LoginResponse();
        $shouldResponseBe->addError('email', 'User is disable');

        $this->assertNull($this->response->getAppUser());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }
}