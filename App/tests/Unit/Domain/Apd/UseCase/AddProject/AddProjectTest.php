<?php

namespace App\Tests\Domain\Apd\UseCase\AddProject;

use App\Domain\Apd\UseCase\AddProject\AddProject;
use App\Domain\Apd\UseCase\AddProject\AddProjectPresenter;
use App\Domain\Apd\UseCase\AddProject\AddProjectResponse;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryProjectRepository;
use App\Tests\_Mock\Domain\AppUser\Entity\InMemoryAppUserRepository;
use App\Tests\Domain\Apd\Entity\ProjectBuilder;
use App\Tests\Domain\AppUser\Entity\AppUserBuilder;
use PHPUnit\Framework\TestCase;

class AddProjectTest extends TestCase implements AddProjectPresenter
{
    const APPUSER_ID = 1;
    
    private $response;
    private $appUserRepository;
    private $projectRepository;
    private $appUser;
    private $project;
    private $addProject;

    public function setUp(): void
    {
        $this->appUserRepository = new InMemoryAppUserRepository;
        $this->projectRepository = new InMemoryProjectRepository;
        $this->appUser = AppUserBuilder::anAppUser()->setId(self::APPUSER_ID)->build();
        $this->appUserRepository->addAppUser($this->appUser);
        $this->project = ProjectBuilder::aProject()->setId(1)->setUserId(self::APPUSER_ID)->build();
        $this->projectRepository->addProject($this->project);
        $this->addProject = new AddProject($this->appUserRepository, $this->projectRepository);
    }

    public function present(AddProjectResponse $response): void
    {
        $this->response = $response;
    }

    public function test_add_project_in_database()
    {
        $request = AddProjectRequestBuilder::aProject(self::APPUSER_ID)->build();
        $this->addProject->execute($request, $this);

        $this->assertNotNull($this->response->getProject());
        $this->assertEquals(
            $this->response->getProject(),
            $this->projectRepository->getProjectById($this->response->getProject()->getId())
        );
    }

    public function test_fail_when_a_request_data_is_missing()
    {
        $request = AddProjectRequestBuilder::aProject()->build();
        $request->name = null;
        $this->addProject->execute($request, $this);

        $this->assertNull($this->response->getProject());
    }
}