<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\EditFormMedecinType;
use App\Form\EditFormUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationControllerJson extends AbstractController
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher ;
    }

    //ADD Utilisateur
    #[Route('/registerPatientJson', name: 'app_registration_patient_json')]
    public function registerPatient(Request $request,ManagerRegistry $rm,MailerService $mailer,NormalizerInterface $normalizer)
    {

            $user = new User();
            // Encode the new users password
            // Set their role
            $user->setNom($request->get('nom'));
            $user->setPrenom($request->get('prenom'));
            $user->setEmail($request->get('email'));
            //$user->setPassword("Selim123456?");
            $password = $request->get('password');
            $user->setPassword($this->userPasswordHasher->hashPassword($user,$password));

            $user->setRoles(['ROLE_PATIENT']);
            $user->setDateDeCreation(new \DateTime());
            $user->setEtat("non valide");
            // Save
            $em =$rm->getManager();
            $em->persist($user);
            $em->flush();

        $UserNormalises = $normalizer->normalize($user, 'json', ['groups' => "User"]);
        return new JsonResponse($UserNormalises,Response::HTTP_OK,[],true);
    }

    #[Route('/registerMedecinJson', name: 'app_registration_doctor_json')]
    public function registerDoctor(Request $request,ManagerRegistry $rm,MailerService $mailer,NormalizerInterface $normalizer)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_user_home');
        }

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_MEDECIN']);
            $user->setDateDeCreation(new \DateTime());
            $user->setEtat("non valide");
            // Save
            $em =$rm->getManager();
            $em->persist($user);
            $em->flush();
            $context = ['user' =>$user];

            $mailer->sendEmail(
                to: $user->getEmail(),
                template: 'confirmation-register',
                subject: ' Confirmation de création de compte',
                context: $context
            );
            $mailer->sendEmail(
                to: "admin20@gmail.com",
                template: 'confirmation-register-admin',
                subject: ' Nouveau compte utilisateur créé',
                context: $context
            );
            return $this->redirectToRoute('app_login');

        }
        $UserNormalises = $normalizer->normalize($user, 'json', ['groups' => "User"]);
        return new JsonResponse($UserNormalises,Response::HTTP_OK,[],true);
    }

    #[Route('/registerPharmacienJson', name: 'app_registration_pharmacien_json')]
    public function registerPharmacien(Request $request,ManagerRegistry $rm,MailerService $mailer,NormalizerInterface $normalizer)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_user_home');
        }

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_PHARMACIEN']);
            $user->setDateDeCreation(new \DateTime());
            $user->setEtat("non valide");
            // Save
            $em =$rm->getManager();
            $em->persist($user);
            $em->flush();
            $context = ['user' =>$user];

            $mailer->sendEmail(
                to: $user->getEmail(),
                template: 'confirmation-register',
                subject: ' Confirmation de création de compte',
                context: $context
            );
            $mailer->sendEmail(
                to: "admin20@gmail.com",
                template: 'confirmation-register-admin',
                subject: ' Nouveau compte utilisateur créé',
                context: $context
            );
            return $this->redirectToRoute('app_login');

        }
        $UserNormalises = $normalizer->normalize($user, 'json', ['groups' => "User"]);
        return new JsonResponse($UserNormalises,Response::HTTP_OK,[],true);
    }


    #[Route('/registerAssureurJson', name: 'app_registration_assureur_json')]
    public function registerAssureur(Request $request,ManagerRegistry $rm,MailerService $mailer,NormalizerInterface $normalizer)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_user_home');
        }

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_ASSUREUR']);
            $user->setDateDeCreation(new \DateTime());
            $user->setEtat("non valide");
            // Save
            $em =$rm->getManager();
            $em->persist($user);
            $em->flush();
            $context = ['user' =>$user];

            $mailer->sendEmail(
                to: $user->getEmail(),
                template: 'confirmation-register',
                subject: ' Confirmation de création de compte',
                context: $context
            );
            $mailer->sendEmail(
                to: "admin20@gmail.com",
                template: 'confirmation-register-admin',
                subject: ' Nouveau compte utilisateur créé',
                context: $context
            );
            return $this->redirectToRoute('app_login');
            
        }
        $UserNormalises = $normalizer->normalize($user, 'json', ['groups' => "User"]);
        return new JsonResponse($UserNormalises,Response::HTTP_OK,[],true);
    }

    #[Route('/registerAdmin', name: 'app_registration_admin_json')]
    public function registerAdmin(Request $request,ManagerRegistry $rm)
    {
            $user = new User();
            $user->setEmail("admin20@gmail.com");
            // Encode the new users password
            $user->setPassword($this->userPasswordHasher->hashPassword($user,"admin"));
            $user->setNom("admin");
            $user->setPrenom("admin");
            // Set their role
            $user->setRoles(['ROLE_ADMIN']);

            $user->setDateDeCreation(new \DateTime());
            // Save
            $em =$rm->getManager();
            $em->persist($user);
            $em->flush();

        return $this->redirectToRoute('app_login');
    }

}
