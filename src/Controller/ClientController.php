<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientController extends AbstractController
{
    #[Route('/client/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('client/index-1.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }
}
