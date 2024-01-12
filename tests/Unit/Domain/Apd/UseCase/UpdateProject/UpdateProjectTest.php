<?php

namespace App\Tests\Domain\Apd\UseCase\UpdateProject;

use App\Domain\Apd\UseCase\UpdateProject\UpdateProject;
use PHPUnit\Framework\TestCase;
use App\Domain\Apd\UseCase\UpdateProject\UpdateProjectResponse;
use App\Domain\Apd\UseCase\UpdateProject\UpdateProjectPresenter;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctNetworkRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctSectionRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryProjectRepository;
use App\Tests\_Mock\Domain\AppUser\Entity\InMemoryAppUserRepository;
use App\Tests\Domain\Apd\Entity\ProjectBuilder;
use App\Tests\Domain\AppUser\Entity\AppUserBuilder;

class UpdateProjectTest extends TestCase implements UpdateProjectPresenter
{
    const APPUSER_ID = 1;
    const PROJECT_ID = 1;

    private $response;
    private $appUserRepository;
    private $projectRepository;
    private $ductNetworkRepository;
    private $ductSectionRepository;
    private $appUser;
    private $project;
    private $updateProject;

    public function setUp(): void
    {
        $this->appUserRepository = new InMemoryAppUserRepository;
        $this->projectRepository = new InMemoryProjectRepository;
        $this->ductNetworkRepository = new InMemoryDuctNetworkRepository;
        $this->ductSectionRepository = new InMemoryDuctSectionRepository;
        $this->appUser = AppUserBuilder::anAppUser()->setId(self::APPUSER_ID)->build();
        $this->appUserRepository->addAppUser($this->appUser);
        $this->project = ProjectBuilder::aProject()->setId(self::PROJECT_ID)->setUserId(self::APPUSER_ID)->build();
        $this->projectRepository->addProject($this->project);
        $this->updateProject = new UpdateProject($this->appUserRepository, $this->projectRepository, $this->ductNetworkRepository, $this->ductSectionRepository);
    }

    public function present(UpdateProjectResponse $response): void
    {
        $this->response = $response;
    }

    public function test_project_is_updated()
    {
        $request = UpdateProjectRequestBuilder::aRequest()
            ->setProjectId(self::PROJECT_ID)
            ->setUserId(self::APPUSER_ID)
            ->build();
        $this->updateProject->execute($request, $this);

        $this->assertNotNull($this->response->getProject());
        $this->assertNotEquals(
            $this->response->getProject(),
            $this->project
        );
    }

    public function test_fails_when_user_does_not_exist()
    {
        $request = UpdateProjectRequestBuilder::aRequest()
            ->setProjectId(self::PROJECT_ID)
            ->setUserId(42)
            ->build();
        $this->updateProject->execute($request, $this);

        $shouldResponseBe = new UpdateProjectResponse();
        $shouldResponseBe->addError('userId', 'User doesn\'t exist with this id.');

        $this->assertNull($this->response->getProject());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }

    public function test_fails_when_project_does_not_exist()
    {
        $request = UpdateProjectRequestBuilder::aRequest()
            ->setProjectId(42)
            ->setUserId(self::APPUSER_ID)
            ->build();
        $this->updateProject->execute($request, $this);

        $shouldResponseBe = new UpdateProjectResponse();
        $shouldResponseBe->addError('projectId', 'Project doesn\'t exist with this id.');

        $this->assertNull($this->response->getProject());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }
}