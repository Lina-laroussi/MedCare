<?php

namespace App\Controller;

use App\Form\EditFormMedecinType;
use App\Form\EditFormUserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/p', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }


    #[Route('/profile', name: 'app_user_profile')]
    public function profile(Request $request,ManagerRegistry $rg): Response
    {
        $currentuser = $this->getUser();
        $form = $this->createForm(EditFormUserType::class, $currentuser);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em=$rg->getManager();
            $em->persist($form);
            $em->flush();
            //  $userRepository->save($currentuser, true);

            return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Front-Office/profile/profile-user.html.twig', [
            'controller_name' => 'ProfileController',
            'user'=>$currentuser,
            'form'=>$form->createView()
        ]);
    }




   /* #[Route('/profile', name: 'app_user_profile', methods: ['GET','POST'])]
    public function edit(UserRepository $userRepository,Request $request,ManagerRegistry $rg):Response
    {
        $currentuser = $this->getUser();
        if(in_array('ROLE_MEDECIN',$currentuser->getRoles(),true)) {

            $form = $this->createForm(EditFormMedecinType::class, $currentuser);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $em=$rg->getManager();
                $em->persist($form);
                $em->flush();
               //  $userRepository->save($currentuser, true);

                return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('Front-Office/profile/profile-medecin.html.twig', [
                'user' => $currentuser,
                'form'=> $form->createView()
            ]);

        } else{

            $form = $this->createForm(EditFormUserType::class, $currentuser);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid() ) {

                $em=$rg->getManager();
                $em->persist($form);
                $em->flush();

                //$userRepository->save($currentuser, true, []);

                return $this->redirectToRoute('app_user_profile', Response::HTTP_SEE_OTHER);
            }
            return $this->render('Front-Office/profile/profile-user.html.twig', [
                'user' => $currentuser,
                'form'=> $form->createView()

            ]);
        }}*/
   }

