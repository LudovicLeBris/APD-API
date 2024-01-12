<?php

namespace App\Tests\Domain\AppUser\UseCase\ConfirmRegister;

use App\Domain\AppUser\UseCase\ConfirmRegister\ConfirmRegister;
use App\Domain\AppUser\UseCase\ConfirmRegister\ConfirmRegisterPresenter;
use App\Domain\AppUser\UseCase\ConfirmRegister\ConfirmRegisterRequest;
use App\Domain\AppUser\UseCase\ConfirmRegister\ConfirmRegisterResponse;
use App\Tests\_Mock\Domain\AppUser\Entity\InMemoryAppUserRepository;
use App\Tests\Domain\AppUser\Entity\AppUserBuilder;
use PHPUnit\Framework\TestCase;

class ConfirmRegisterTest extends TestCase implements ConfirmRegisterPresenter
{
    const APPUSER_ID = 1;
    
    private $response;
    private $appUserRepository;
    private $appUserJustRegistered;
    private $confirmRegister;

    public function setUp(): void
    {
        $this->appUserRepository = new InMemoryAppUserRepository;
        $this->appUserJustRegistered = AppUserBuilder::anAppUser()
            ->setId(self::APPUSER_ID)
            ->setIsEnable(false)
            ->build();
        $this->appUserRepository->addAppUser($this->appUserJustRegistered);
        $this->confirmRegister = new ConfirmRegister($this->appUserRepository);
    }

    public function present(ConfirmRegisterResponse $response): void
    {
        $this->response = $response;
    }

    public function test_if_user_is_enabled()
    {
        $this->confirmRegister->execute(new ConfirmRegisterRequest(self::APPUSER_ID), $this);
        
        $this->assertTrue($this->response->getAppUser()->getIsEnable());
    }

    public function test_fails_when_user_does_not_exist()
    {
        $this->confirmRegister->execute(new ConfirmRegisterRequest(42), $this);

        $shouldResponseBe = new ConfirmRegisterResponse();
        $shouldResponseBe->addError('id', 'User with this id doesn\'t exist');

        $this->assertEquals(
            $shouldResponseBe,
            $this->response
        );
    }
}