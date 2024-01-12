<?php

namespace App\Tests\Domain\Apd\UseCase\GetProject;

use App\Domain\Apd\UseCase\GetProject\GetProject;
use App\Domain\Apd\UseCase\GetProject\GetProjectPresenter;
use App\Domain\Apd\UseCase\GetProject\GetProjectRequest;
use App\Domain\Apd\UseCase\GetProject\GetProjectResponse;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryProjectRepository;
use App\Tests\_Mock\Domain\AppUser\Entity\InMemoryAppUserRepository;
use App\Tests\Domain\Apd\Entity\ProjectBuilder;
use App\Tests\Domain\AppUser\Entity\AppUserBuilder;
use PHPUnit\Framework\TestCase;

class GetProjectTest extends TestCase implements GetProjectPresenter
{
    const APPUSER_ID = 1;
    const PROJECT_ID = 1;
    
    private $response;
    private $appUserRepository;
    private $projectRepository;
    private $appUser;
    private $project;
    private $getProject;

    public function setUp():void
    {
        $this->appUserRepository = new InMemoryAppUserRepository;
        $this->projectRepository = new InMemoryProjectRepository;
        $this->appUser = AppUserBuilder::anAppUser()->setId(self::APPUSER_ID)->build();
        $this->appUserRepository->addAppUser($this->appUser);
        $this->project = ProjectBuilder::aProject()
            ->setId(self::PROJECT_ID)
            ->setUserId(self::APPUSER_ID)
            ->build();
        $this->projectRepository->addProject($this->project);
        $this->getProject = new GetProject($this->appUserRepository, $this->projectRepository);
    }

    public function present(GetProjectResponse $response): void
    {
        $this->response = $response;
    }

    public function test_return_project()
    {
        $this->getProject->execute(new GetProjectRequest(self::APPUSER_ID, self::PROJECT_ID), $this);

        $this->assertNotNull($this->response->getProject());
        $this->assertSame(
            $this->response->getProject(),
            $this->project
        );
    }

    public function test_fails_when_user_does_not_exist()
    {
        $this->getProject->execute(new GetProjectRequest(42, self::PROJECT_ID), $this);

        $shouldResponseBe = new GetProjectResponse();
        $shouldResponseBe->addError('userId', 'User doesn\'t exist with this id.');

        $this->assertNull($this->response->getProject());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }

    public function test_fails_when_project_does_not_exist()
    {
        $this->getProject->execute(new GetProjectRequest(self::APPUSER_ID, 42), $this);

        $shouldResponseBe = new GetProjectResponse();
        $shouldResponseBe->addError('projectId', 'Project doesn\'t exist with this id.');

        $this->assertNull($this->response->getProject());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }
}