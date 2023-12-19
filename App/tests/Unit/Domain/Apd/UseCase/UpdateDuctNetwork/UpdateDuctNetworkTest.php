<?php

namespace App\Tests\Domain\Apd\UseCase\UpdateDuctNetwork;

use App\Domain\Apd\UseCase\UpdateDuctNetwork\UpdateDuctNetwork;
use App\Domain\Apd\UseCase\UpdateDuctNetwork\UpdateDuctNetworkPresenter;
use App\Domain\Apd\UseCase\UpdateDuctNetwork\UpdateDuctNetworkResponse;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctNetworkRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctSectionRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryProjectRepository;
use App\Tests\Domain\Apd\Entity\DuctNetworkBuilder;
use App\Tests\Domain\Apd\Entity\ProjectBuilder;
use PHPUnit\Framework\TestCase;

class UpdateDuctNetworkTest extends TestCase implements UpdateDuctNetworkPresenter
{
    const DUCTNETWORK_ID = 1;
    const PROJECT_ID = 1;
    
    private $response;
    private $ductNetworkRepository;
    private $projecteRepository;
    private $ductSectionRepository;
    private $ductNetwork;
    private $project;
    private $updateDuctNetwork;

    public function setUp(): void
    {
        $this->ductNetworkRepository = new InMemoryDuctNetworkRepository;
        $this->projecteRepository = new InMemoryProjectRepository;
        $this->ductSectionRepository = new InMemoryDuctSectionRepository;
        $this->ductNetwork = DuctNetworkBuilder::aDuctNetwork()->setId(self::DUCTNETWORK_ID)->setProjectId(self::PROJECT_ID)->build();
        $this->project = ProjectBuilder::aProject()->setId(self::PROJECT_ID)->build();
        $this->project->addDuctNetwork($this->ductNetwork);
        $this->projecteRepository->addProject($this->project);
        $this->ductNetworkRepository->addDuctNetwork($this->ductNetwork);
        $this->updateDuctNetwork = new UpdateDuctNetwork($this->projecteRepository, $this->ductNetworkRepository, $this->ductSectionRepository);
    }

    public function present(UpdateDuctNetworkResponse $response): void
    {
        $this->response = $response;
    }

    public function test_duct_network_is_updated()
    {
        $request = UpdateDuctNetworkRequestBuilder::aRequest()
            ->setId(self::DUCTNETWORK_ID)
            ->setProjectId(self::PROJECT_ID)
            ->build();
        $this->updateDuctNetwork->execute($request, $this);

        $this->assertNotNull($this->response->getDuctNetwork());
        $this->assertNotEquals(
            $this->ductNetwork,
            $this->response->getDuctNetwork()
        );
    }

    public function test_return_null_when_duct_network_does_not_exist()
    {
        $request = UpdateDuctNetworkRequestBuilder::aRequest()
            ->setId(42)
            ->build();
        $this->updateDuctNetwork->execute($request, $this);

        $this->assertNull($this->response->getDuctNetwork());
    }

    public function test_return_null_when_project_does_not_exist()
    {
        $request = UpdateDuctNetworkRequestBuilder::aRequest()
            ->setProjectId(42)
            ->build();
        $this->updateDuctNetwork->execute($request, $this);

        $this->assertNull($this->response->getDuctNetwork());
    }

    public function test_return_null_when_duct_network_does_not_belong_to_the_right_project()
    {
        $otherDuctNetwork = DuctNetworkBuilder::aDuctNetwork()->setId(42)->build();
        $this->ductNetworkRepository->addDuctNetwork($otherDuctNetwork);
        
        $request = UpdateDuctNetworkRequestBuilder::aRequest()
            ->setId(42)
            ->build();
        $this->updateDuctNetwork->execute($request, $this);

        $this->assertNull($this->response->getDuctNetwork());
    }
}