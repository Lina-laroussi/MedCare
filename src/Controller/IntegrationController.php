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
        return $this->render('Front-Office/Landing.html.twig');
    }

    #[Route('/admin', name: 'app_integration2')]
    public function admin(): Response
    {
        return $this->render('Back-Office/DashboardAdmin.html.twig');
    }

    #[Route('/consultation', name: 'app_consult')]
    public function dashboard(): Response
    {
        return $this->render('Front-Office/consultation.html.twig');
    }
}
