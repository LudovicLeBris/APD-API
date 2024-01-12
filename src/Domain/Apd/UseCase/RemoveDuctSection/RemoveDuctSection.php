<?php

namespace App\Domain\Apd\UseCase\RemoveDuctSection;

use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;
use App\Domain\Apd\Entity\DuctSectionRepositoryInterface;

class RemoveDuctSection
{
    private $ductSectionRepository;
    private $ductNetworkRepository;

    public function __construct(
        DuctSectionRepositoryInterface $ductSectionRepository,
        DuctNetworkRepositoryInterface $ductNetworkRepository
    )
    {
        $this->ductSectionRepository = $ductSectionRepository;
        $this->ductNetworkRepository = $ductNetworkRepository;
    }

    public function execute(RemoveDuctSectionRequest $request, RemoveDuctSectionPresenter $presenter)
    {
        $response = new RemoveDuctSectionResponse();
        $isValid = $this->checkRequest($request, $response);

        if ($isValid) {
            $ductNetwork = $this->ductNetworkRepository->getDuctNetworkById($request->ductNetworkId);
            $ductSectionToRemove = $this->ductSectionRepository->getDuctSectionById($request->ductSectionId);
            
            $this->ductSectionRepository->deleteDucSection($request->ductSectionId);
            $ductNetwork->removeDuctSection($ductSectionToRemove);
            $this->ductNetworkRepository->updateDuctNetwork($ductNetwork);
    
            $response->setDuctSection($ductSectionToRemove);
        }

        $presenter->present($response);
    }

    private function checkRequest(RemoveDuctSectionRequest $request, RemoveDuctSectionResponse $response)
    {
        $isValid = true;

        $ductNetwork = $this->ductNetworkRepository->getDuctNetworkById($request->ductNetworkId);
        if (is_null($ductNetwork)) {
            $response->addError('ductNetwork', 'There is no duct network with this id', 404);
            $isValid = $isValid && false;
        }

        $ductSectionToRemove = $this->ductSectionRepository->getDuctSectionById($request->ductSectionId);
        if (is_null($ductSectionToRemove)) {
            $response->addError('ductSection', 'There is no duct section with this id', 404);
            $isValid = $isValid && false;
        }

        return $isValid;
    }
}