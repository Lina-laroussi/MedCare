<?php

namespace App\Controller;

use App\Form\EditFormMedecinType;
use App\Form\EditFormUserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function update_profile_utilisateur( ManagerRegistry $rg, Request $req,  SluggerInterface $slugger): Response
    {
       // $user = $repo->find($id);
        $user=$this->getUser();

        $form = $this->createForm(EditFormUserType::class, $user);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();
                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('utilisateurs_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setImage($newFilename);
            }

            $result = $rg->getManager();
            $result->persist($user);
            $result->flush();
        }

        return $this->render('Front-Office/profile/profile-user.html.twig', [
            'form' => $form->createView(),
            'user'=>$user
        ]);

    }


    #[Route('/profileM', name: 'app_medecin_profile')]
    public function update_profile_medecin(ManagerRegistry $rg, Request $req, SluggerInterface $slugger): Response
    {
        //$user = $repo->find($id);
        $user=$this->getUser();
        $form = $this->createForm(EditFormMedecinType::class, $user);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();
                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('utilisateurs_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setImage($newFilename);
            }

            $result = $rg->getManager();
            $result->persist($user);
            $result->flush();

        }

        return $this->render('Front-Office/profile/profile-medecin.html.twig', [
            'form' => $form->createView(),
            'user'=>$user
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
