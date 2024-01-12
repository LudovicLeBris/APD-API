<?php

namespace App\Domain\Apd\Entity;

use App\Domain\Apd\Entity\DuctSection;

interface DuctSectionRepositoryInterface
{
    public function getDuctSectionById(int $id): ?DuctSection;

    public function getDuctSectionsByDuctNetworkId(int $ductNetworkId): array;

    public function addDuctSection(DuctSection $ductSection): void;

    public function updateDuctSection(DuctSection $ductSection): void;

    public function deleteDucSection(int $id): void;
}