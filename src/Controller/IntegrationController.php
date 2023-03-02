<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Mime;
use App\Service\MailerService;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Attachment;

use App\Entity\Pharmacie;
use App\Form\PharmacieType;
use App\Repository\PharmacieRepository;


class IntegrationController extends AbstractController
{
    #[Route('/integration', name: 'app_integration')]
    public function index(): Response
    {
        return $this->render('Front-Office/Landing.html.twig', [
            'controller_name' => 'IntegrationController',
        ]);
    }
    
    #[Route('/detail', name: 'app_integration10')]
    public function detail(): Response
    {
        return $this->render('Front-Office/pharmacy-details1.html.twig', [
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
    public function searchpharmacie(PharmacieRepository $pharmacieRepository): Response
    {
        return $this->render('Front-Office/pharmacy-search.html.twig', [
            'controller_name' => 'IntegrationController',
            'pharmacies' => $pharmacieRepository->findAll(),

        ]);
    }

    #[Route('/searchpharmacie/{id}', name: 'app_integration4', methods: ['GET'])]
    public function  detailspharmacie(Pharmacie $pharmacie): Response
    {
        return $this->render('Front-Office/pharmacy-details.html.twig', [
            'pharmacie' => $pharmacie,
            'controller_name' => 'IntegrationController',

        ]);
    }
    #[Route('/send', name: 'app_send')]
    public function sendEmail(MailerService $mailer )
    {   $mailer->sendEmail(from:'pharmaciemedcare@gmail.com',to:'feryelouerfelli@gmail.com' , content:'votre facture',subject: 'Facture Pharmacie', tmpFile:'document.pdf');
        return new Response("Success");
    }
    
}
