<?php

namespace App\Controller;

use App\Form\ForgotPasswordFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;

class SecurityControllerJson extends AbstractController
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher ;
    }

    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('Front-Office/security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route(path: '/loginJson', name: 'app_login_json')]
    public function login(AuthenticationUtils $authenticationUtils,SerializerInterface $serializer): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $login=[$error,$lastUsername];

        $jsonLogin = $serializer->serialize($login, 'json',['groups' => "User"]);

        return new JsonResponse($jsonLogin,Response::HTTP_OK,[],true);
    }

    #[Route(path: '/logoutJson', name: 'app_logout_json')]
    public function logout(): void
    {
       // throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/forgotPassJson', name: 'app_forgot_password_json')]
    public function forgottenPassword(Request $request,UserRepository $repo,MailerService $mailer,TokenGeneratorInterface $tokenGenerator,ManagerRegistry $rm,SerializerInterface $serializer)
    {
        $form=$this->createForm(ForgotPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user=$repo->findOneByEmail($form->get('email')->getData());
            if($user){
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $em =$rm->getManager();
                $em->persist($user);
                $em->flush();

                $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                //$context = compact('url', 'user');
                $context = ['url'=>$url , 'user' =>$user];

                $mailer->sendEmail(
                    to: $user->getEmail(),
                    template: 'forgot-password',
                    subject: 'Réinitialisation de mot de passe',
                    context: $context
                );
                return $this->redirectToRoute('app_login');
            }
            return $this->redirectToRoute('app_login');
        }

        $jsonForgot = $serializer->serialize($form, 'json');
        return new JsonResponse($jsonForgot,Response::HTTP_OK,[],true);
    }

    #[Route('/resetPassJson{token}', name: 'app_reset_password_json')]
    public function resetPass($token , UserRepository $repo,Request $request,ManagerRegistry $rm,MailerService $mailer,SerializerInterface $serializer): Response
    {
        $user = $repo->findOneByResetToken($token);

        if($user) {
            $form = $this->createForm(ResetPasswordFormType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $user->setResetToken('');
                $user->setPassword($this->userPasswordHasher->hashPassword($user, $form->get('password')->getData()));
                $em=$rm->getManager();
                $em->persist($user);
                $em->flush();

                $context = ['user' =>$user];

                $mailer->sendEmail(
                    to: $user->getEmail(),
                    template: 'confirmation-password',
                    subject: ' Confirmation du changement de mot de passe',
                    context: $context
                );

                return $this->redirectToRoute('app_login');
            }
        }
        $jsonReset = $serializer->serialize($form, 'json');
        return new JsonResponse($jsonReset,Response::HTTP_OK,[],true);
    }


}
