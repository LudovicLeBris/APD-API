<?php

namespace App\Tests\Domain\Apd\UseCase\GetDuctSection;

use App\Domain\Apd\UseCase\GetDuctSection\GetDuctSection;
use App\Domain\Apd\UseCase\GetDuctSection\GetDuctSectionPresenter;
use App\Domain\Apd\UseCase\GetDuctSection\GetDuctSectionRequest;
use App\Domain\Apd\UseCase\GetDuctSection\GetDuctSectionResponse;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctSectionRepository;
use App\Tests\Domain\Apd\Entity\DuctSectionBuilder;
use PHPUnit\Framework\TestCase;

class GetDuctSectionTest extends TestCase implements GetDuctSectionPresenter
{
    const DUCTSECTION_ID = 1;

    private $response;
    private $ductSectionRepository;
    private $getDuctSection;
    private $ductSection;

    public function setUp(): void
    {
        $this->ductSectionRepository = new InMemoryDuctSectionRepository();
        $this->ductSection = DuctSectionBuilder::aDuctSection()->setId(self::DUCTSECTION_ID)->build();
        $this->ductSectionRepository->addDuctSection($this->ductSection);
        $this->getDuctSection = new GetDuctSection($this->ductSectionRepository);
    }

    public function present(GetDuctSectionResponse $response): void
    {
        $this->response = $response;
    }

    public function test_return_duct_section()
    {
        $this->getDuctSection->execute(new GetDuctSectionRequest(self::DUCTSECTION_ID), $this);

        $this->assertNotNull($this->response->getDuctSection());
        $this->assertSame(
            $this->response->getDuctSection(),
            $this->ductSection
        );
    }

    public function test_return_null_when_duct_section_does_not_exist()
    {
        $this->getDuctSection->execute(new GetDuctSectionRequest(mt_rand(0, 500)), $this);

        $this->assertNull($this->response->getDuctSection());
    }
}