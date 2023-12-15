<?php

namespace App\Tests\_Mock\Domain\Apd\Entity;

use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;

class InMemoryDuctNetworkRepository implements DuctNetworkRepositoryInterface
{
    private $ductNetworks = [];
    
    public function getDuctNetworkById(int $id): ?DuctNetwork
    {
        $find = function (DuctNetwork $ductNetwork) use ($id) {
            return $ductNetwork->getId() === $id;
        };

        $ductNetworksFound = array_values(array_filter($this->ductNetworks, $find));
        if(count($ductNetworksFound) === 1) {
            return $ductNetworksFound[0];
        }
        
        return null;
    }

    public function getDuctNetworksByProjectId(int $projectId): array
    {
        $find = function (DuctNetwork $ductNetwork) use ($projectId) {
            return $ductNetwork->getProjectId() === $projectId;
        };

        $ductNetworksFound = array_values(array_filter($this->ductNetworks, $find));

        return $ductNetworksFound;
    }

    public function addDuctNetwork(DuctNetwork $ductNetwork): void
    {
        if (!isset($ductNetwork->id)) {
            $ductNetwork->setId(mt_rand(0, 500));
        }
        
        $this->ductNetworks[] = $ductNetwork;
    }

    public function updateDuctNetwork(DuctNetwork $ductNetwork): void
    {
        for ($i=0; $i < count($this->ductNetworks); $i++) {
            if ($this->ductNetworks[$i]->getId() === $ductNetwork->getId()) {
                $this->ductNetworks[$i] = $ductNetwork;
                break;
            }
        }
    }

    public function deleteDucNetwork(int $id): void
    {
        for ($i=0; $i < count($this->ductNetworks); $i++) {
            if ($this->ductNetworks[$i]->getId() === $id) {
                array_splice($this->ductNetworks, $i, 1);
                break;
            }
        }
    }
}