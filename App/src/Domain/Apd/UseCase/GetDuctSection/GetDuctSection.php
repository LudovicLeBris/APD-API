<?php

namespace App\Domain\Apd\UseCase\GetDuctSection;

use App\Domain\Apd\Entity\DuctSectionRepositoryInterface;

class GetDuctSection
{
    private $ductSectionRepository;

    public function __construct(DuctSectionRepositoryInterface $ductSectionRepository)
    {
        $this->ductSectionRepository = $ductSectionRepository;
    }

    public function execute(GetDuctSectionRequest $request, GetDuctSectionPresenter $presenter)
    {
        $response = new GetDuctSectionResponse();

        $ductSection = $this->ductSectionRepository->getDuctSectionById($request->id);

        $response->setDuctSection($ductSection);

        $presenter->present($response);
    }
}