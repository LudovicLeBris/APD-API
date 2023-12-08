<?php

namespace App\Repository;

use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\Apd\Entity\DuctSection;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Apd\Factory\DuctSectionFactory;
use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DuctNetworkRepositoryTest extends ServiceEntityRepository implements DuctNetworkRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DuctNetwork::class);
    }
    
    public function getDuctNetworkById(int $id): ?DuctNetwork
    {
        $ductNetwork = new DuctNetwork("A", "galvanised_steel", 20);
        $ductNetwork->setId(1);

        $ductSectionFactory = new DuctSectionFactory();
        $ductSectionFactory->setSectionTechnicalDatas([
            "air" => $ductNetwork->getAir(),
            "shape" => 'rectangular',
            "material" => $ductNetwork->getGeneralMaterial(),
            "flowrate" => 5000,
            "length" => 10,
            "singularities" => [
                "90_elbow" => 1,
                "90_junc_tee" => 1
            ],
            "additionalApd" => 10,
            "width" => 500,
            "height" => 300
        ]);

        $ductSection = $ductSectionFactory->createDuctSection()->setId(2);
        $ductSection->setName("A")->setDuctNetworkId($ductNetwork->getId());

        $ductNetwork->addDuctSection($ductSection);

        // return null;
        return $ductNetwork;
    }

    public function addDuctNetwork(DuctNetwork $ductNetwork): void
    {
        
    }

    public function updateDuctNetwork(DuctNetwork $ductNetwork): void
    {

    }

    public function deleteDucNetwork(int $id): void
    {

    }
}