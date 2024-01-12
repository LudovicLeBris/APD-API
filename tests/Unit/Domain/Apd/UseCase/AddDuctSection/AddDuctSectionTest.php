<?php

namespace App\Tests\Domain\Apd\UseCase\AddDuctSection;

use PHPUnit\Framework\TestCase;
use App\Domain\Apd\UseCase\AddDuctSection\AddDuctSection;
use App\Domain\Apd\UseCase\AddDuctSection\AddDuctSectionResponse;
use App\Domain\Apd\UseCase\AddDuctSection\AddDuctSectionPresenter;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctNetworkRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctSectionRepository;
use App\Tests\Domain\Apd\Entity\DuctNetworkBuilder;
use App\Tests\Domain\Apd\Entity\DuctSectionBuilder;

class AddDuctSectionTest extends TestCase implements AddDuctSectionPresenter
{
    const DUCTSECTION_ID = 1;

    private $response;
    private $ductSectionRepository;
    private $ductNetworkRepository;
    private $addDuctSection;
    private $ductSection;
    private $ductNetwork;
    
    public function setUp(): void
    {
        $this->ductSectionRepository = new InMemoryDuctSectionRepository;
        $this->ductNetworkRepository = new InMemoryDuctNetworkRepository;
        $this->ductNetwork = DuctNetworkBuilder::aDuctNetwork()->setId(1)->build();
        $this->ductSection = DuctSectionBuilder::aDuctSection()->setId(self::DUCTSECTION_ID)->build();
        $this->ductNetworkRepository->addDuctNetwork($this->ductNetwork);
        $this->addDuctSection = new AddDuctSection($this->ductSectionRepository, $this->ductNetworkRepository);
    }
    
    public function present(AddDuctSectionResponse $response): void
    {
        $this->response = $response;
    }

    public function test_add_duct_section_in_database()
    {
        $request = AddDuctSectionRequestBuilder::aRequest()->build();
        $this->addDuctSection->execute($request, $this);

        $this->assertNotNull($this->response->getDuctSection());
        $this->assertEquals(
            $this->response->getDuctSection(),
            $this->ductSectionRepository->getDuctSectionById($this->response->getDuctSection()->getId())
        );
    }

    public function test_fail_when_a_request_data_is_missing()
    {
        $request = AddDuctSectionRequestBuilder::aRequest()->build();
        $request->shape = null;
        $this->addDuctSection->execute($request, $this);

        $this->assertNull($this->response->getDuctSection());
    }

    public function test_get_errors_when_a_request_data_is_missing()
    {
        $request = AddDuctSectionRequestBuilder::aRequest()->build();
        $request->diameter = null;
        $this->addDuctSection->execute($request, $this);

        $this->assertGreaterThan(0, count($this->response->getErrors()));
    }

    public function test_if_calculation_is_done()
    {
        $request = AddDuctSectionRequestBuilder::aRequest()->build();
        $this->addDuctSection->execute($request, $this);
        $ductSection = $this->response->getDuctSection();

        $this->assertIsFloat($ductSection->getLinearApd());
        $this->assertIsFloat($ductSection->getSingularApd());
        $this->assertIsFloat($ductSection->getTotalApd());
    }

    public function test_duct_section_is_save_in_duct_network_object()
    {
        $request = AddDuctSectionRequestBuilder::aRequest()->build();
        $this->addDuctSection->execute($request, $this);

        $lastDuctSectionSavedInDuctNetwork = $this->ductNetwork->getDuctSections()[array_key_last($this->ductNetwork->getDuctSections())];

        $this->assertSame(
            $this->response->getDuctSection(),
            $lastDuctSectionSavedInDuctNetwork
        );
    }
}