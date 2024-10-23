<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $data = [
            'test 1',
            'test 2',
            'test 3',
        ];

        return $this->render('home.html.twig', [
            'data' => $data
        ]);
    }
}
