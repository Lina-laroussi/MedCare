<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IntegrationController extends AbstractController
{
    #[Route('/', name: 'Home_Page')]
    public function home(): Response
    {
        return $this->render('Front-Office/Landing.html.twig');
    }

    #[Route('/dashboard', name: 'app_consult')]
    public function dashboard(): Response
    {
        return $this->render('Back-Office/dashboardDoc.html.twig');
    }

    #[Route('/consultation', name: 'app_consultation')]
    public function consultation(): Response
    {
        return $this->render('Front-Office/consultation.html.twig');

    }

    #[Route('/fichemed', name: 'app_fiche_med')]
    public function fich(): Response
    {
        return $this->render('Front-Office/fichemed.html.twig');
    }

    #[Route('/ordenance', name: 'app_ordenance')]
    public function ordenance(): Response
    {
        return $this->render('Front-Office/ordenance.html.twig');
    }






}
