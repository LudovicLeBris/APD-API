<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test', methods:['GET'])]
    public function index(): JsonResponse
    {
        return $this->json("test", Response::HTTP_OK);
    }

}
