<?php

namespace App\Domain\Apd\Entity;

interface DuctNetworkRepositoryInterface
{
    public function getDuctNetworkById(int $id): ?DuctNetwork;

    public function getDuctNetworksByProjectId(int $projectId): array;

    public function addDuctNetwork(DuctNetwork $ductNetwork);

    public function updateDuctNetwork(DuctNetwork $ductNetwork);

    public function deleteDucNetwork(int $id): void;
}