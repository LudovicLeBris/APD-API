<?php

namespace App\Controller;

use App\Repository\DiameterRepository;
use App\Repository\MaterialRepository;
use App\Repository\SingularityRepository;
use App\Service\Apd\DuctApd;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(DiameterRepository $diameterRepository, MaterialRepository $materialRepository, SingularityRepository $singularityRepository): JsonResponse
    {
        $duct1Singularities = [
            "90_elbow" => 2,
        ];
        $duct2Singularities = [
            "90_sep_tee" => 1,
            "90_sharp_elbow" => 2,
            "45_sharp_elbow" => 4
        ];

        
        $optimalDimension = DuctApd::getOptimalDimensions($diameterRepository, 'circular', 2000);
        
        $ductSection1 = new DuctApd('circular', 'galvanised steel', 250, 0 , 1000, 1, $duct1Singularities, 50);
        $sectionLinearApd1 = $ductSection1->getLinearApd($materialRepository);
        $sectionSingularApd1 = $ductSection1->getSingularApd($singularityRepository);


        return $this->json([
            $optimalDimension,
            $sectionLinearApd1,
            $sectionSingularApd1
        ]);
    }
}
