<?php

namespace App\Controller;

use App\Repository\DiameterRepository;
use App\Repository\MaterialRepository;
use App\Repository\SingularityRepository;
use App\Service\Apd\DuctApd;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api")]
class ApdController extends AbstractController
{
    #[Route('/ductdimension', name:'app_apd_getDuctDimension', methods:['POST'])]
    public function optimalDuctDimension(Request $request, DuctApd $ductApd): JsonResponse
    {
        $post = json_decode($request->getContent(), true);
        $shape = $post['shape'];
        $flowRate = $post['flowRate'];

        if($shape === 'rectangular') {
            $secondsize = $post['width'];
        } else {
            $secondsize = 0;
        }

        if(array_key_exists('flowSpeed', $post)) {
            $idealFlowSpeed = $post['flowSpeed'];
            $optimalDuctDimension = DuctApd::getOptimalDimensions($shape, $flowRate, $secondsize, $idealFlowSpeed);
        } else {
            $optimalDuctDimension = DuctApd::getOptimalDimensions($shape, $flowRate, $secondsize);
        }

        
        return $this->json([
            $optimalDuctDimension
        ], 200);
    }

    #[Route('/ductsection', name:'app_apd_getDuctSection', methods:['POST'])]
    public function ductSection(Request $request, DuctApd $ductApd): JsonResponse
    {
        $post = json_decode($request->getContent(), true);
        $shape = $post['shape'];

        if($shape === 'circular') {
            $firstSize = $post['diameter'];
            $secondsize = 0;
        } elseif ($shape === 'rectangular') {
            $firstSize = $post['width'];
            $secondsize = $post['height'];
        }
        $section = DuctApd::getSection($shape, $firstSize, $secondsize);

        return $this->json([
            $section
        ], 200);
    }

    #[Route('/flowspeed', name:'app_apd_getFlowSpeed', methods:['POST'])]
    public function flowSpeed(Request $request, DuctApd $ductApd): JsonResponse
    {
        $post = json_decode($request->getContent(), true);
        $flowRate = $post['flowRate'];
        $shape = $post['shape'];

        if($shape === 'circular') {
            $firstSize = $post['diameter'];
            $secondsize = 0;
        } elseif ($shape === 'rectangular') {
            $firstSize = $post['width'];
            $secondsize = $post['height'];
        }
        $flowSpeed = DuctApd::getFlowSpeed($flowRate, $shape, $firstSize, $secondsize);

        return $this->json([
            $flowSpeed
        ], 200);
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
    public function setSection(Request $request, DuctApd $ductApd): JsonResponse
    {
        $post = json_decode($request->getContent(), true);
        $shape = $post['shape'];
        $material = $post['material'];
        if($shape === 'circular') {
            $firstSize = $post['diameter'];
            $secondsize = 0;
        } elseif ($shape === 'rectangular') {
            $firstSize = $post['width'];
            $secondsize = $post['height'];
        }
        $flowRate = $post['flowRate'];
        $length = $post['length'];
        $singularities = $post['singularities'];
        $additionalApd = $post['additionalApd'];

        $ductApd->globalSetter($shape, $material, $firstSize, $secondsize, $flowRate, $length, $singularities, $additionalApd);
        
        if(array_key_exists('temperature', $post)) {
            $temperature = $post['temperature'];
            $ductApd->air->setTemperature($temperature);
        }
        if(array_key_exists('altitude', $post)) {
            $altitude = $post['altitude'];
            $ductApd->air->setAltitude($altitude);
        }

        $response = [
            'ductSection' => $ductApd->section,
            'flowSpeed' => $ductApd->flowSpeed,
            'linearApd' => $ductApd->getLinearApd(),
            'singularApd' => $ductApd->getSingularApd(),
            'totalApd' => $ductApd->getTotalApd()
        ];

        return $this->json($response, 200);
    }

    #[Route('/sections', name:'app_apd_setSections', methods:['POST'])]
    public function setSections(Request $request, DuctApd $ductApd)
    {
        $post = json_decode($request->getContent(), true);
        $generalAdditionalApd = $post['additionalApd'];
        $sections = $post['sections'];

        $totalLinearApd = 0;
        $totalSingularApd = 0;
        $totalAdditionalApd = 0;

        if(array_key_exists('temperature', $post)) {
            $temperature = $post['temperature'];
            $ductApd->air->setTemperature($temperature);
        }
        if(array_key_exists('altitude', $post)) {
            $altitude = $post['altitude'];
            $ductApd->air->setAltitude($altitude);
        }

        foreach($sections as $section) {
            if($section['shape'] === 'circular') {
                $firstSize = $section['diameter'];
                $secondsize = 0;
            } elseif($section['shape'] === 'rectangular') {
                $firstSize = $section['width'];
                $secondsize = $section['height'];
            }
            $ductApd->globalSetter(
                $section['shape'],
                $section['material'],
                $firstSize,
                $secondsize,
                $section['flowRate'],
                $section['length'],
                $section['singularities'],
                $section['additionalApd']
            );
            $totalLinearApd += $ductApd->getLinearApd();
            $totalSingularApd += $ductApd->getSingularApd();
            $totalAdditionalApd += $ductApd->getAdditionalApd();
        }

        $totalSingularApd = round($totalSingularApd, 3);
        $totalApd = $totalLinearApd + $totalSingularApd + $totalAdditionalApd + $generalAdditionalApd;

        $response = [
            'totalLinearApd' => $totalLinearApd,
            'totalSingularApd' => $totalSingularApd,
            'totalAdditionalApd' => $totalAdditionalApd,
            'generalAdditionalApd' => $generalAdditionalApd,
            'totalApd' => $totalApd
        ];
        
        return $this->json($response, 200);
    }
}
