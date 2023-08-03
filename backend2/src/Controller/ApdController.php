<?php

namespace App\Controller;

use App\Service\Apd\DuctApd;
use App\Service\DuctApdService;
use App\Repository\DiameterRepository;
use App\Repository\MaterialRepository;
use App\Repository\SingularityRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/api")]
class ApdController extends AbstractController
{
    #[Route('/ductdimension', name:'app_apd_getDuctDimension', methods:['POST'])]
    public function optimalDuctDimension(DuctApdService $ductApdService): JsonResponse
    {
        $optimalDuctDimension = $ductApdService->getOptimalDuctDimension();
        
        return $this->json($optimalDuctDimension, 200);
    }

    #[Route('/ductsection', name:'app_apd_getDuctSection', methods:['POST'])]
    public function ductSection(DuctApdService $ductApdService): JsonResponse
    {
        $section = $ductApdService->getDuctSection();

        return $this->json($section, 200);
    }

    #[Route('/flowspeed', name:'app_apd_getFlowSpeed', methods:['POST'])]
    public function flowSpeed(DuctApdService $ductApdService): JsonResponse
    {
        $flowSpeed = $ductApdService->getFlowSpeed();

        return $this->json($flowSpeed, 200);
    }

    #[Route('/diameters', name:'app_apd_diametersList', methods:['GET'])]
    public function listDiameters(DiameterRepository $diameterRepository): JsonResponse
    {
        $diameterEntities = $diameterRepository->findAll();
        $diameters = [];
        foreach($diameterEntities as $diameter) {
            $diameters[] = $diameter->getDiameter();
        }

        return $this->json($diameters, 200);
    }

    #[Route('/materials', name:'app_apd_materialsList', methods:['GET'])]
    public function listMaterials(MaterialRepository $materialRepository): JsonResponse
    {
        $materialEntities = $materialRepository->findAll();
        $materials = [];
        foreach($materialEntities as $material) {
            $materials[] = $material->getName();
        }
        
        return $this->json($materials, 200);
    }

    #[Route('/singularities/{shape}', name:'app_apd_singularitiesList', methods:['GET'], requirements:['shape' => '^circular|rectangular$'])]
    public function listSingularities(SingularityRepository $singularityRepository, string $shape): JsonResponse
    {
        $singularityEntities = $singularityRepository->findBy(['shape' => $shape]);
        $singularities = [];
        foreach($singularityEntities as $singularity) {
            $singularities[] = $singularity->getLongName();
        }
        
        return $this->json($singularities, 200);
    }

    #[Route('/section', name:'app_apd_setSection', methods:['POST'])]
    public function setSection(DuctApdService $ductApdService): JsonResponse
    {
        $section = $ductApdService->getSection();

        return $this->json($section, 200);
    }

    #[Route('/sections', name:'app_apd_setSections', methods:['POST'])]
    public function setSections(DuctApdService $ductApdService)
    {
        $sections = $ductApdService->getSections();
        
        return $this->json($sections, 200);
    }
}
