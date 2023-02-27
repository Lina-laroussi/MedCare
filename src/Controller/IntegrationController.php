<?php

namespace App\Controller;

use App\Form\EditFormUserType;
use App\Form\ForgotPasswordFormType;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IntegrationController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('Front-Office/Landing.html.twig', [
            'controller_name' => 'IntegrationController',
        ]);
    }


    #[Route('/connect', name: 'app_choose_profile')]
    public function profile(): Response
    {
        return $this->render('Front-Office/choose_profile.html.twig', [
            'controller_name' => 'IntegrationController',
        ]);
    }

    #[Route('/send', name: 'app_send')]
    public function sendEmail(MailerService $mailer)
    {
        $mailer->sendEmail(content: 'votre mail est envoyé');
        return new Response("votre mail été envoyé");
    }

}
