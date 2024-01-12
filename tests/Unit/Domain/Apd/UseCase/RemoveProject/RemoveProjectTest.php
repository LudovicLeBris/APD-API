<?php

namespace App\Tests\Domain\Apd\UseCase\RemoveProject;

use App\Domain\Apd\Entity\DuctSection;
use App\Domain\Apd\UseCase\RemoveProject\RemoveProject;
use App\Domain\Apd\UseCase\RemoveProject\RemoveProjectPresenter;
use App\Domain\Apd\UseCase\RemoveProject\RemoveProjectRequest;
use App\Domain\Apd\UseCase\RemoveProject\RemoveProjectResponse;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctNetworkRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctSectionRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryProjectRepository;
use App\Tests\_Mock\Domain\AppUser\Entity\InMemoryAppUserRepository;
use App\Tests\Domain\Apd\Entity\DuctNetworkBuilder;
use App\Tests\Domain\Apd\Entity\DuctSectionBuilder;
use App\Tests\Domain\Apd\Entity\ProjectBuilder;
use App\Tests\Domain\AppUser\Entity\AppUserBuilder;
use PHPUnit\Framework\TestCase;

class RemoveProjectTest extends TestCase implements RemoveProjectPresenter
{
    const APPUSER_ID = 1;
    const PROJECT_ID = 1;
    const DUCTNETWORK_ID = 1;
    const DUCTSECTION_ID = 1;

    private $response;
    private $appUserRepository;
    private $projectRepository;
    private $ductNetworkRepository;
    private $ductSectionRespository;
    private $appUser;
    private $project;
    private $ductNetwork;
    private $ductSection;
    private $removeProject;

    public function setUp(): void
    {
        $this->appUserRepository = new InMemoryAppUserRepository;
        $this->projectRepository = new InMemoryProjectRepository;
        $this->ductNetworkRepository = new InMemoryDuctNetworkRepository;
        $this->ductSectionRespository = new InMemoryDuctSectionRepository;
        $this->appUser = AppUserBuilder::anAppUser()->setId(self::APPUSER_ID)->build();
        $this->appUserRepository->addAppUser($this->appUser);
        $this->project = ProjectBuilder::aProject()
            ->setId(self::PROJECT_ID)
            ->setUserId(self::APPUSER_ID)
            ->build();
        $this->ductNetwork = DuctNetworkBuilder::aDuctNetwork()
            ->setId(self::DUCTNETWORK_ID)
            ->setProjectId(self::PROJECT_ID)
            ->build();
        $this->ductSection = DuctSectionBuilder::aDuctSection()
            ->setId(self::DUCTSECTION_ID)
            ->setDuctNetworkId(self::DUCTNETWORK_ID)
            ->build();
        $this->ductNetwork->addDuctSection($this->ductSection);
        $this->project->addDuctNetwork($this->ductNetwork);
        $this->ductSectionRespository->addDuctSection($this->ductSection);
        $this->ductNetworkRepository->addDuctNetwork($this->ductNetwork);
        $this->projectRepository->addProject($this->project);
        $this->removeProject = new RemoveProject(
            $this->appUserRepository,
            $this->projectRepository, 
            $this->ductNetworkRepository, 
            $this->ductSectionRespository
        );
    }

    public function present(RemoveProjectResponse $response): void
    {
        $this->response = $response;
    }

    public function test_projects_and_all_duct_networks_and_duct_sections_are_deleting_from_database()
    {
        $this->removeProject->execute(new RemoveProjectRequest(self::APPUSER_ID, self::PROJECT_ID), $this);

        $this->assertEmpty($this->projectRepository->getProjectsByUserId(self::APPUSER_ID));
        $this->assertEmpty($this->ductNetworkRepository->getDuctNetworksByProjectId(self::PROJECT_ID));
        $this->assertEmpty($this->ductSectionRespository->getDuctSectionsByDuctNetworkId(self::DUCTNETWORK_ID));
    }

    public function test_fails_when_user_does_not_exist()
    {
        $this->removeProject->execute(new RemoveProjectRequest(42, self::PROJECT_ID), $this);

        $shouldResponseBe = new RemoveProjectResponse();
        $shouldResponseBe->addError('userId', 'User doesn\'t exist with this id.');

        $this->assertNull($this->response->getProject());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }

    public function test_fails_when_project_does_not_exist()
    {
        $this->removeProject->execute(new RemoveProjectRequest(self::APPUSER_ID, 42), $this);

        $shouldResponseBe = new RemoveProjectResponse();
        $shouldResponseBe->addError('projectId', 'Project doesn\'t exist with this id.');

        $this->assertNull($this->response->getProject());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }
}