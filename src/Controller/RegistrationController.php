<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher ;
    }

    #[Route('/registerPatient', name: 'app_registration_patient')]
    public function registerPatient(Request $request,ManagerRegistry $rm,ValidatorInterface $validator)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_PATIENT']);
            $user->setDateDeCreation(new \DateTime());
            // Save
            $em =$rm->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');

        }
        return $this->render('Front-Office/registration/register-patient.html.twig', [

            'form' => $form->createView()
        ]);
    }

    #[Route('/registerMedecin', name: 'app_registration_doctor')]
    public function registerDoctor(Request $request,ManagerRegistry $rm,ValidatorInterface $validator)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_MEDECIN']);
            $user->setDateDeCreation(new \DateTime());
            // Save
            $em =$rm->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');

        }
        return $this->render('Front-Office/registration/register-doctor.html.twig', [

            'form' => $form->createView()
        ]);
    }

    #[Route('/registerPharmacien', name: 'app_registration_pharmacien')]
    public function registerPharmacien(Request $request,ManagerRegistry $rm,ValidatorInterface $validator)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_PHARMACIEN']);
            $user->setDateDeCreation(new \DateTime());
            // Save
            $em =$rm->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');

        }
        return $this->render('Front-Office/registration/register-pharmacien.html.twig', [

            'form' => $form->createView()
        ]);
    }


    #[Route('/registerAssureur', name: 'app_registration_assureur')]
    public function registerAssureur(Request $request,ManagerRegistry $rm,ValidatorInterface $validator)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_ASSUREUR']);
            $user->setDateDeCreation(new \DateTime());
            // Save
            $em =$rm->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');

        }
        return $this->render('Front-Office/registration/register-assureur.html.twig', [

            'form' => $form->createView()
        ]);
    }



}
