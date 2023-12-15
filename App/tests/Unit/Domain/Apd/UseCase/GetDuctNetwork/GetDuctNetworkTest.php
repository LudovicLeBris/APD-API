<?php

namespace App\Tests\Domain\Apd\UseCase\GetDuctNetwork;

use PHPUnit\Framework\TestCase;
use App\Tests\Domain\Apd\Entity\DuctNetworkBuilder;
use App\Domain\Apd\UseCase\GetDuctNetwork\GetDuctNetwork;
use App\Domain\Apd\UseCase\GetDuctNetwork\GetDuctNetworkRequest;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryProjectRepository;
use App\Domain\Apd\UseCase\GetDuctNetwork\GetDuctNetworkResponse;
use App\Domain\Apd\UseCase\GetDuctNetwork\GetDuctNetworkPresenter;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctNetworkRepository;
use App\Tests\Domain\Apd\Entity\ProjectBuilder;

class GetDuctNetworkTest extends TestCase implements GetDuctNetworkPresenter
{
    const PROJECT_ID = 1;
    const DUCTNETWORK_ID = 1;
    
    private $response;
    private $projectRepository;
    private $ductNetworkRepository;
    private $project;
    private $getDuctNetwork;
    private $ductNetwork;

    public function setUp(): void
    {
        $this->projectRepository = new InMemoryProjectRepository;
        $this->ductNetworkRepository = new InMemoryDuctNetworkRepository;
        $this->project = ProjectBuilder::aProject()->setId(self::PROJECT_ID)->build();
        $this->ductNetwork = DuctNetworkBuilder::aDuctNetwork()
            ->setId(self::DUCTNETWORK_ID)
            ->setProjectId(self::PROJECT_ID)
            ->build();
        $this->project->addDuctNetwork($this->ductNetwork);
        $this->projectRepository->addProject($this->project);
        $this->ductNetworkRepository->addDuctNetwork($this->ductNetwork);
        $this->getDuctNetwork = new GetDuctNetwork($this->projectRepository, $this->ductNetworkRepository);
    }

    public function present(GetDuctNetworkResponse $response): void
    {
        $this->response = $response;
    }

    public function test_return_duct_network()
    {
        $this->getDuctNetwork->execute(new GetDuctNetworkRequest(self::PROJECT_ID, self::DUCTNETWORK_ID), $this);

        $this->assertNotNull($this->response->getDuctNetwork());
        $this->assertSame(
            $this->response->getDuctNetwork(),
            $this->ductNetwork
        );
    }

    public function test_fails_when_duct_network_does_not_exist()
    {
        $this->getDuctNetwork->execute(new GetDuctNetworkRequest(self::PROJECT_ID, mt_rand(1, 500)), $this);

        $shouldResponseBe = new GetDuctNetworkResponse();
        $shouldResponseBe->addError('ductNetworkId', 'Duct network doesn\'t exist with this id.');

        $this->assertNull($this->response->getDuctNetwork());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }

    public function test_fails_when_project_does_not_exist()
    {
        $this->getDuctNetwork->execute(new GetDuctNetworkRequest(mt_rand(1, 500), self::DUCTNETWORK_ID), $this);

        $shouldResponseBe = new GetDuctNetworkResponse();
        $shouldResponseBe->addError('projectId', 'Project doesn\'t exist with this id.');

        $this->assertNull($this->response->getDuctNetwork());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }

    public function test_fails_when_duct_network_does_not_belong_to_project()
    {
        $anotherDuctNetwork = DuctNetworkBuilder::aDuctNetwork()
            ->setId(42)
            ->setProjectId(42)
            ->build();
        $this->ductNetworkRepository->addDuctNetwork($anotherDuctNetwork);
        $this->getDuctNetwork->execute(new GetDuctNetworkRequest(self::PROJECT_ID, 42), $this);
                
        $shouldResponseBe = new GetDuctNetworkResponse();
        $shouldResponseBe->addError('ductNetworkId', 'Duct network don\'t belong to this project.');

        $this->assertNull($this->response->getDuctNetwork());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }
}