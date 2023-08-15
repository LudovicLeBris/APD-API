<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route('/', name: 'app_home')]
    public function home(Request $request): Response
    {
        return new Response("<html><body><h1>test</h1><div>". $request->getContent() ."</div></body></html>");
    }
}
