<?php

namespace App\Tests\Domain\AppUser\UseCase\UpdatePassword;

use App\Domain\Apd\UseCase\UpdateProject\UpdateProject;
use App\Domain\AppUser\Entity\RecoveryPassword;
use App\Domain\AppUser\UseCase\UpdatePassword\UpdatePassword;
use App\Domain\AppUser\UseCase\UpdatePassword\UpdatePasswordPresenter;
use App\Domain\AppUser\UseCase\UpdatePassword\UpdatePasswordResponse;
use App\SharedKernel\Service\PasswordHasher;
use App\SharedKernel\Service\TokenGenerator;
use App\Tests\_Mock\Domain\AppUser\Entity\InMemoryAppUserRepository;
use App\Tests\_Mock\Domain\AppUser\Entity\InMemoryRecoveryPasswordRepository;
use App\Tests\Domain\AppUser\Entity\AppUserBuilder;
use App\Tests\Domain\AppUser\Entity\RecoveryPasswordBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class UpdatePasswordTest extends TestCase implements UpdatePasswordPresenter
{
    const APPUSER_ID = 1;
    const APPUSER_EMAIL = 'email@test.io';
    const OLD_PASSWORD = 'Azerty123?';
    const NEW_PASSWORD = 'Ytrezsa321!';

    private $response;
    private $appUserRepository;
    private $recoveryPasswordRepository;
    private $appUser;
    private $recoveryPassword;
    private $updatePassword;

    public function setUp(): void
    {
        $this->appUserRepository = new InMemoryAppUserRepository;
        $this->recoveryPasswordRepository = new InMemoryRecoveryPasswordRepository;
        $this->appUser = AppUserBuilder::anAppUser()
            ->setId(self::APPUSER_ID)
            ->setEmail(self::APPUSER_EMAIL)
            ->setPassword(self::OLD_PASSWORD)
            ->build();
        $this->appUserRepository->addAppUser($this->appUser);
        $this->recoveryPassword = RecoveryPasswordBuilder::aRecoveryPassword()->build();
        $this->recoveryPasswordRepository->addRecoveryPassword($this->recoveryPassword);
        $this->updatePassword = new UpdatePassword(
            $this->appUserRepository,
            $this->recoveryPasswordRepository,
            new PasswordHasher
        );
    }

    public function present(UpdatePasswordResponse $response): void
    {
        $this->response = $response;
    }

    public function test_password_updated_from_an_update_request()
    {
        $request = UpdatePasswordRequestBuilder::anUpdateRequest()
            ->setOldPassword(self::OLD_PASSWORD)
            ->setNewPassword(self::NEW_PASSWORD)
            ->build();
        $this->updatePassword->execute($request, $this);

        $this->assertTrue($this->response->getIsDone());
    }

    public function test_password_updated_from_a_recovery_request()
    {
        $guid = $this->recoveryPasswordRepository->getRecoveryPasswordByAppUserId(self::APPUSER_ID)->getGuid();
        $request = UpdatePasswordRequestBuilder::aRecoverRequest()
            ->setGuid($guid)    
            ->setNewPassword(self::NEW_PASSWORD)
            ->build();
        $this->updatePassword->execute($request, $this);

        $this->assertTrue($this->response->getIsDone());
    }

    public function test_fails_when_user_id_does_not_match()
    {
        $request = UpdatePasswordRequestBuilder::anUpdateRequest()
            ->setId(42)
            ->setOldPassword(self::OLD_PASSWORD)
            ->setNewPassword(self::NEW_PASSWORD)
            ->build();
        $this->updatePassword->execute($request, $this);

        $shouldResponseBe = new UpdatePasswordResponse();
        $shouldResponseBe->addError('id', 'User doesn\'t exist with this id');

        $this->assertFalse($this->response->getIsDone());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }

    public function test_fails_when_old_password_does_not_match()
    {
        $request = UpdatePasswordRequestBuilder::anUpdateRequest()
            ->setOldPassword('WrongPassword123?')
            ->setNewPassword(self::NEW_PASSWORD)
            ->build();
        $this->updatePassword->execute($request, $this);

        $shouldResponseBe = new UpdatePasswordResponse();
        $shouldResponseBe->addError('password', 'Invalid password');

        $this->assertFalse($this->response->getIsDone());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }

    public function test_fails_when_recover_time_has_expired()
    {
        $guid = (new TokenGenerator())->getToken();
        $newRecoveryPassword = new RecoveryPassword(
            $guid,
            self::APPUSER_ID,
            self::APPUSER_EMAIL,
            new DateTimeImmutable('yesterday')
        );
        $this->recoveryPasswordRepository->addRecoveryPassword($newRecoveryPassword);

        $request = UpdatePasswordRequestBuilder::aRecoverRequest()
            ->setGuid($guid)
            ->setNewPassword(self::NEW_PASSWORD)
            ->build();
        $this->updatePassword->execute($request, $this);

        $shouldResponseBe = new UpdatePasswordResponse();
        $shouldResponseBe->addError('recover time', 'The recovery time is exceeded');

        $this->assertFalse($this->response->getIsDone());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }
}