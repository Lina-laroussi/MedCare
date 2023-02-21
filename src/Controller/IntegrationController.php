<?php

namespace App\Controller;

use App\Form\EditFormUserType;
use App\Form\ForgotPasswordFormType;
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

    #[Route('/admin', name: 'app_admin')]
    public function admin(): Response
    {
        $currentuser = $this->getUser();

        return $this->render('Back-Office/dashboardAdmin.html.twig', [
            'controller_name' => 'IntegrationController',
            'user'=>$currentuser
        ]);
    }

    #[Route('/forgot', name: 'app_integration2')]
    public function forgot(): Response
    {
        $form = $this->createForm(ForgotPasswordFormType::class );
        return $this->render('Front-Office/forgot-password.html.twig', [
            'controller_name' => 'IntegrationController',
            'form'=>$form->createView()
        ]);
    }


}
