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
    #[Route('/home', name: 'app_profile')]
    public function home(): Response
    {
        $user=$this->getUser();
        return $this->render('Front-Office/profile/home-user.html.twig', [
            'controller_name' => 'ProfileController',
            'user'=>$user
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
                $user->setDateDeModification(new \DateTime());
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
                $user->setDateDeModification(new \DateTime());
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
   }

