<?php

namespace App\Tests\Domain\Apd\UseCase\UpdateDuctSection;

use PHPUnit\Framework\TestCase;
use App\Tests\Domain\Apd\Entity\DuctNetworkBuilder;
use App\Tests\Domain\Apd\Entity\DuctSectionBuilder;
use App\Domain\Apd\UseCase\UpdateDuctSection\UpdateDuctSection;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctNetworkRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctSectionRepository;
use App\Domain\Apd\UseCase\UpdateDuctSection\UpdateDuctSectionResponse;
use App\Domain\Apd\UseCase\UpdateDuctSection\UpdateDuctSectionPresenter;

class UpdateDuctSectionTest extends TestCase implements UpdateDuctSectionPresenter
{
    const DUCTSECTION_ID = 1;
    const DUCTNETWORK_ID = 1;
    
    private $response;
    private $ductSectionRepository;
    private $ductNetworkRepository;
    private $updateDuctSection;
    private $ductSection;
    private $ductNetwork;

    public function setUp(): void
    {
        $this->ductSectionRepository = new InMemoryDuctSectionRepository;
        $this->ductNetworkRepository = new InMemoryDuctNetworkRepository;
        $this->ductNetwork = DuctNetworkBuilder::aDuctNetwork()->setId(self::DUCTNETWORK_ID)->build();
        $this->ductSection = DuctSectionBuilder::aDuctSection()->setId(self::DUCTSECTION_ID)->build();
        $this->ductNetwork->addDuctSection($this->ductSection);
        $this->ductNetworkRepository->addDuctNetwork($this->ductNetwork);
        $this->ductSectionRepository->addDuctSection($this->ductSection);
        $this->updateDuctSection = new UpdateDuctSection($this->ductSectionRepository, $this->ductNetworkRepository);
    }

    public function present(UpdateDuctSectionResponse $response):void
    {
        $this->response = $response;
    }

    public function test_duct_section_is_updated()
    {
        $request = UpdateDuctSectionRequestBuilder::aRequest()
            ->setId(self::DUCTSECTION_ID)
            ->setDuctNetworkId(self::DUCTNETWORK_ID)
            ->build();
        $this->updateDuctSection->execute($request, $this);

        $this->assertNotNull($this->response->getDuctSection());
        $this->assertNotEquals(
            $this->ductSection,
            $this->response->getDuctSection()
        );
    }

    public function test_return_null_when_duct_section_does_not_exist()
    {
        $request = UpdateDuctSectionRequestBuilder::aRequest()
            ->setId(42)
            ->build();
        $this->updateDuctSection->execute($request, $this);

        $this->assertNull($this->response->getDuctSection());
    }

    public function test_return_null_when_duct_network_does_not_exist()
    {
        $request = UpdateDuctSectionRequestBuilder::aRequest()
            ->setDuctNetworkId(42)
            ->build();
        $this->updateDuctSection->execute($request, $this);

        $this->assertNull($this->response->getDuctSection());
    }

    public function test_return_null_when_duct_section_does_not_belong_to_the_right_duct_network()
    {
        $otherDuctSection = DuctSectionBuilder::aDuctSection()->setId(42)->build();
        $this->ductSectionRepository->addDuctSection($otherDuctSection);
        
        $request = UpdateDuctSectionRequestBuilder::aRequest()
            ->setId(42)
            ->build();
        $this->updateDuctSection->execute($request, $this);

        $this->assertNull($this->response->getDuctSection());
    }
}