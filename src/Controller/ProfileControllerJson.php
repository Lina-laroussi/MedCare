<?php

namespace App\Controller;

use App\Form\EditFormMedecinType;
use App\Form\EditFormUserType;
use App\Form\SearchFormType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[
    Route('profileMobile'),
    IsGranted ('IS_AUTHENTICATED_FULLY')
]
class ProfileControllerJson extends AbstractController
{
    #[Route('/p', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/denied', name: 'app_denied')]
    public function denied(): Response
    {
        $user=$this->getUser();
        return $this->render('Front-Office/profile/access_denied.html.twig', [
            'controller_name' => 'ProfileController',
            'user'=>$user
        ]);
    }

    #[Route('/blocked', name: 'app_blocked')]
    public function blocked(): Response
    {
        $user=$this->getUser();
        return $this->render('Front-Office/profile/blocked-user.html.twig', [
            'controller_name' => 'ProfileController',
            'user'=>$user
        ]);
    }


    #[Route('/homeUserJson', name: 'app_user_home_json')]
    public function home(UserRepository $repo,SerializerInterface $serializer): Response
    {
        $user=$this->getUser();
        $email=$user->getUserIdentifier();
        $currentUser = $repo->findOneByEmail($email);
        if($currentUser->getEtat() == "non valide"){
            return $this->redirectToRoute('app_denied');
        }
        if($currentUser->isIsBlocked()){
            return $this->redirectToRoute('app_blocked');
        }

        $jsonHome = $serializer->serialize($user, 'json', ['groups' => "User"]);
        return new JsonResponse($jsonHome,Response::HTTP_OK,[],true);
    }



    #[Route('/ProfileUserJson', name: 'app_profile_json')]
    public function Profile(UserRepository $repo,SerializerInterface $serializer): Response
    {
        $user=$this->getUser();
        $jsonHome = $serializer->serialize($user, 'json', ['groups' => "User"]);
        return new JsonResponse($jsonHome,Response::HTTP_OK,[],true);
    }


    //Modifier Patient
    #[Route('/SettingsJson', name: 'app_user_profile_json')]
    public function update_profile_utilisateur( ManagerRegistry $rg, Request $req,  SluggerInterface $slugger,NormalizerInterface $normalizer): Response
    {
       // $user = $repo->find($id);
        $user=$this->getUser();
        $user->setEmail($req->get('EmailUser'));
        $user->setDateDeNaissance($req->get('datedenaissance'));
        $user->setSexe($req->get('sexe'));
        $user->setNumTel($req->get('numtel'));
        $user->setAdresse($req->get('adresse'));
        $user->setDateDeCreation($req->get('datedecreation'));

        $UserNormalises = $normalizer->normalize($user, 'json', ['groups' => "User"]);
        return new JsonResponse($UserNormalises,Response::HTTP_OK,[],true);
    }



    #[Route('/MSettingsJson', name: 'app_medecin_profile_json')]
    public function update_profile_medecin(ManagerRegistry $rg, Request $req, SluggerInterface $slugger,SerializerInterface $serializer): Response
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

        $userMSettings=[$user,$form];
        $jsonSettings = $serializer->serialize($userMSettings, 'json');

        return new JsonResponse($jsonSettings,Response::HTTP_OK,[],true);
    }


   }

