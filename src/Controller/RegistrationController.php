<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\EditFormMedecinType;
use App\Form\EditFormUserType;
use App\Form\RenvoyerCodeVerifType;
use App\Form\UserType;
use App\Form\VerificationEmailType;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher ;
    }

    #[Route('/registerPatient', name: 'app_registration_patient')]
    public function registerPatient(Request $request,ManagerRegistry $rm,MailerService $mailer)
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
            $user->setRoles(['ROLE_PATIENT']);
            $user->setDateDeCreation(new \DateTime());
            $user->setEtat("non vérifié");
            $verificationCode = rand(100000, 999999);
            $user->setResetToken($verificationCode);
            // Save
            $em =$rm->getManager();
            $em->persist($user);
            $em->flush();

            $context = ['user' =>$user];

            $mailer->sendEmail(
                to: $user->getEmail(),
                template: 'verificationCode',
                subject: ' Confirmation de création de compte',
                context: $context
            );

            $mailer->sendEmail(
                to: "lina.laroussi20@gmail.com",
                template: 'confirmation-register-admin',
                subject: ' Nouveau compte utilisateur créé',
                context: $context
            );
            return $this->redirectToRoute('app_verification_patient');

        }
        return $this->render('Front-Office/registration/register-patient.html.twig', [

            'form' => $form->createView()
        ]);
    }


    #[Route('/verifyPatient', name: 'app_verification_patient')]
    public function VerifyPatient(Request $request,ManagerRegistry $rm,MailerService $mailer,UserRepository $repo)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_user_home');
        }

        $form = $this->createForm(VerificationEmailType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $Verifcode=$form->get('code')->getData();

            $user=$repo->findOneByCode($Verifcode);
            if($user){
                $userCode = $user->getResetToken();
                if($Verifcode == $userCode ){

                    $user->setEtat("valide");
                    $user->setResetToken('');
                    // Save
                    $em =$rm->getManager();
                    $em->persist($user);
                    $em->flush();

                    $context = ['user' =>$user];

                    $mailer->sendEmail(
                        to: $user->getEmail(),
                        template: 'confirmation_verification',
                        subject: ' Verification de votre compte',
                        context: $context
                    );

                    return $this->redirectToRoute('app_login');
                }
            }
            else{
                return $this->redirectToRoute('app_code_invalid');
            }

        }
        return $this->render('Front-Office/registration/verify-email.html.twig', [

            'form' => $form->createView(),
        ]);
    }


    #[Route('/error', name: 'app_code_invalid')]
    public function error(): Response
    {
        return $this->render('Front-Office/registration/code-invalid.html.twig', [
            'controller_name' => 'RegistrationController'

        ]);
    }

    #[Route('/notFound', name: 'app_user_not_found')]
    public function notFound(): Response
    {
        return $this->render('Front-Office/registration/user-not-found.html.twig', [
            'controller_name' => 'RegistrationController'

        ]);
    }


    #[Route('/renvoyerCode', name: 'app_renvoyer_code')]
    public function renvoyezCode(Request $request,ManagerRegistry $rm,MailerService $mailer,UserRepository $repo)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_user_home');
        }
        $form = $this->createForm(RenvoyerCodeVerifType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email=$form->get('email')->getData();

            $user=$repo->findOneByEmail($email);

            if($user) {
                $verificationCode = rand(100000, 999999);
                $user->setResetToken($verificationCode);
                // Save
                $em = $rm->getManager();
                $em->persist($user);
                $em->flush();

                $context = ['user' => $user];

                $mailer->sendEmail(
                    to: $user->getEmail(),
                    template: 'nouveau-code',
                    subject: ' Nouveau code de vérification',
                    context: $context
                );

                return $this->redirectToRoute('app_verification_patient');
            }
            else {
                return $this->redirectToRoute('app_user_not_found');
            }
        }
        return $this->render('Front-Office/registration/renvoyer-code.html.twig', [

            'form' => $form->createView()

        ]);
    }

    #[Route('/registerMedecin', name: 'app_registration_doctor')]
    public function registerDoctor(Request $request,ManagerRegistry $rm,MailerService $mailer)
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
                to: "lina.laroussi20@gmail.com",
                template: 'confirmation-register-admin',
                subject: ' Nouveau compte utilisateur créé',
                context: $context
            );
            return $this->redirectToRoute('app_login');

        }
        return $this->render('Front-Office/registration/register-doctor.html.twig', [

            'form' => $form->createView()
        ]);
    }

    #[Route('/registerPharmacien', name: 'app_registration_pharmacien')]
    public function registerPharmacien(Request $request,ManagerRegistry $rm,MailerService $mailer)
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
                to: "lina.laroussi20@gmail.com",
                template: 'confirmation-register-admin',
                subject: ' Nouveau compte utilisateur créé',
                context: $context
            );
            return $this->redirectToRoute('app_login');

        }
        return $this->render('Front-Office/registration/register-pharmacien.html.twig', [

            'form' => $form->createView()
        ]);
    }


    #[Route('/registerAssureur', name: 'app_registration_assureur')]
    public function registerAssureur(Request $request,ManagerRegistry $rm,MailerService $mailer)
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
                to: "lina.laroussi20@gmail.com",
                template: 'confirmation-register-admin',
                subject: ' Nouveau compte utilisateur créé',
                context: $context
            );
            return $this->redirectToRoute('app_login');
            
        }
        return $this->render('Front-Office/registration/register-assureur.html.twig', [

            'form' => $form->createView()
        ]);
    }

    #[Route('/registerAdmin', name: 'app_registration_admin')]
    public function registerAdmin(Request $request,ManagerRegistry $rm)
    {
            $user = new User();
            $user->setEmail("lina.laroussi20@gmail.com");
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
