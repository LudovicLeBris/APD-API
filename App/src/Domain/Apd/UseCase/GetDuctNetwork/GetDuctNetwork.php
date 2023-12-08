<?php

namespace App\Domain\Apd\UseCase\GetDuctNetwork;

use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;

class GetDuctNetwork
{
    private $ductNetworkRepository;

    public function __construct(DuctNetworkRepositoryInterface $ductNetworkRepository)
    {
        $this->ductNetworkRepository = $ductNetworkRepository;
    }

    public function execute(GetDuctNetworkRequest $request, GetDuctNetworkPresenter $presenter)
    {
        $response = new GetDuctNetworkResponse();

        $ductNetwork = $this->ductNetworkRepository->getDuctNetworkById($request->id);

        $response->setDuctNetwork($ductNetwork);

        $presenter->present($response);
    }
}