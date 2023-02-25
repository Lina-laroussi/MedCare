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
        return $this->render('Front-Office/dashboardDoc.html.twig');
    }


    #[Route('/admin/consultation', name: 'admin_consultation')]
    public function consultation(): Response
    {
        return $this->render('Back-Office/dashboardAdmin.html.twig');
    }
    #[Route('/admin/ordonnance', name: 'admin_ordonnance')]
    public function ordonnance(): Response
    {
        return $this->render('Back-Office/ordonnance.html.twig');
    }
    #[Route('/admin/ficheMedicale', name: 'admin_fiche_medicale')]
    public function ficheMedicale(): Response
    {
        return $this->render('Back-Office/fichMedicale.html.twig');
    }

    
    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('Back-Office/login.html.twig');
    }




}
