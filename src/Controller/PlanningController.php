<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Entity\User;
use App\Form\PlanningType;
use App\Repository\PlanningRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;

#[Route('/planning')]
class PlanningController extends AbstractController
{
    #[Route('/show/{page?1}/{nbre?5}', name: 'app_planning_index', methods: ['GET'])]
    public function index(PlanningRepository $planningRepository,$nbre,$page): Response
    {
        $nbPlanning = $planningRepository->countPlanning();
        $nbrePage = ceil($nbPlanning / $nbre) ;
        return $this->render('Front-Office/planning/index.html.twig', [
            'plannings' => $planningRepository->findTous($page,$nbre),
            'isPaginated'=>true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre
        ]);
    }
    #[Route('/admin/{page?1}/{nbre?5}', name: 'app_planning_index_admin', methods: ['GET'])]
    public function indexAdmin(PlanningRepository $planningRepository,$page, $nbre): Response
    {
        $plannings = $planningRepository->findBy([], [],$nbre, ($page - 1 ) * $nbre);
        $nbPlannings = $planningRepository->count([]);
        $nbrePage = ceil($nbPlannings / $nbre) ;
        return $this->render('Back-Office/planning/index.html.twig', [
            'plannings' => $plannings,
            'isPaginated' => true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre
        ]);
    }

    #[Route('/{id}/new', name: 'app_planning_new', methods: ['GET', 'POST'])]
    public function new($id ,Request $request, UserRepository $medecinRep,PlanningRepository $planningRepository): Response
    {   
        $planning = new Planning();
        $planning ->setMedecin($medecinRep->find($id));
        $planning ->setEtat("en cours");
        $date_creation = new DateTimeImmutable();
        $planning->setDateDeCreation($date_creation);
        $planning->setDateDeModification($date_creation);
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planningRepository->save($planning, true);

            return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Front-Office/planning/new.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_planning_show', methods: ['GET'])]
    public function show(Planning $planning): Response
    {
        return $this->render('Front-Office/planning/show.html.twig', [
            'planning' => $planning,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_planning_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planning $planning, PlanningRepository $planningRepository): Response
    {
        $date_modification = new DateTimeImmutable();
        $planning->setDateDeModification($date_modification);
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planningRepository->save($planning, true);

            return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Front-Office/planning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_planning_delete', methods: ['POST'])]
    public function delete(Request $request, Planning $planning, PlanningRepository $planningRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planning->getId(), $request->request->get('_token'))) {
            $planningRepository->remove($planning, true);
        }

        return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
    }


}
