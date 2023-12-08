<?php

namespace App\Repository;

use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\Apd\Entity\DuctSection;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Apd\Factory\DuctSectionFactory;
use App\Domain\Apd\Entity\DuctSectionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DuctSectionRepositoryTest extends ServiceEntityRepository implements DuctSectionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DuctSection::class);
    }
    
    public function getDuctSectionById(int $id): ?DuctSection
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

        // return null;
        return $ductSection;
    }

    public function addDuctSection(DuctSection $ductSection): void
    {
        
    }

    public function deleteDucSection(int $id): void
    {

    }

    public function updateDuctSection(DuctSection $ductsection): void
    {

    }
}