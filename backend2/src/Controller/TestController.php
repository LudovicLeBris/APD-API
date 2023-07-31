<?php

namespace App\Controller;

use App\Service\Apd\DuctApd;
use App\Repository\DiameterRepository;
use App\Repository\MaterialRepository;
use App\Repository\SingularityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(DiameterRepository $diameterRepository, MaterialRepository $materialRepository, SingularityRepository $singularityRepository): JsonResponse
    {
        // données test
        $duct1Singularities = [
            "90_elbow" => 2,
        ];
        $duct2Singularities = [
            "90_sep_tee" => 1,
            "90_sharp_elbow" => 2,
            "45_sharp_elbow" => 4
        ];

        
        // test des méthodes statiques
        $optimalDimension = DuctApd::getOptimalDimensions($diameterRepository, 'circular', 2000);
        $section = DuctApd::getSection('circular', 250);
        $flowspeed = DuctApd::getFlowSpeed(1500, 'circular', 250);
        
        // test d'instanciation d'un objet ductApd
        $ductSection1 = new DuctApd('circular', 'galvanised steel', 250, 0 , 1000, 1, $duct1Singularities, 50);
        $ductSection1->air->setAltitude(2000);
        $sectionLinearApd1 = $ductSection1->getLinearApd($materialRepository);
        $sectionSingularApd1 = $ductSection1->getSingularApd($singularityRepository);


        return $this->json([
            $optimalDimension,
            $section,
            $flowspeed,
            $sectionLinearApd1,
            $sectionSingularApd1
        ]);
    }

    #[Route('/test2', name: 'app_test2', methods: ['POST'])]
    public function testWithRequest(Request $request, DiameterRepository $diameterRepository, MaterialRepository $materialRepository, SingularityRepository $singularityRepository): JsonResponse
    {       
        $post = json_decode($request->getContent(), true);
        $shape = $post['shape'];
        $material = $post['material'];
        $diameter = $post['diameter'];
        $flowRate = $post['flowRate'];
        $lenght = $post['length'];
        $singularities = $post['singularities'];
        $additionalApd = $post['additionalApd'];

        $ductSection1 = new DuctApd($shape, $material, $diameter, 0 , $flowRate, $lenght, $singularities, $additionalApd);
        // $sectionLinearApd1 = $ductSection1->getLinearApd($materialRepository);
        // $sectionSingularApd1 = $ductSection1->getSingularApd($singularityRepository);
        $sectionTotalApd1 = $ductSection1->getTotalApd($materialRepository, $singularityRepository);

        return $this->json([
            // $sectionLinearApd1,
            // $sectionSingularApd1,
            $sectionTotalApd1
        ]);
        
    }
}
