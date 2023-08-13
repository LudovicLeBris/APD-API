<?php

namespace App\Controller;

use App\Service\DuctSectionService;
use App\Repository\DiameterRepository;
use App\Repository\MaterialRepository;
use App\Repository\SingularityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/api")]
class ApdController extends AbstractController
{
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
    public function listDiameters(DiameterRepository $diameterRepository): JsonResponse
    {
        $diameterEntities = $diameterRepository->findAll();
        $diameters = [];
        foreach($diameterEntities as $diameter) {
            $diameters[] = $diameter->getDiameter();
        }

        return $this->json($diameters, Response::HTTP_OK);
    }

    #[Route('/materials', name:'app_apd_materialsList', methods:['GET'])]
    public function listMaterials(MaterialRepository $materialRepository): JsonResponse
    {
        $materialEntities = $materialRepository->findAll();
        $materials = [];
        foreach($materialEntities as $material) {
            $materials[] = $material->getName();
        }
        
        return $this->json($materials, Response::HTTP_OK);
    }

    #[Route('/singularities/{shape}', name:'app_apd_singularitiesList', methods:['GET'], requirements:['shape' => '^circular|rectangular$'])]
    public function listSingularities(SingularityRepository $singularityRepository, string $shape): JsonResponse
    {
        $singularities = $singularityRepository->findBy(['shape' => $shape]);
        
        return $this->json($singularities, Response::HTTP_OK);
    }

    #[Route('/section', name:'app_apd_setSection', methods:['GET'])]
    public function setSection(DuctSectionService $ductSectionService): JsonResponse
    {
        $response = $ductSectionService->getSection();

        return $this->json($response['response'], $response['httpResponse']);
    }

    #[Route('/sections', name:'app_apd_setSections', methods:['GET'])]
    public function setSections(DuctSectionService $ductSectionService)
    {
        $response = $ductSectionService->getSections();
        
        return $this->json($response['response'], $response['httpResponse']);
    }
}
