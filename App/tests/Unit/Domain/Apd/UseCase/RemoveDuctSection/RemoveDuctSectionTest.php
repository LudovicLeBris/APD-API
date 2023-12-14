<?php

namespace App\Tests\Domain\Apd\UseCase\RemoveDuctSection;

use PHPUnit\Framework\TestCase;
use App\Tests\Domain\Apd\Entity\DuctSectionBuilder;
use App\Domain\Apd\UseCase\RemoveDuctSection\RemoveDuctSection;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctNetworkRepository;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctSectionRepository;
use App\Domain\Apd\UseCase\RemoveDuctSection\RemoveDuctSectionRequest;
use App\Domain\Apd\UseCase\RemoveDuctSection\RemoveDuctSectionResponse;
use App\Domain\Apd\UseCase\RemoveDuctSection\RemoveDuctSectionPresenter;
use App\Tests\Domain\Apd\Entity\DuctNetworkBuilder;

class RemoveDuctSectionTest extends TestCase implements RemoveDuctSectionPresenter
{
    const DUCTSECTION_ID = 3;
    
    private $response;
    private $ductSectionRepository;
    private $ductNetworkRepository;
    private $removeDuctSection;
    private $ductNetwork;
    private $ductSection;

    public function setUp(): void
    {
        $this->ductSectionRepository = new InMemoryDuctSectionRepository();
        $this->ductNetworkRepository = new InMemoryDuctNetworkRepository();
        $this->ductNetwork = DuctNetworkBuilder::aDuctNetwork()->setId(1)->build();
        $this->ductSection = DuctSectionBuilder::aDuctSection()->setId(self::DUCTSECTION_ID)->build();
        $this->ductNetwork->addDuctSection($this->ductSection);
        $this->ductNetworkRepository->addDuctNetwork($this->ductNetwork);
        $this->ductSectionRepository->addDuctSection($this->ductSection);
        $this->removeDuctSection = new RemoveDuctSection($this->ductSectionRepository, $this->ductNetworkRepository);
    }

    public function present(RemoveDuctSectionResponse $response): void
    {
        $this->response = $response;
    }

    public function test_duct_section_is_deleting_from_database()
    {
        $this->removeDuctSection->execute(new RemoveDuctSectionRequest('1', self::DUCTSECTION_ID), $this);

        $this->assertNull($this->ductSectionRepository->getDuctSectionById(self::DUCTSECTION_ID));
    }

    public function test_duct_section_is_removing_from_duct_network_object()
    {
        $ductSectionsCountInDuctNetworkBeforeRemoving = count($this->ductNetwork->getDuctSections());
        $this->removeDuctSection->execute(new RemoveDuctSectionRequest('1', self::DUCTSECTION_ID), $this);
        $ductSectionsCountInDuctNetworkAfterRemoving = count($this->ductNetwork->getDuctSections());

        $this->assertLessThan($ductSectionsCountInDuctNetworkBeforeRemoving, $ductSectionsCountInDuctNetworkAfterRemoving);
        $this->assertSame(
            $this->response->getDuctSection(),
            $this->ductSection
        );
    }
}