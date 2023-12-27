<?php

namespace App\Tests\Domain\Apd\UseCase\GetDuctSection;

use PHPUnit\Framework\TestCase;
use App\Tests\Domain\Apd\Entity\DuctSectionBuilder;
use App\Domain\Apd\UseCase\GetDuctSection\GetDuctSection;
use App\Domain\Apd\UseCase\GetDuctSection\GetDuctSectionRequest;
use App\Domain\Apd\UseCase\GetDuctSection\GetDuctSectionResponse;
use App\Domain\Apd\UseCase\GetDuctSection\GetDuctSectionPresenter;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctNetworkRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctSectionRepository;
use App\Tests\Domain\Apd\Entity\DuctNetworkBuilder;

class GetDuctSectionTest extends TestCase implements GetDuctSectionPresenter
{
    const DUCTNETWORK_ID = 1;
    const DUCTSECTION_ID = 1;

    private $response;
    private $ductNetworkRepository;
    private $ductSectionRepository;
    private $ductNetwork;
    private $ductSection;
    private $getDuctSection;

    public function setUp(): void
    {
        $this->ductNetworkRepository = new InMemoryDuctNetworkRepository();
        $this->ductSectionRepository = new InMemoryDuctSectionRepository();
        $this->ductNetwork = DuctNetworkBuilder::aDuctNetwork()->setId(self::DUCTNETWORK_ID)->build();
        $this->ductSection = DuctSectionBuilder::aDuctSection()
            ->setId(self::DUCTSECTION_ID)
            ->setDuctNetworkId(self::DUCTNETWORK_ID)
            ->build();
        $this->ductNetwork->addDuctSection($this->ductSection);
        $this->ductNetworkRepository->addDuctNetwork($this->ductNetwork);
        $this->ductSectionRepository->addDuctSection($this->ductSection);
        $this->getDuctSection = new GetDuctSection($this->ductNetworkRepository, $this->ductSectionRepository);
    }

    public function present(GetDuctSectionResponse $response): void
    {
        $this->response = $response;
    }

    public function test_return_duct_section()
    {
        $this->getDuctSection->execute(new GetDuctSectionRequest(self::DUCTNETWORK_ID, self::DUCTSECTION_ID), $this);

        $this->assertNotNull($this->response->getDuctSection());
        $this->assertSame(
            $this->response->getDuctSection(),
            $this->ductSection
        );
    }

    public function test_fails_when_duct_network_does_not_exist()
    {
        $this->getDuctSection->execute(new GetDuctSectionRequest(mt_rand(0, 500), self::DUCTNETWORK_ID), $this);
        
        $shouldResponseBe = new GetDuctSectionResponse();
        $shouldResponseBe->addError('ductNetworkId', 'Duct network doesn\'t exist with this id.');

        $this->assertNull($this->response->getDuctSection());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }

    public function test_fails_when_duct_section_does_not_exist()
    {
        $this->getDuctSection->execute(new GetDuctSectionRequest(self::DUCTNETWORK_ID, mt_rand(0, 500)), $this);
                
        $shouldResponseBe = new GetDuctSectionResponse();
        $shouldResponseBe->addError('ductSectionId', 'Duct section doesn\'t exist with this id.');

        $this->assertNull($this->response->getDuctSection());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }

    public function test_fails_when_duct_section_does_not_belong_to_duct_network()
    {
        $anotherDuctSection = DuctSectionBuilder::aDuctSection()
            ->setId(42)
            ->setDuctNetworkId(42)
            ->build();
        $this->ductSectionRepository->addDuctSection($anotherDuctSection);
        $this->getDuctSection->execute(new GetDuctSectionRequest(self::DUCTNETWORK_ID, 42), $this);
                
        $shouldResponseBe = new GetDuctSectionResponse();
        $shouldResponseBe->addError('ductSectionId', 'Duct section don\'t belong to this ductNetwork.');

        $this->assertNull($this->response->getDuctSection());
        $this->assertEquals(
            $this->response,
            $shouldResponseBe
        );
    }
}