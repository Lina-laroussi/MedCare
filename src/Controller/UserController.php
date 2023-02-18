<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditFormUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

   //user connected
    #[Route('/profile', name: 'app_user_profile', methods: ['GET','POST'])]
    public function getUserConnected(UserRepository $userRepository,Request $request,ManagerRegistry $rm): Response
    {
        $currentuser = $this->getUser();

        $form = $this->createForm(EditFormUserType::class, $currentuser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$userRepository->save($currentuser, true);

            $em =$rm->getManager();
            $em->persist($currentuser);
            $em->flush();
            return $this->redirectToRoute('app_user_profile');
        }
        return $this->render('Front-Office/profile/profile-user.html.twig', [
            'user' => $currentuser,
            'form'=> $form->createView()

        ]);
    }

    /*#[Route('/editProfile', name: 'app_edit_profile', methods: ['GET'])]
    public function editUserConnected(UserRepository $userRepository,Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(EditFormUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Front-Office/profile/profile-user.html.twig', [
            'user' => $user,
            'form'=> $form

        ]);
    }*/


    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
