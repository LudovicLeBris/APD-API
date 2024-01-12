<?php

namespace App\Tests\Domain\AppUser\UseCase\LostPassword;

use App\Domain\AppUser\UseCase\LostPassword\LostPassword;
use App\Domain\AppUser\UseCase\LostPassword\LostPasswordPresenter;
use App\Domain\AppUser\UseCase\LostPassword\LostPasswordRequest;
use App\Domain\AppUser\UseCase\LostPassword\LostPasswordResponse;
use App\Tests\_Mock\Domain\AppUser\Entity\InMemoryAppUserRepository;
use App\Tests\_Mock\Domain\AppUser\Entity\InMemoryRecoveryPasswordRepository;
use App\Tests\Domain\AppUser\Entity\AppUserBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;

class LostPasswordTest extends TestCase implements LostPasswordPresenter
{
    const APPUSER_ID = 1;
    const EMAIL = 'email@test.io';

    private $response;
    private $appUserRepository;
    private $recoveryPasswordRepository;
    private $appUser;
    private $lostPassword;
    private $mailer;

    public function setUp(): void
    {
        $this->appUserRepository = new InMemoryAppUserRepository;
        $this->recoveryPasswordRepository = new InMemoryRecoveryPasswordRepository;
        $this->appUser = AppUserBuilder::anAppUser()
            ->setId(self::APPUSER_ID)
            ->setEmail(self::EMAIL)
            ->build();
        $this->appUserRepository->addAppUser($this->appUser);
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->lostPassword = new LostPassword($this->appUserRepository, $this->recoveryPasswordRepository, $this->mailer);
    }

    public function present(LostPasswordResponse $response): void
    {
        $this->response = $response;
    }

    public function test_user_is_returned()
    {
        $this->lostPassword->execute(new LostPasswordRequest(self::EMAIL), $this);

        $this->assertNotNull($this->response->getAppUser());
    }

    public function test_if_a_recoveryPassword_is_saved_in_database()
    {
        $this->lostPassword->execute(new LostPasswordRequest(self::EMAIL), $this);

        $this->assertEquals(
            $this->recoveryPasswordRepository->getRecoveryPasswordByAppUserId(self::APPUSER_ID)->getAppUserEmail(),
            $this->appUser->getEmail()
        );
    }

    public function test_fails_when_user_is_not_registered()
    {
        $this->lostPassword->execute(new LostPasswordRequest('anotheremail@test.io'), $this);

        $shouldResponseBe = new LostPasswordResponse();
        $shouldResponseBe->addError('email', 'User is not registered');

        $this->assertEquals(
            $shouldResponseBe,
            $this->response
        );
    }

    public function test_fails_when_user_is_already_disable()
    {
        $disabledAppUser = AppUserBuilder::anAppUser()
            ->setId(42)
            ->setEmail('disable@test.io')
            ->setIsEnable(false)
            ->build();

        $this->appUserRepository->addAppUser($disabledAppUser);

        $this->lostPassword->execute(new LostPasswordRequest('disable@test.io'), $this);

        $shouldResponseBe = new LostPasswordResponse();
        $shouldResponseBe->addError('email', 'User is not enable');

        $this->assertEquals(
            $shouldResponseBe,
            $this->response
        );
    }

    public function test_fails_when_email_is_invalid()
    {
        $this->lostPassword->execute(new LostPasswordRequest('invalid.io'), $this);

        $shouldResponseBe = new LostPasswordResponse();
        $shouldResponseBe->addError('email', 'Value "invalid.io" was expected to be a valid e-mail address.');

        $this->assertEquals(
            $shouldResponseBe,
            $this->response
        );
    }
}