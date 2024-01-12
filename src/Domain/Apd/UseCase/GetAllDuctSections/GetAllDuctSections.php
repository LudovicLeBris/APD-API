<?php

namespace App\Domain\Apd\UseCase\GetAllDuctSections;

use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;
use App\Domain\Apd\Entity\DuctSectionRepositoryInterface;

class GetAllDuctSections
{
    private $ductNetworkRepository;
    private $ductSectionRepository;

    public function __construct(
        DuctNetworkRepositoryInterface $ductNetworkRepository,
        DuctSectionRepositoryInterface $ductSectionRepository
    )
    {
        $this->ductNetworkRepository = $ductNetworkRepository;
        $this->ductSectionRepository = $ductSectionRepository;
    }

    public function execute(GetAllDuctSectionsRequest $request, GetAllDuctSectionsPresenter $presenter)
    {
        $response = new GetAllDuctSectionsResponse();

        $ductNetwork = $this->ductNetworkRepository->getDuctNetworkById($request->ductNetworkId);
        $isValid = $this->checkDuctNetworkExist($ductNetwork, $response);

        if ($isValid) {
            $response->setAllDuctSections($this->ductSectionRepository->getDuctSectionsByDuctNetworkId($ductNetwork->getId()));
        }

        $presenter->present($response);

    }

    private function checkDuctNetworkExist(?DuctNetwork $ductNetwork, GetAllDuctSectionsResponse $response)
    {
        if ($ductNetwork) {
            return true;
        }

        $response->addError('ductNetworkId', 'Duct network doesn\'t exist with this id.', 404);
        return false;
    }
}