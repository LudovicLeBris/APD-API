<?php

namespace App\Tests\_Mock\Domain\Apd\Entity;

use App\Domain\Apd\Entity\DuctSection;
use App\Domain\Apd\Entity\DuctSectionRepositoryInterface;

class InMemoryDuctSectionRepository implements DuctSectionRepositoryInterface
{
    private $ductSections = [];
    
    public function getAllDuctSection()
    {
        return $this->ductSections;
    }
    
    public function getDuctSectionById(int $id): ?DuctSection
    {
        $find = function (DuctSection $ductSection) use ($id) {
            return $ductSection->getId() === $id;
        };

        $DuctSectionsFound = array_values(array_filter($this->ductSections, $find));
        if(count($DuctSectionsFound) === 1) {
            return $DuctSectionsFound[0];
        }
        
        return null;
    }

    public function getDuctSectionsByDuctNetworkId(int $ductNetworkId): array
    {
        $find = function (DuctSection $ductSection) use ($ductNetworkId) {
            return $ductSection->getDuctNetworkId() === $ductNetworkId;
        };

        $ductSectionsFound = array_values(array_filter($this->ductSections, $find));

        return $ductSectionsFound;
    }

    public function addDuctSection(DuctSection $ductSection): void
    {
        if (!isset($ductSection->id)) {
            $ductSection->setId(mt_rand(0, 500));
        }
        
        $this->ductSections[] = $ductSection;
    }

    public function updateDuctSection(DuctSection $ductSection): void
    {
        for ($i=0; $i < count($this->ductSections); $i++) {
            if ($this->ductSections[$i]->getId() === $ductSection->getId()) {
                $this->ductSections[$i] = $ductSection;
                break;
            }
        }
    }

    public function deleteDucSection(int $id): void
    {
        for ($i=0; $i < count($this->ductSections); $i++) {
            if ($this->ductSections[$i]->getId() === $id) {
                array_splice($this->ductSections, $i, 1);
                break;
            }
        }
    }
}