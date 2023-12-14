<?php

namespace App\Tests\Domain\Apd\UseCase\RemoveProject;

use App\Domain\Apd\UseCase\RemoveProject\RemoveProject;
use App\Domain\Apd\UseCase\RemoveProject\RemoveProjectPresenter;
use App\Domain\Apd\UseCase\RemoveProject\RemoveProjectRequest;
use App\Domain\Apd\UseCase\RemoveProject\RemoveProjectResponse;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctNetworkRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctSectionRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryProjectRepository;
use App\Tests\Domain\Apd\Entity\ProjectBuilder;
use PHPUnit\Framework\TestCase;

class RemoveProjectTest extends TestCase implements RemoveProjectPresenter
{
    const PROJECT_ID = 1;
    const DUCTNETWORK_ID = 1;
    const DUCTSECTION_ID = 1;

    private $response;
    private $projectRepository;
    private $ductNetworkRepository;
    private $ductSectionRespository;
    private $project;
    private $removeProject;

    public function setUp(): void
    {
        $this->projectRepository = new InMemoryProjectRepository;
        $this->ductNetworkRepository = new InMemoryDuctNetworkRepository;
        $this->ductSectionRespository = new InMemoryDuctSectionRepository;
        $this->project = ProjectBuilder::aProject()->setId(self::PROJECT_ID)->build();
        $this->projectRepository->addProject($this->project);
        $this->removeProject = new RemoveProject($this->projectRepository, $this->ductNetworkRepository, $this->ductSectionRespository);
    }

    public function present(RemoveProjectResponse $response): void
    {
        $this->response = $response;
    }

    public function test_project_is_deleting_from_database()
    {
        $this->removeProject->execute(new RemoveProjectRequest(self::PROJECT_ID), $this);

        $this->assertNull($this->projectRepository->getProjectById(self::PROJECT_ID));
    }
}