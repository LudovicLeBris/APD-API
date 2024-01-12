<?php

namespace App\Tests\Domain\Apd\UseCase\RemoveDuctNetwork;

use App\Domain\Apd\UseCase\RemoveDuctNetwork\RemoveDuctNetwork;
use App\Domain\Apd\UseCase\RemoveDuctNetwork\RemoveDuctNetworkPresenter;
use App\Domain\Apd\UseCase\RemoveDuctNetwork\RemoveDuctNetworkRequest;
use App\Domain\Apd\UseCase\RemoveDuctNetwork\RemoveDuctNetworkResponse;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctNetworkRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctSectionRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryProjectRepository;
use App\Tests\Domain\Apd\Entity\DuctNetworkBuilder;
use App\Tests\Domain\Apd\Entity\DuctSectionBuilder;
use App\Tests\Domain\Apd\Entity\ProjectBuilder;
use PHPUnit\Framework\TestCase;

class RemoveDuctNetworkTest extends TestCase implements RemoveDuctNetworkPresenter
{
    const DUCTSECTION_ID = 1;
    const DUCTNETWORK_ID = 1;
    const PROJECT_ID = 1;
    
    private $response;
    private $ductSectionRepository;
    private $ductNetWorkRepository;
    private $projectRepository;
    private $removeDuctNetwork;
    private $ductSection;
    private $ductNetwork;
    private $project;

    public function setUp(): void
    {
        $this->ductSectionRepository = new InMemoryDuctSectionRepository;
        $this->ductNetWorkRepository = new InMemoryDuctNetworkRepository;
        $this->projectRepository = new InMemoryProjectRepository;
        $this->project = ProjectBuilder::aProject()->setId(self::PROJECT_ID)->build();
        $this->ductNetwork = DuctNetworkBuilder::aDuctNetwork()->setId(self::DUCTNETWORK_ID)->setProjectId(self::PROJECT_ID)->build();
        $this->ductSection = DuctSectionBuilder::aDuctSection()->setId(self::DUCTSECTION_ID)->setDuctNetworkId(self::DUCTNETWORK_ID)->build();
        $this->ductNetwork->addDuctSection($this->ductSection);
        $this->project->addDuctNetwork($this->ductNetwork);
        $this->projectRepository->addProject($this->project);
        $this->ductNetWorkRepository->addDuctNetwork($this->ductNetwork);
        $this->ductSectionRepository->addDuctSection($this->ductSection);
        $this->removeDuctNetwork = new RemoveDuctNetwork($this->projectRepository, $this->ductNetWorkRepository, $this->ductSectionRepository);
    }

    public function present(RemoveDuctNetworkResponse $response): void
    {
        $this->response = $response;
    }

    public function test_duct_network_is_deleting_from_database()
    {
        $this->removeDuctNetwork->execute(new RemoveDuctNetworkRequest(self::PROJECT_ID, self::DUCTNETWORK_ID), $this);

        $this->assertNull($this->ductNetWorkRepository->getDuctNetworkById(self::DUCTNETWORK_ID));
    }

    public function test_duct_network_is_removing_from_project_object()
    {
        $projectCountInProjectBeforeRemoving = count($this->project->getDuctNetworks());
        $this->removeDuctNetwork->execute(new RemoveDuctNetworkRequest(self::PROJECT_ID, self::DUCTNETWORK_ID), $this);
        $projectCountInProjectAfterRemoving = count($this->project->getDuctNetworks());

        $this->assertLessThan($projectCountInProjectBeforeRemoving, $projectCountInProjectAfterRemoving);
        $this->assertSame(
            $this->response->getDuctNetwork(),
            $this->ductNetwork
        );
    }
}