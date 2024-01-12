<?php

namespace App\Tests\Domain\Apd\UseCase\AddDuctNetwork;

use App\Domain\Apd\UseCase\AddDuctNetwork\AddDuctNetwork;
use App\Domain\Apd\UseCase\AddDuctNetwork\AddDuctNetworkPresenter;
use App\Domain\Apd\UseCase\AddDuctNetwork\AddDuctNetworkResponse;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctNetworkRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryProjectRepository;
use App\Tests\Domain\Apd\Entity\DuctNetworkBuilder;
use App\Tests\Domain\Apd\Entity\ProjectBuilder;
use PHPUnit\Framework\TestCase;

class AddDuctNetworkTest extends TestCase implements AddDuctNetworkPresenter
{
    const DUCTNETWORK_ID = 1;
    const PROJECT_ID = 1;

    private $response;
    private $ductNetworkRepository;
    private $projectRepository;
    private $ductNetwork;
    private $project;
    private $addDuctNetwork;

    public function setUp(): void
    {
        $this->ductNetworkRepository = new InMemoryDuctNetworkRepository;
        $this->projectRepository = new InMemoryProjectRepository;
        $this->ductNetwork = DuctNetworkBuilder::aDuctNetwork()->setId(self::DUCTNETWORK_ID)->build();
        $this->project = ProjectBuilder::aProject()->setId(self::PROJECT_ID)->build();
        $this->projectRepository->addProject($this->project);
        $this->addDuctNetwork = new AddDuctNetwork($this->projectRepository, $this->ductNetworkRepository);
    }

    public function present(AddDuctNetworkResponse $response): void
    {
        $this->response = $response;
    }

    public function test_add_duct_network_in_database()
    {
        $request = AddDuctNetworkRequestBuilder::aRequest()->build();

        $this->addDuctNetwork->execute($request, $this);

        $this->assertNotNull($this->response->getDuctNetwork());
        $this->assertEquals(
            $this->response->getDuctNetwork(),
            $this->ductNetworkRepository->getDuctNetworkById($this->response->getDuctNetwork()->getId())
        );
    }

    public function test_fail_when_a_request_data_is_missing()
    {
        $request = AddDuctNetworkRequestBuilder::aRequest()->build();
        $request->name = null;
        $this->addDuctNetwork->execute($request, $this);

        $this->assertNull($this->response->getDuctNetwork());

    }

    public function test_if_calculation_is_done()
    {
        $request = AddDuctNetworkRequestBuilder::aRequest()->build();
        $this->addDuctNetwork->execute($request, $this);
        $ductNetwork = $this->response->getDuctNetwork();

        $this->assertIsFloat($ductNetwork->getTotalLinearApd());
        $this->assertIsFloat($ductNetwork->getTotalSingularApd());
        $this->assertIsFloat($ductNetwork->getTotalAdditionalApd());
        $this->assertIsFloat($ductNetwork->getTotalApd());
    }

    public function test_duct_network_is_save_in_project_object()
    {
        $request = AddDuctNetworkRequestBuilder::aRequest()->build();
        $this->addDuctNetwork->execute($request, $this);

        $lastDuctNetworkSavedInProject = $this->project->getDuctNetworks()[array_key_last($this->project->getDuctNetworks())];

        $this->assertSame(
            $this->response->getDuctNetwork(),
            $lastDuctNetworkSavedInProject
        );
    }
}