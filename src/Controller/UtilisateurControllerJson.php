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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


#[
    Route('Mobile')
]
class UtilisateurControllerJson extends AbstractController
{
    #[Route('/', name: 'app_admin_json')]
    public function admin(UserRepository $repo): Response
    {
        $currentuser = $this->getUser();
        $nbMedecins = $repo->countUsersByRole('ROLE_MEDECIN');
        $nbPatients = $repo->countUsersByRole('ROLE_PATIENT');
        $nbPharmaciens = $repo->countUsersByRole('ROLE_PHARMACIEN');
        $nbAssureurs = $repo->countUsersByRole('ROLE_ASSUREUR');

        return $this->render('Back-Office/DashboardAdmin.html.twig', [
            'controller_name' => 'UtilisateurController',
            'user'=>$currentuser,
            'nbM'=>$nbMedecins,
            'nbP'=>$nbPatients,
            'nbPh'=>$nbPharmaciens,
            'nbA'=>$nbAssureurs
        ]);
    }



    #[Route('/validate/{id}', name: 'validate_utilisateur_json')]
    public function validate_utilisateur($id,ManagerRegistry $rg, Request $req,UserRepository $repo,MailerService $mailer,SerializerInterface $serializer):Response
    {
        $user = $repo->find($id);
        $user->setEtat("valide");
        $result = $rg->getManager();
        $result->persist($user);
        $result->flush();

        $jsonbloque = $serializer->serialize($user, 'json');
        return new JsonResponse($jsonbloque,Response::HTTP_OK,[],true);
    }

    #[Route('/bloquer/{id}', name: 'bloquer_utilisateur_json')]
    public function bloquer_utilisateur($id,ManagerRegistry $rg, Request $req,UserRepository $repo,MailerService $mailer,SerializerInterface $serializer):Response
    {
        $user = $repo->find($id);
        $user->setIsBlocked(true);
        $result = $rg->getManager();
        $result->persist($user);
        $result->flush();

        $jsonbloque = $serializer->serialize($user, 'json',['groups' => "User"]);
        return new JsonResponse($jsonbloque,Response::HTTP_OK,[],true);
    }


    #[Route('/debloquer/{id}', name: 'debloquer_utilisateur_json')]
    public function debloquer_utilisateur($id,ManagerRegistry $rg, Request $req,UserRepository $repo,MailerService $mailer,SerializerInterface $serializer):Response
    {
        $user = $repo->find($id);
        $user->setIsBlocked(false);
        $result = $rg->getManager();
        $result->persist($user);
        $result->flush();

        $jsonDebloque = $serializer->serialize($user, 'json',['groups' => "User"]);
        return new JsonResponse($jsonDebloque,Response::HTTP_OK,[],true);
    }

    #[Route('/updateUser/{id}', name: 'update_utilisateur_json')]
    public function update_utilisateur($id, ManagerRegistry $rg, Request $req, UserRepository $repo, SluggerInterface $slugger,NormalizerInterface $normalizer): Response
    {
        $user = $repo->find($id);
        $user->setEmail($req->get('email'));
        $user->setDateDeNaissance($req->get('datedenaissance'));
        $user->setSexe($req->get('sexe'));
        $user->setNumTel($req->get('numtel'));
        $user->setAdresse($req->get('adresse'));
        $result = $rg->getManager();
        $result->persist($user);
        $result->flush();

        $UserNormalises = $normalizer->normalize($user, 'json', ['groups' => "User"]);
        return new JsonResponse($UserNormalises,Response::HTTP_OK,[],true);
    }

    #[Route('/updateMed/{id}', name: 'update_medecin_json')]
    public function update_medecin($id, ManagerRegistry $rg, Request $req, UserRepository $repo, SluggerInterface $slugger,NormalizerInterface $normalizer): Response
    {
        $user = $repo->find($id);
        $user->setEmail($req->get('EmailUser'));
        $user->setDateDeNaissance($req->get('datedenaissance'));
        $user->setSexe($req->get('sexe'));
        $user->setNumTel($req->get('numtel'));
        $user->setAdresse($req->get('adresse'));
        $user->setDateDeCreation($req->get('datedecreation'));
        $result = $rg->getManager();
        $result->persist($user);
        $result->flush();

        $UserNormalises = $normalizer->normalize($user, 'json', ['groups' => "User"]);
        return new JsonResponse($UserNormalises,Response::HTTP_OK,[],true);
    }


    #[Route('/removeUserJson', name: 'remove_utilisateur_json')]
    public function remove_utilisateur(ManagerRegistry $rg, UserRepository $repo,Request $req,NormalizerInterface $normalizer): Response
    {
        $id = $req->get('id');
        $user = $repo->find($id);
        $result = $rg->getManager();
        $result->remove($user);
        $result->flush();

        $jsonContent = $normalizer->normalize($result, 'json', ['groups' => "User"]);
        return new JsonResponse($result,Response::HTTP_OK,[],true);
    }

    #[Route('/listMedecins/{page?1}/{nbre?5}', name: 'list_medecins_json')]
    public function list_medecin(UserRepository $repo,Request $req,$nbre,$page,SerializerInterface $serializer):Response
    {
        $nbMedecins = $repo->countUsersByRole('ROLE_MEDECIN');
        $nbrePage = ceil($nbMedecins / $nbre) ;
        $users = $repo->findUsersByRole($page,$nbre,'ROLE_MEDECIN');

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() ) {
            $searchTerm = $form->getData();
            $users = $repo->findUsersBySearchTerm($searchTerm);
        }
        $jsonListM = $serializer->serialize($users, 'json');
        return new JsonResponse($jsonListM,Response::HTTP_OK,[],true);

    }

    #[Route('/listAssureurs/{page?1}/{nbre?5}', name: 'list_assureurs_json')]
    public function list_assureur(UserRepository $repo,Request $req,$page,$nbre,SerializerInterface $serializer):Response
    {
        $nbAssureurs = $repo->countUsersByRole('ROLE_ASSUREUR');
        $nbrePage = ceil($nbAssureurs / $nbre) ;
        $users = $repo->findUsersByRole($page,$nbre,'ROLE_ASSUREUR');

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() ) {
            $searchTerm = $form->getData();
            $users = $repo->findUsersBySearchTerm($searchTerm);
        }
        $jsonListA = $serializer->serialize($users, 'json');
        return new JsonResponse($jsonListA,Response::HTTP_OK,[],true);
    }

    #[Route('/listPatientsJson ', name: 'list_patients_json')]
    public function list_patient(UserRepository $repo,Request $req,SerializerInterface $serializer):Response
    {

        $users = $repo->findPatients('ROLE_PATIENT');

        $jsonListPa = $serializer->serialize($users, 'json',['groups' => "User"]);
        return new JsonResponse($jsonListPa,Response::HTTP_OK,[],true,);
    }

    #[Route('/listPharmaciens/{page?1}/{nbre?5}', name: 'list_pharmaciens_json')]
    public function list_pharmacien(UserRepository $repo,Request $req,$nbre,$page,SerializerInterface $serializer): Response
    {
        $nbPharmaciens = $repo->countUsersByRole('ROLE_PHARMACIEN');
        $nbrePage = ceil($nbPharmaciens / $nbre) ;
        $users = $repo->findUsersByRole($page,$nbre,'ROLE_PHARMACIEN');

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() ) {
            $searchTerm = $form->getData();
            $users = $repo->findUsersBySearchTerm($searchTerm);
        }

        $jsonListPh = $serializer->serialize($users, 'json');
        return new JsonResponse($jsonListPh,Response::HTTP_OK,[],true);
    }
}
