<?php

namespace App\Tests\Domain\Apd\UseCase\UpdateProject;

use App\Domain\Apd\UseCase\UpdateProject\UpdateProject;
use PHPUnit\Framework\TestCase;
use App\Domain\Apd\UseCase\UpdateProject\UpdateProjectResponse;
use App\Domain\Apd\UseCase\UpdateProject\UpdateProjectPresenter;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryProjectRepository;
use App\Tests\Domain\Apd\Entity\ProjectBuilder;

class UpdateProjectTest extends TestCase implements UpdateProjectPresenter
{
    CONST PROJECT_ID = 1;

    private $response;
    private $projectRepository;
    private $project;
    private $updateProject;

    public function setUp(): void
    {
        $this->projectRepository = new InMemoryProjectRepository;
        $this->project = ProjectBuilder::aProject()->setId(self::PROJECT_ID)->build();
        $this->projectRepository->addProject($this->project);
        $this->updateProject = new UpdateProject($this->projectRepository);
    }

    public function present(UpdateProjectResponse $response): void
    {
        $this->response = $response;
    }

    public function test_project_is_updated()
    {
        $request = UpdateProjectRequestBuilder::aRequest()
            ->setId(self::PROJECT_ID)
            ->build();
        $this->updateProject->execute($request, $this);

        $this->assertNotNull($this->response->getProject());
        $this->assertNotEquals(
            $this->response->getProject(),
            $this->project
        );
    }

    public function test_return_null_when_project_does_not_exist()
    {
        $request = UpdateProjectRequestBuilder::aRequest()
            ->setId(42)
            ->build();
        $this->updateProject->execute($request, $this);

        $this->assertNull($this->response->getProject());

    }
}