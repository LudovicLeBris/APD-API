<?php

namespace App\Tests\Domain\Apd\UseCase\GetDuctNetwork;

use App\Domain\Apd\UseCase\GetDuctNetwork\GetDuctNetwork;
use PHPUnit\Framework\TestCase;
use App\Domain\Apd\UseCase\GetDuctNetwork\GetDuctNetworkResponse;
use App\Domain\Apd\UseCase\GetDuctNetwork\GetDuctNetworkPresenter;
use App\Domain\Apd\UseCase\GetDuctNetwork\GetDuctNetworkRequest;
use App\Tests\_Mock\Domain\Apd\Entity\InMemoryDuctNetworkRepository;
use App\Tests\Domain\Apd\Entity\DuctNetworkBuilder;

class GetDuctNetworkTest extends TestCase implements GetDuctNetworkPresenter
{
    const DUCTNETWORK_ID = 1;
    
    private $response;
    private $ductNetWorkRepository;
    private $getDuctNetwork;
    private $ductNetwork;

    public function setUp(): void
    {
        $this->ductNetWorkRepository = new InMemoryDuctNetworkRepository;
        $this->ductNetwork = DuctNetworkBuilder::aDuctNetwork()->setId(self::DUCTNETWORK_ID)->build();
        $this->ductNetWorkRepository->addDuctNetwork($this->ductNetwork);
        $this->getDuctNetwork = new GetDuctNetwork($this->ductNetWorkRepository);
    }

    public function present(GetDuctNetworkResponse $response): void
    {
        $this->response = $response;
    }

    public function test_return_duct_network()
    {
        $this->getDuctNetwork->execute(new GetDuctNetworkRequest(self::DUCTNETWORK_ID), $this);

        $this->assertNotNull($this->response->getDuctNetwork());
        $this->assertSame(
            $this->response->getDuctNetwork(),
            $this->ductNetwork
        );
    }

    public function test_return_null_when_duct_network_does_not_exist()
    {
        $this->getDuctNetwork->execute(new GetDuctNetworkRequest(mt_rand(1, 500)), $this);

        $this->assertNull($this->response->getDuctNetwork());
    }
}