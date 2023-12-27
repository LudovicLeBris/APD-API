<?php

namespace App\Tests\Domain\Apd\UseCase\GetAllDuctSections;

use App\Domain\Apd\UseCase\GetAllDuctSections\GetAllDuctSections;
use App\Domain\Apd\UseCase\GetAllDuctSections\GetAllDuctSectionsPresenter;
use App\Domain\Apd\UseCase\GetAllDuctSections\GetAllDuctSectionsRequest;
use App\Domain\Apd\UseCase\GetAllDuctSections\GetAllDuctSectionsResponse;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctNetworkRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctSectionRepository;
use App\Tests\Domain\Apd\Entity\DuctNetworkBuilder;
use App\Tests\Domain\Apd\Entity\DuctSectionBuilder;
use PHPUnit\Framework\TestCase;

class GetAllDuctSectionsTest extends TestCase implements GetAllDuctSectionsPresenter
{
    const DUCTNETWORK_ID = 1;
    const NUMBER_OF_DUCTSECTIONS = 10;

    private $response;
    private $ductNetworkRepository;
    private $ductSectionRepository;
    private $ductNetwork;
    private $getAllDuctSections;

    public function setUp(): void
    {
        $this->ductNetworkRepository = new InMemoryDuctNetworkRepository;
        $this->ductSectionRepository = new InMemoryDuctSectionRepository;
        $this->ductNetwork = DuctNetworkBuilder::aDuctNetwork()->setId(self::DUCTNETWORK_ID)->build();
        $loopIndex = 10;
        $loopEnd = $loopIndex + self::NUMBER_OF_DUCTSECTIONS;
        for ($loopIndex; $loopIndex < $loopEnd; $loopIndex++) { 
            $aDuctSection = DuctSectionBuilder::aDuctSection()
                ->setId($loopIndex)
                ->setDuctNetworkId(self::DUCTNETWORK_ID)
                ->setName('duct section n°'. $loopIndex)
                ->build();
            $this->ductNetwork->addDuctSection($aDuctSection);
            $this->ductSectionRepository->addDuctSection($aDuctSection);
        }
        $this->ductNetworkRepository->addDuctNetwork($this->ductNetwork);
        $this->getAllDuctSections = new GetAllDuctSections($this->ductNetworkRepository, $this->ductSectionRepository);
    }

    public function present(GetAllDuctSectionsResponse $response): void
    {
        $this->response = $response;
    }

    public function test_return_all_duct_sections_in_array()
    {
        $this->getAllDuctSections->execute(new GetAllDuctSectionsRequest(self::DUCTNETWORK_ID), $this);

        $this->assertNotNull($this->response->getAllDuctSections());
        $this->assertIsArray($this->response->getAllDuctSections());
        $this->assertCount(self::NUMBER_OF_DUCTSECTIONS, $this->response->getAllDuctSections());
    }

    public function test_fails_when_duct_network_does_not_exist()
    {
        $this->getAllDuctSections->execute(new GetAllDuctSectionsRequest(42), $this);

        $shouldResponseBe = new GetAllDuctSectionsResponse();
        $shouldResponseBe->addError('ductNetworkId', 'Duct network doesn\'t exist with this id.');

        $this->assertNull($this->response->getAllDuctSections());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }
}