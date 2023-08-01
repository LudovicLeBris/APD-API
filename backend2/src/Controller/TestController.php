<?php

namespace App\Controller;

use App\Service\Apd\DuctApd;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/api")]
class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(DuctApd $ductApd): JsonResponse
    {
        // données test
        $duct1Singularities = [
            "90_elbow" => 2,
            "90_sep_tee" => 1
        ];
        $duct2Singularities = [
            "90_sep_tee" => 1,
            "90_sharp_elbow" => 2,
            "45_sharp_elbow" => 4
        ];

        
        // test des méthodes statiques
        $section = DuctApd::getSection('circular', 250);
        $flowspeed = DuctApd::getFlowSpeed(1500, 'circular', 250);
        $optimalDimension = DuctApd::getOptimalDimensions('circular', 1000);
        
        // test d'instanciation d'un objet ductApd
        $ductApd->globalSetter('circular', 'galvanised steel', 355, 0, 2000, 10, $duct1Singularities);
        // $ductSection1->air->setAltitude(2000);
        $sectionLinearApd1 = $ductApd->getLinearApd();
        $sectionSingularApd1 = $ductApd->getSingularApd();


        return $this->json([
            $section,
            $flowspeed,
            $optimalDimension,
            $sectionLinearApd1,
            $sectionSingularApd1
        ]);
    }

    #[Route('/test2', name: 'app_test2', methods: ['POST'])]
    public function testWithRequest(Request $request, DuctApd $ductApd): JsonResponse
    {       
        /* Json body for this test
        {
            "flowRate": 2500,
            "shape": "circular",
            "diameter": 355,
            "material": "galvanised steel",
            "length": 10,
            "singularities": {
                "90_elbow": 3,
                "90_sep_tee": 1
            },
            "additionalApd": 0,
        }
        */

        $post = json_decode($request->getContent(), true);
        $shape = $post['shape'];
        $material = $post['material'];
        $diameter = $post['diameter'];
        $flowRate = $post['flowRate'];
        $lenght = $post['length'];
        $singularities = $post['singularities'];
        $additionalApd = $post['additionalApd'];

        $ductApd->globalSetter($shape, $material, $diameter, 0 , $flowRate, $lenght, $singularities, $additionalApd);
        $sectionLinearApd1 = $ductApd->getLinearApd();
        $sectionSingularApd1 = $ductApd->getSingularApd();
        $sectionTotalApd1 = $ductApd->getTotalApd();

        return $this->json([
            $sectionLinearApd1,
            $sectionSingularApd1,
            $sectionTotalApd1
        ]);
        
    }
}
