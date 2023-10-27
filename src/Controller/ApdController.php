<?php

namespace App\Controller;

use App\Entity\DuctNetwork;
use App\Entity\DuctSection;
use App\Service\DuctSectionService;
use App\Utils\Data;
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

    #[Route('/ductdimension', name:'app_apd_getDuctDimension', methods:['GET'])]
    public function optimalDuctDimension(DuctSectionService $ductSectionService): JsonResponse
    {
        $response = $ductSectionService->getOptimalDuctDimension();
        
        return $this->json($response['response'], $response['httpResponse']);
    }

    #[Route('/ductsection', name:'app_apd_getDuctSection', methods:['GET'])]
    public function ductSection(DuctSectionService $ductSectionService): JsonResponse
    {
        $response = $ductSectionService->getDuctSection();

        return $this->json($response['response'], $response['httpResponse']);
    }

    #[Route('/flowspeed', name:'app_apd_getFlowSpeed', methods:['GET'])]
    public function flowSpeed(DuctSectionService $ductSectionService): JsonResponse
    {
        $response = $ductSectionService->getFlowSpeed();

        return $this->json($response['response'], $response['httpResponse']);
    }

    #[Route('/diameters', name:'app_apd_diametersList', methods:['GET'])]
    public function listDiameters(): JsonResponse
    {
        $diameters = Data::getDiameters();

        return $this->json($diameters, Response::HTTP_OK);
    }

    #[Route('/materials', name:'app_apd_materialsList', methods:['GET'])]
    public function listMaterials(): JsonResponse
    {
        $materials = Data::getMaterials();
        
        return $this->json($materials, Response::HTTP_OK);
    }

    #[Route('/singularities/{shape}', name:'app_apd_singularitiesList', methods:['GET'], requirements:['shape' => '^circular|rectangular$'])]
    public function listSingularities(string $shape): JsonResponse
    {
        $singularities = Data::getSingularitiesLongName($shape);
        
        return $this->json($singularities, Response::HTTP_OK);
    }

    #[Route('/section', name:'app_apd_setSection', methods:['GET'])]
    public function setSection(DuctSectionService $ductSectionService): JsonResponse
    {
        $response = $ductSectionService->getSection();

        return $this->json($response['response'], $response['httpResponse']);
    }

    #[Route('/network', name:'app_apd_setNetwork', methods:['GET'])]
    public function setNetwork(DuctSectionService $ductSectionService)
    {
        $response = $ductSectionService->getNetwork();
        
        return $this->json($response['response'], $response['httpResponse']);
    }
}
