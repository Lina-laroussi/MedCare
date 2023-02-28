<?php

namespace App\Controller;

use App\Form\EditFormUserType;
use App\Form\ForgotPasswordFormType;
use App\Form\ResetPasswordFormType;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/reset', name: 'app_send')]
    public function sendEmail(Request $request)
    {
        $form = $this->createForm(ResetPasswordFormType::class);

        $form->handleRequest($request);
        return $this->render('Front-Office/security/password-reset.html.twig', [
            'controller_name' => 'IntegrationController',
            'form'=>$form->createView()
        ]);
    }

}
