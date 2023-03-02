<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditFormMedecinType;
use App\Form\EditFormUserType;
use App\Form\SearchFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


#[
    Route('admin'),
    IsGranted ('IS_AUTHENTICATED_FULLY')
]
class UtilisateurController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function admin(): Response
    {
        $currentuser = $this->getUser();

        return $this->render('Back-Office/DashboardAdmin.html.twig', [
            'controller_name' => 'UtilisateurController',
            'user'=>$currentuser
        ]);
    }

    #[Route('/newUser', name: 'add_utilisateur')]
    public function add_utilisateur(ManagerRegistry $rg, Request $req):Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $result = $rg->getManager();
            $result->persist($user);
            $result->flush();
        }

        return $this->render('utilisateur/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/validate/{id}', name: 'validate_utilisateur')]
    public function validate_utilisateur($id,ManagerRegistry $rg, Request $req,UserRepository $repo,MailerService $mailer):Response
    {
        $user = $repo->find($id);
        $user->setEtat("valide");
        $result = $rg->getManager();
        $result->persist($user);
        $result->flush();

        $context = ['user' =>$user];

        $mailer->sendEmail(
            to: $user->getEmail(),
            template: 'confirmation-validation',
            subject: ' Validation Compte',
            context: $context
        );

        if (in_array('ROLE_MEDECIN', $user->getRoles(), true)) {
            return $this->redirectToRoute('list_medecins');
        } elseif (in_array('ROLE_ASSUREUR', $user->getRoles(), true)){
            return $this->redirectToRoute('list_assureurs');
        }elseif (in_array('ROLE_PHARMACIEN', $user->getRoles(), true)){
            return $this->redirectToRoute('list_pharmaciens');
        }else {
            return $this->redirectToRoute('list_patients');
        }
    }


    #[Route('/updateUser/{id}', name: 'update_utilisateur')]
    public function update_utilisateur($id, ManagerRegistry $rg, Request $req, UserRepository $repo, SluggerInterface $slugger): Response
    {
        $user = $repo->find($id);
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

        return $this->render('Back-Office/Profile-User.html.twig', [
            'form' => $form->createView(),
            'user'=>$user
        ]);

    }

    #[Route('/updateMed/{id}', name: 'update_medecin')]
    public function update_medecin($id, ManagerRegistry $rg, Request $req, UserRepository $repo, SluggerInterface $slugger): Response
    {
        $user = $repo->find($id);

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

        return $this->render('Back-Office/Profile-Medecin.html.twig', [
            'form' => $form->createView(),
            'user'=>$user
        ]);
    }


    #[Route('/removeUser/{id}', name: 'remove_utilisateur')]
    public function remove_utilisateur($id, ManagerRegistry $rg, UserRepository $repo,Request $req): Response
    {
        $user = $repo->find($id);
        $result = $rg->getManager();
        $result->remove($user);
        $result->flush();

        if (in_array('ROLE_MEDECIN', $user->getRoles(), true)) {
            return $this->redirectToRoute('list_medecins');
        } elseif (in_array('ROLE_ASSUREUR', $user->getRoles(), true)){
            return $this->redirectToRoute('list_assureurs');
        }elseif (in_array('ROLE_PHARMACIEN', $user->getRoles(), true)){
            return $this->redirectToRoute('list_pharmaciens');
        }else {
            return $this->redirectToRoute('list_patients');
        }
    }

    #[Route('/listMedecins', name: 'list_medecins')]
    public function list_medecin(UserRepository $repo,Request $req)
    {
        $users = $repo->findAll();
        //$count=$repo->findUsersByRole('ROLE_MEDECIN');
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() ) {
            $searchTerm = $form->getData();
            $users = $repo->findUsersBySearchTerm($searchTerm);
        }
        return $this->render('Back-Office/list-medecins.html.twig', [
            'users' => $users,
            'form'=>$form->createView(),
        ]);
    }

    #[Route('/listAssureurs', name: 'list_assureurs')]
    public function list_assureur(UserRepository $repo,Request $req): Response
    {
        $users = $repo->findAll();
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() ) {
            $searchTerm = $form->getData();
            $users = $repo->findUsersBySearchTerm($searchTerm);
        }
        return $this->render('Back-Office/list-assureurs.html.twig', [
            'users' => $users,
            'form'=>$form->createView(),
        ]);
    }

    #[Route('/listPatients', name: 'list_patients')]
    public function list_patient(UserRepository $repo,Request $req): Response
    {
        $users = $repo->findAll();
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() ) {
            $searchTerm = $form->getData();
            $users = $repo->findUsersBySearchTerm($searchTerm);
        }

        return $this->render('Back-Office/list-patients.html.twig', [
            'users' => $users,
            'form'=>$form->createView(),
        ]);
    }

    #[Route('/listPharmaciens', name: 'list_pharmaciens')]
    public function list_pharmacien(UserRepository $repo,Request $req): Response
    {
        $users = $repo->findAll();
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() ) {
            $searchTerm = $form->getData();
            $users = $repo->findUsersBySearchTerm($searchTerm);
        }

        return $this->render('Back-Office/list-pharmaciens.html.twig', [
            'users' => $users,
            'form'=>$form->createView(),
        ]);
    }
}
