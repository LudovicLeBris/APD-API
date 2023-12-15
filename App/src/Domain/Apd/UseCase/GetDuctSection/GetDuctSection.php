<?php

namespace App\Domain\Apd\UseCase\GetDuctSection;

use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\Apd\Entity\DuctSection;
use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;
use App\Domain\Apd\Entity\DuctSectionRepositoryInterface;

class GetDuctSection
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

    public function execute(GetDuctSectionRequest $request, GetDuctSectionPresenter $presenter)
    {
        $response = new GetDuctSectionResponse();

        $ductNetwork = $this->ductNetworkRepository->getDuctNetworkById($request->ductNetworkId);
        $ductSection = $this->ductSectionRepository->getDuctSectionById($request->ductSectionId);
        $isValid = $this->checkDuctNetworkExist($ductNetwork, $response);
        $isValid = $isValid && $this->checkDuctSectionExist($ductSection, $response);
        $isValid = $isValid && $this->checkIfDuctSectionExistInDuctNetwork($ductNetwork, $request, $response);
        
        if ($isValid) {
            $response->setDuctSection($ductSection);
        }

        $presenter->present($response);
    }

    private function checkDuctNetworkExist(?DuctNetwork $ductNetwork, GetDuctSectionResponse $response)
    {
        if ($ductNetwork) {
            return true;
        }

        $response->addError('ductNetworkId', 'Duct network doesn\'t exist with this id.');
        return false;
    }

    private function checkDuctSectionExist(?DuctSection $ductSection, GetDuctSectionResponse $response)
    {
        if ($ductSection) {
            return true;
        }

        $response->addError('ductSectionId', 'Duct section doesn\'t exist with this id.');
        return false;
    }

    private function checkIfDuctSectionExistInDuctNetwork(?DuctNetwork $ductNetwork, GetDuctSectionRequest $request, GetDuctSectionResponse $response)
    {
        foreach ($ductNetwork->getDuctSections() as $ductSection) {
            if ($ductSection->getId() === $request->ductSectionId) {
                return true;
            }
        }

        $response->addError('ductSectionId', 'Duct section don\'t belong to this ductNetwork.');
        return false;
    }
}