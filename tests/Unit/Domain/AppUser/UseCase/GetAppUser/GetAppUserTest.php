<?php

namespace App\Tests\Domain\AppUser\UseCase\GetAppUser;

use App\Domain\AppUser\UseCase\GetAppUser\GetAppUser;
use App\Domain\AppUser\UseCase\GetAppUser\GetAppUserPresenter;
use App\Domain\AppUser\UseCase\GetAppUser\GetAppUserRequest;
use App\Domain\AppUser\UseCase\GetAppUser\GetAppUserResponse;
use App\Tests\_Mock\Domain\AppUser\Entity\InMemoryAppUserRepository;
use App\Tests\Domain\AppUser\Entity\AppUserBuilder;
use PHPUnit\Framework\TestCase;

class GetAppUserTest extends TestCase implements GetAppUserPresenter
{
    const APPUSER_ID = 1;

    private $response;
    private $appUserRepository;
    private $appUser;
    private $getAppUser;

    public function setUp(): void
    {
        $this->appUserRepository = new InMemoryAppUserRepository;
        $this->appUser = AppUserBuilder::anAppUser()->setId(self::APPUSER_ID)->build();
        $this->appUserRepository->addAppUser($this->appUser);
        $this->getAppUser = new GetAppUser($this->appUserRepository);
    }

    public function present(GetAppUserResponse $response): void
    {
        $this->response = $response;
    }

    public function test_return_user()
    {
        $this->getAppUser->execute(new GetAppUserRequest(self::APPUSER_ID), $this);

        $this->assertNotNull($this->response->getAppUser());
        $this->assertEquals(
            $this->response->getAppUser(),
            $this->appUser
        );
    }

    public function test_fails_when_user_does_not_exist()
    {
        $this->getAppUser->execute(new GetAppUserRequest(42), $this);

        $this->assertNull($this->response->getAppUser());
    }
}