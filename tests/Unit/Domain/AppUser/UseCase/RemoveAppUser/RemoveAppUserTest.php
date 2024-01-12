<?php

namespace App\Tests\Domain\AppUser\UseCase\RemoveAppUser;

use App\Domain\AppUser\UseCase\RemoveAppUser\RemoveAppUser;
use App\Domain\AppUser\UseCase\RemoveAppUser\RemoveAppUserPresenter;
use App\Domain\AppUser\UseCase\RemoveAppUser\RemoveAppUserRequest;
use App\Domain\AppUser\UseCase\RemoveAppUser\RemoveAppUserResponse;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctNetworkRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctSectionRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryProjectRepository;
use App\Tests\_Mock\Domain\AppUser\Entity\InMemoryAppUserRepository;
use App\Tests\Domain\Apd\Entity\DuctNetworkBuilder;
use App\Tests\Domain\Apd\Entity\DuctSectionBuilder;
use App\Tests\Domain\Apd\Entity\ProjectBuilder;
use App\Tests\Domain\AppUser\Entity\AppUserBuilder;
use PHPUnit\Framework\TestCase;

class RemoveAppUserTest extends TestCase implements RemoveAppUserPresenter
{
    const APPUSER_ID = 1;
    const PROJECT_ID = 1;
    const DUCTNETWORK_ID = 1;
    const DUCTSECTION_ID = 1;

    private $response;
    private $appUserRepository;
    private $projectRepository;
    private $ductNetworkRepository;
    private $ductSectionRepository;
    private $appUser;
    private $project;
    private $ductNetwork;
    private $ductSection;
    private $removeAppUser;

    public function setUp(): void
    {
        $this->appUserRepository = new InMemoryAppUserRepository;
        $this->projectRepository = new InMemoryProjectRepository;
        $this->ductNetworkRepository = new InMemoryDuctNetworkRepository;
        $this->ductSectionRepository = new InMemoryDuctSectionRepository;
        $this->appUser = AppUserBuilder::anAppUser()->setId(self::APPUSER_ID)->build();
        $this->appUserRepository->addAppUser($this->appUser);
        $this->project = ProjectBuilder::aProject()->setId(self::PROJECT_ID)->build();
        $this->ductNetwork = DuctNetworkBuilder::aDuctNetwork()->setId(self::DUCTNETWORK_ID)->build();
        $this->ductSection = DuctSectionBuilder::aDuctSection()->setId(self::DUCTSECTION_ID)->build();
        $this->ductSectionRepository->addDuctSection($this->ductSection);
        $this->ductNetwork->addDuctSection($this->ductSection);
        $this->ductNetworkRepository->addDuctNetwork($this->ductNetwork);
        $this->project->addDuctNetwork($this->ductNetwork);
        $this->projectRepository->addProject($this->project);
        $this->removeAppUser = new RemoveAppUser(
            $this->appUserRepository,
            $this->projectRepository,
            $this->ductNetworkRepository,
            $this->ductSectionRepository
        );
    }

    public function present(RemoveAppUserResponse $response): void
    {
        $this->response = $response;
    }

    public function test_user_is_deleted_from_database()
    {
        $this->removeAppUser->execute(new RemoveAppUserRequest(self::APPUSER_ID), $this);

        $this->assertNotNull($this->response->getAppUser());
        $this->assertNull($this->appUserRepository->getAppUserById(self::APPUSER_ID));
    }

    public function test_projects_and_all_datas_related_are_deleted_from_database()
    {
        $this->removeAppUser->execute(new RemoveAppUserRequest(self::APPUSER_ID), $this);

        foreach ($this->projectRepository->getProjectsByUserId(self::APPUSER_ID) as $project) {
            $this->assertEmpty($this->ductNetworkRepository->getDuctNetworksByProjectId($project->getId()));
            foreach ($this->ductNetworkRepository->getDuctNetworksByProjectId($project->getId()) as $ductNetwork) {
                $this->assertEmpty($this->ductSectionRepository->getDuctSectionsByDuctNetworkId($ductNetwork->getId()));
            }
        }
        
        $this->assertEmpty($this->projectRepository->getProjectsByUserId(self::APPUSER_ID));
    }

    public function test_fails_when_user_does_nor_exist()
    {
        $this->removeAppUser->execute(new RemoveAppUserRequest(42), $this);

        $shouldeResponseBe = new RemoveAppUserResponse();
        $shouldeResponseBe->addError('id', 'There is no user with this id');

        $this->assertNull($this->response->getAppUser());
        $this->assertEquals(
            $this->response,
            $shouldeResponseBe
        );
    }
}