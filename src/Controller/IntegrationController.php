<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IntegrationController extends AbstractController
{
    #[Route('/integration', name: 'app_integration')]
    public function index(): Response
    {
        return $this->render('Front-Office/Landing.html.twig', [
            'controller_name' => 'IntegrationController',
        ]);
    }

    #[Route('/admin', name: 'app_integration2')]
    public function admin(): Response
    {
        return $this->render('Back-Office/DashboardAdmin.html.twig', [
            'controller_name' => 'IntegrationController',
        ]);
    }
    #[Route('/searchpharmacie', name: 'app_integration3')]
    public function searchpharmacie(): Response
    {
        return $this->render('Front-Office/pharmacy-search.html.twig', [
            'controller_name' => 'IntegrationController',
        ]);
    }
    #[Route('/detailpharmacie', name: 'app_integration4')]
    public function detailspharmacie(): Response
    {
        return $this->render('Front-Office/pharmacy-details.html.twig', [
            'controller_name' => 'IntegrationController',
        ]);
    }
}
