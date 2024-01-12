<?php

namespace App\Tests\Domain\AppUser\UseCase\Register;

use App\Domain\AppUser\UseCase\Register\Register;
use App\Domain\AppUser\UseCase\Register\RegisterPresenter;
use App\Domain\AppUser\UseCase\Register\RegisterResponse;
use App\SharedKernel\Service\PasswordHasher;
use App\Tests\_Mock\Domain\AppUser\Entity\InMemoryAppUserRepository;
use App\Tests\Domain\AppUser\Entity\AppUserBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;

class RegisterTest extends TestCase implements RegisterPresenter
{
    private $response;
    private $appUserRepository;
    private $passwordHasher;
    private $mailer;
    private $register;
    private $shouldResponseBe;

    public function setUp(): void
    {
        $this->appUserRepository = new InMemoryAppUserRepository;
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->passwordHasher = new PasswordHasher();
        $this->register = new Register($this->appUserRepository, $this->passwordHasher, $this->mailer);
        $this->shouldResponseBe = new RegisterResponse();
    }

    public function present(RegisterResponse $response): void
    {
        $this->response = $response;
    }

    public function test_appUser_is_save_in_database()
    {
        $request = RegisterRequestBuilder::aRequest()->build();
        $this->register->execute($request, $this);

        $this->assertNotNull($this->response->getAppUser());
        $this->assertEquals(
            $this->response->getAppUser(),
            $this->appUserRepository->getAppUserById($this->response->getAppUser()->getId())
        );
    }

    public function test_fails_when_email_already_exist()
    {
        $request = RegisterRequestBuilder::aRequest()->build();
        $this->appUserRepository->addAppUser(AppUserBuilder::anAppUser()->setId(42)->setEmail($request->email)->build());
        $this->register->execute($request, $this);

        $this->shouldResponseBe->addError('email', 'Email already used');

        $this->assertEquals(
            $this->shouldResponseBe,
            $this->response
        );
    }

    public function test_fails_when_one_request_data_is_missing()
    {
        $request = RegisterRequestBuilder::aRequest()->build();
        $request->email = null;
        $this->register->execute($request, $this);

        $this->shouldResponseBe->addError('email', 'Email is empty or missing');

        $this->assertEquals(
            $this->shouldResponseBe,
            $this->response
        );
    }

    public function test_fails_when_email_is_invalid()
    {
        $request = RegisterRequestBuilder::aRequest()->setEmail('toto.fr')->build();
        $this->register->execute($request, $this);

        $this->shouldResponseBe->addError('email', 'Invalid email');
        $this->assertEquals(
            $this->shouldResponseBe,
            $this->response
        );
    }

    public function test_fails_when_password_does_not_match_required_pattern()
    {
        $request = RegisterRequestBuilder::aRequest()->setPassword('toto')->build();
        $this->register->execute($request, $this);

        $this->shouldResponseBe->addError('password', 'The password must have minimum 8 characters, 1 uppercase letter, 1 lowercase letter, 1 digit and 1 special character');

        $this->assertEquals(
            $this->shouldResponseBe,
            $this->response
        );
    }

    public function test_if_appuser_registered_is_not_enable()
    {
        $request = RegisterRequestBuilder::aRequest()->build();
        $this->register->execute($request, $this);

        $this->assertFalse($this->response->getAppuser()->getIsEnable());
    }

    public function test_if_password_store_in_database_is_hased()
    {
        $password = 'Azerty1234?';
        $request = RegisterRequestBuilder::aRequest()->setPassword($password)->build();
        $this->register->execute($request, $this);

        $hashedPassword = $this->response->getAppUser()->getPassword();
        $this->assertNotEquals(
            $password,
            $hashedPassword
        );
        $this->assertTrue($this->passwordHasher->isPasswordValid($password, $hashedPassword));
    }
}