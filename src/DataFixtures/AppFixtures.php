<?php

namespace App\DataFixtures;

use App\Domain\Apd\Entity\Project;
use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\Apd\Factory\DuctSectionFactory;
use App\Domain\AppUser\Entity\AppUser;
use App\Entity\DuctNetwork as DuctNetworkEntity;
use App\Entity\DuctSection as DuctSectionEntity;
use App\Entity\Project as ProjectEntity;
use App\Entity\User as UserEntity;
use App\SharedKernel\Service\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    
    public function __construct(PasswordHasher $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    
    public function load(ObjectManager $manager): void
    {
        // Set all domains entity
        
        $appUser = new AppUser(
            "john.doe@demo.fr",
            $this->passwordHasher->hash("demoDem0!"),
            "Doe",
            "John",
            null,
            "appUser",
            true
        );

        $project = new Project("Projet test");

        $ductNetwork = new DuctNetwork(
            "Réseau n°1",
            "galvanised_steel",
            50
        );
        $project->addDuctNetwork($ductNetwork);
        
        $ductSectionFactory = new DuctSectionFactory();
        $ductSectionFactory->setSectionTechnicalDatas([
            "air" => $ductNetwork->getAir(),
            "shape" => "circular",
            "material" => $ductNetwork->getGeneralMaterial(),
            "flowrate" => 5000,
            "length" => 15,
            "singularities" => [
                "90_elbow" => 1,
                "45_elbow" => 2
            ],
            "additionalApd" => 50,
            "diameter" => 500
        ]);
        $ductSection = $ductSectionFactory->createDuctSection();
        $ductSection->setName("Section n°1");
        $ductNetwork->addDuctSection($ductSection);
        
        
        // set Symfony's entities
        
        $userEntity = new UserEntity();
        $userEntity
            ->setEmail($appUser->getEmail())
            ->setPassword($appUser->getPassword())
            ->setLastname($appUser->getLastname())
            ->setFirstname($appUser->getFirstname())
            ->setRoles([$appUser->getRole()])
            ->setIsEnable($appUser->getIsEnable())
        ;
        
        
        $projectEntity = new ProjectEntity();
        $projectEntity
        ->setUser($userEntity)
        ->setName($project->getName())
        ->setGeneralAltitude($project->getGeneralAltitude())
        ->setGeneralTemperature($project->getGeneralTemperature())
        ;
        
        $userEntity->addProject($projectEntity);
        
        $ductNetworkEntity = new DuctNetworkEntity();
        $ductNetworkEntity
        ->setProject($projectEntity)
        ->setName($ductNetwork->getName())
        ->setAltitude($ductNetwork->getAltitude())
        ->setTemperature($ductNetwork->getTemperature())
        ->setGeneralMaterial($ductNetwork->getGeneralMaterial())
        ->setAdditionalApd($ductNetwork->getAdditionalApd())
        ->setTotalLinearApd($ductNetwork->getTotalLinearApd())
        ->setTotalSingularApd($ductNetwork->getTotalSingularApd())
        ->setTotalAdditionalApd($ductNetwork->getTotalAdditionalApd())
        ->setTotalApd($ductNetwork->getTotalApd())
        ->setAir($ductNetwork->getAir())
        ;
        
        $projectEntity->addDuctNetwork($ductNetworkEntity);

        $ductSectionEntity = new DuctSectionEntity();
        $ductSectionEntity
            ->setDuctNetwork($ductNetworkEntity)
            ->setName($ductSection->getName())
            ->setShape($ductSection->getShape())
            ->setMaterial($ductSection->getMaterial())
            ->setFlowrate($ductSection->getFlowrate())
            ->setLength($ductSection->getLength())
            ->setSingularities($ductSection->getSingularities())
            ->setAdditionalApd($ductSection->getAdditionalApd())
            ->setDiameter($ductSection->getDiameter())
            ->setWidth($ductSection->getWidth())
            ->setHeight($ductSection->getHeight())
            ->setEquivDiameter($ductSection->getEquivDiameter())
            ->setDuctSectionsSection($ductSection->getDuctSectionsSection())
            ->setFlowspeed($ductSection->getFlowspeed())
            ->setLinearApd($ductSection->getLinearApd())
            ->setSingularApd($ductSection->getSingularApd())
            ->setTotalApd($ductSection->getTotalApd())
            ->setAir($ductSection->getAir())
        ;

        $ductNetworkEntity->addDuctSection($ductSectionEntity);
        
        $manager->persist($userEntity);
        $manager->persist($projectEntity);
        $manager->persist($ductNetworkEntity);
        $manager->persist($ductSectionEntity);

        $manager->flush();
    }
}
