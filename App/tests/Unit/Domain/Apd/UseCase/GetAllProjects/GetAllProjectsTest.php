<?php

namespace App\Tests\Domain\Apd\UseCase\GetAllProjects;

use App\Domain\Apd\UseCase\GetAllProjects\GetAllProjects;
use App\Domain\Apd\UseCase\GetAllProjects\GetAllProjectsPresenter;
use App\Domain\Apd\UseCase\GetAllProjects\GetAllProjectsRequest;
use App\Domain\Apd\UseCase\GetAllProjects\GetAllProjectsResponse;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryProjectRepository;
use App\Tests\_Mock\Domain\AppUser\Entity\InMemoryAppUserRepository;
use App\Tests\Domain\Apd\Entity\ProjectBuilder;
use App\Tests\Domain\AppUser\Entity\AppUserBuilder;
use PHPUnit\Framework\TestCase;

class GetAllProjectsTest extends TestCase implements GetAllProjectsPresenter
{
    const APPUSER_ID = 1;
    const NUMBER_OF_PROJECTS = 5;

    private $response;
    private $appUserRepository;
    private $projectRepository;
    private $appUser;
    private $getAllProjects;

    public function setUp(): void
    {
        $this->appUserRepository = new InMemoryAppUserRepository;
        $this->projectRepository = new InMemoryProjectRepository;
        $this->appUser = AppUserBuilder::anAppUser()->setId(self::APPUSER_ID)->build();
        $this->appUserRepository->addAppUser($this->appUser);
        $loopIndex = 10;
        $loopEnd = $loopIndex + self::NUMBER_OF_PROJECTS;
        for ($loopIndex; $loopIndex < $loopEnd; $loopIndex++) {
            $aProject = ProjectBuilder::aProject()
                ->setId($loopIndex)
                ->setUserId(self::APPUSER_ID)
                ->setName('project n°'. $loopIndex)
                ->build();
            $this->projectRepository->addProject($aProject);
        }
        $this->getAllProjects = new GetAllProjects($this->appUserRepository, $this->projectRepository);
    }

    public function present(GetAllProjectsResponse $response): void
    {
        $this->response = $response;
    }

    public function test_return_all_projects_in_array()
    {
        $this->getAllProjects->execute(new GetAllProjectsRequest(self::APPUSER_ID), $this);

        $this->assertNotNull($this->response->getAllProjects());
        $this->assertIsArray($this->response->getAllProjects());
        $this->assertCount(self::NUMBER_OF_PROJECTS, $this->response->getAllProjects());
    }

    public function test_fails_when_user_does_not_exist()
    {
        $this->getAllProjects->execute(new GetAllProjectsRequest(42), $this);

        $shouldResponseBe = new GetAllProjectsResponse();
        $shouldResponseBe->addError('userId', 'User doesn\'t exist with this id.');

        $this->assertNull($this->response->getAllProjects());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }
}