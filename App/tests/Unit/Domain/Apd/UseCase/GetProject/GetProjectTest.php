<?php

namespace App\Tests\Domain\Apd\UseCase\GetProject;

use App\Domain\Apd\UseCase\GetProject\GetProject;
use App\Domain\Apd\UseCase\GetProject\GetProjectPresenter;
use App\Domain\Apd\UseCase\GetProject\GetProjectRequest;
use App\Domain\Apd\UseCase\GetProject\GetProjectResponse;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryProjectRepository;
use App\Tests\Domain\Apd\Entity\ProjectBuilder;
use PHPUnit\Framework\TestCase;

class GetProjectTest extends TestCase implements GetProjectPresenter
{
    const PROJECT_ID = 1;
    
    private $response;
    private $projectRepository;
    private $project;
    private $getProject;

    public function setUp():void
    {
        $this->projectRepository = new InMemoryProjectRepository;
        $this->project = ProjectBuilder::aProject()->setId(self::PROJECT_ID)->build();
        $this->projectRepository->addProject($this->project);
        $this->getProject = new GetProject($this->projectRepository);
    }

    public function present(GetProjectResponse $response): void
    {
        $this->response = $response;
    }

    public function test_return_project()
    {
        $this->getProject->execute(new GetProjectRequest(self::PROJECT_ID), $this);

        $this->assertNotNull($this->response->getProject());
        $this->assertSame(
            $this->response->getProject(),
            $this->project
        );
    }

    public function test_return_null_when_project_does_not_exist()
    {
        $this->getProject->execute(new GetProjectRequest(42), $this);

        $this->assertNull($this->response->getProject());
    }
}