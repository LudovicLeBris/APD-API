<?php

namespace App\Controller;

use App\Entity\DuctNetwork;
use App\Entity\DuctSection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class ApdController extends AbstractController
{
    #[Route('/test', name: 'app_apd_test')]
    public function test(Request $request): JsonResponse
    {
        $ductSection = new DuctSection(
            'circular',
            'galvanised steel',
            500,
            null,
            null,
            5000,
            1,
            [
                '90_elbow' => 1,
                '90_junc_tee' => 1
            ],
            10
        );

        $ductSection2 = new DuctSection(
            'circular',
            'galvanised steel',
            500,
            null,
            null,
            5000,
            1,
            [
                '90_elbow' => 1,
                '90_junc_tee' => 1
            ],
            10
        );

        $ductNetwork = new DuctNetwork(10);
        $ductNetwork->addDuctSection($ductSection);
        $ductNetwork->addDuctSection($ductSection2);

        $response = [
            'equivDiameter' => $ductSection->getEquivDiameter(),
            'section' => $ductSection->getSection(),
            'flowSpeed' => $ductSection->getFlowSpeed(),
            'linearApd' => $ductSection->getLinearApd(),
            'singularApd' => $ductSection->getSingularApd(),
            'additionalApd' => $ductSection->getAdditionalApd(),
            'totaApd' => $ductSection->getTotalApd(),
            'ductNetworkTotalLinearApd' => $ductNetwork->getTotalLinearApd(),
            'ductNetworkTotalSingularApd' => $ductNetwork->getTotalSingularApd(),
            'ductNetworkTotalAdditionalApd' => $ductNetwork->getTotalAdditionalApd(),
            'ductNetworkTotalAllAdditionalApd' => $ductNetwork->getTotalAllAdditionalApd(),
            'ductNetworkTotalApd' => $ductNetwork->getTotalApd(),
        ];
        
        // $response = Data::getSingularityLongName('circular', '90_elbow');

        return $this->json(
            $response, 200
        );
    }

    #[Route('/', name: 'app_home')]
    public function home(Request $request): Response
    {
        return new Response("<html><body><h1>test</h1><div>". $request->getContent() ."</div></body></html>");
    }
}
