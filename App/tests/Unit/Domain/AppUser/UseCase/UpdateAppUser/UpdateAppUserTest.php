<?php

namespace App\Tests\Domain\AppUser\UseCase\UpdateAppUser;

use App\Domain\AppUser\UseCase\UpdateAppUser\UpdateAppUser;
use App\Domain\AppUser\UseCase\UpdateAppUser\UpdateAppUserPresenter;
use App\Domain\AppUser\UseCase\UpdateAppUser\UpdateAppUserResponse;
use App\Tests\_Mock\Domain\AppUser\Entity\InMemoryAppUserRepository;
use App\Tests\Domain\AppUser\Entity\AppUserBuilder;
use PHPUnit\Framework\TestCase;

class UpdateAppUserTest extends TestCase implements UpdateAppUserPresenter
{
    const APPUSER_ID = 1;

    private $response;
    private $appUserRepository;
    private $appUser;
    private $updateAppUser;

    public function setUp(): void
    {
        $this->appUserRepository = new InMemoryAppUserRepository;
        $this->appUser = AppUserBuilder::anAppUser()->setId(self::APPUSER_ID)->build();
        $this->appUserRepository->addAppUser($this->appUser);
        $this->updateAppUser = new UpdateAppUser($this->appUserRepository);
    }

    public function present(UpdateAppUserResponse $response): void
    {
        $this->response = $response;
    }

    public function test_user_is_updated()
    {
        $request = UpdateAppUserRequestBuilder::aRequest()->setId(self::APPUSER_ID)->build();
        $this->updateAppUser->execute($request, $this);

        $this->assertNotNull($this->response->getAppUser());
        $this->assertNotEquals(
            $this->appUser,
            $this->appUserRepository->getAppUserById(self::APPUSER_ID)
        );
    }

    public function test_return_null_when_user_does_not_exist()
    {
        $request = UpdateAppUserRequestBuilder::aRequest()->setId(42)->build();
        $this->updateAppUser->execute($request, $this);

        $this->assertNull($this->response->getAppUser());
    }
}