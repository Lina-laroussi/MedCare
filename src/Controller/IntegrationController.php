<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Repository\ConsultationRepository;
use App\Repository\OrdonnanceRepository;
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

//--------dashboard / stat:  number of medication per day = added per day in January-------------
    #[Route('/dashboard', name: 'app_consult')]
    public function dashboard(ConsultationRepository $consultationRepository, OrdonnanceRepository $ordonnanceRepository): Response
    { 

        $totalConsultations = $consultationRepository->getTotalConsultations();
        $totalRevenus = $consultationRepository ->getTotalRevenus();
        $totalMedicaments = $ordonnanceRepository ->getTotalMedicament();
        return $this->render('Front-Office/dashboardDoc.html.twig', [
            'totalConsultations' => $totalConsultations,
            'TotalRevenus' => $totalRevenus,
            'TotalMedicament' => $totalMedicaments,
        ]
        );
    
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
