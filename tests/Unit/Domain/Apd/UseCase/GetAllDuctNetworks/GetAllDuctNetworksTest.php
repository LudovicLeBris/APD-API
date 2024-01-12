<?php

namespace App\Tests\Domain\Apd\UseCase\GetAllDuctNetworks;

use App\Domain\Apd\UseCase\GetAllDuctNetworks\GetAllDuctNetworks;
use App\Domain\Apd\UseCase\GetAllDuctNetworks\GetAllDuctNetworksPresenter;
use App\Domain\Apd\UseCase\GetAllDuctNetworks\GetAllDuctNetworksRequest;
use App\Domain\Apd\UseCase\GetAllDuctNetworks\GetAllDuctNetworksResponse;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctNetworkRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryProjectRepository;
use App\Tests\Domain\Apd\Entity\DuctNetworkBuilder;
use App\Tests\Domain\Apd\Entity\ProjectBuilder;
use PHPUnit\Framework\TestCase;

class GetAllDuctNetworksTest extends TestCase implements GetAllDuctNetworksPresenter
{
    const PROJECT_ID = 1;
    const NUMBER_OF_DUCTNETWORKS = 5;

    private $response;
    private $projectRepository;
    private $ductNetworkRepository;
    private $project;
    private $getAllDuctNetworks;

    public function setUp(): void
    {
        $this->projectRepository = new InMemoryProjectRepository;
        $this->ductNetworkRepository = new InMemoryDuctNetworkRepository;
        $this->project = ProjectBuilder::aProject()->setId(self::PROJECT_ID)->build();
        $loopIndex = 10;
        $loopEnd = $loopIndex + self::NUMBER_OF_DUCTNETWORKS;
        for ($loopIndex; $loopIndex < $loopEnd; $loopIndex++) {
            $aDuctNetwork = DuctNetworkBuilder::aDuctNetwork()
                ->setId($loopIndex)
                ->setProjectId(self::PROJECT_ID)
                ->setName('duct network n°'. $loopIndex)
                ->build();
            $this->project->addDuctNetwork($aDuctNetwork);
            $this->ductNetworkRepository->addDuctNetwork($aDuctNetwork);
        }
        $this->projectRepository->addProject($this->project);
        $this->getAllDuctNetworks = new GetAllDuctNetworks($this->projectRepository, $this->ductNetworkRepository);
    }

    public function present(GetAllDuctNetworksResponse $response): void
    {
        $this->response = $response;
    }

    public function test_return_all_duct_networks_in_array()
    {
        $this->getAllDuctNetworks->execute(new GetAllDuctNetworksRequest(self::PROJECT_ID), $this);

        $this->assertNotNull($this->response->getAllDuctNetworks());
        $this->assertNotNull($this->response->getAllDuctNetworks());
        $this->assertCount(self::NUMBER_OF_DUCTNETWORKS, $this->response->getAllDuctNetworks());
    }

    public function test_fails_when_project_does_not_exist()
    {
        $this->getAllDuctNetworks->execute(new GetAllDuctNetworksRequest(42), $this);

        $shouldResponseBe = new GetAllDuctNetworksResponse();
        $shouldResponseBe->addError('projectId', 'Project doesn\'t exist with this id.');

        $this->assertNull($this->response->getAllDuctNetworks());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }
}