<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApdController extends AbstractController
{
    #[Route('/test', name: 'app_apd')]
    public function index(Request $request): JsonResponse
    {
        $test = $request->getContent();
        return $this->json(
            $test, 200
        );
    }
}
