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
use Symfony\Component\Serializer\SerializerInterface;
use DateTimeImmutable;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/planningJson')]
class PlanningJsonController extends AbstractController
{
    #[Route('/show', name: 'app_planningJson_index', methods: ['GET'])]
    public function index(PlanningRepository $planningRepository,SerializerInterface $serializer): Response
    {
        $plannings = $planningRepository->findTous1();

        $planningsJson=$serializer->serialize($plannings, 'json', ['groups'=>"planning"]);
        return new Response($planningsJson);
    }
    
    #[Route('/admin', name: 'app_planningJson_index_admin', methods: ['GET'])]
    public function indexAdmin(PlanningRepository $planningRepository,SerializerInterface $serializer): Response
    {
        $plannings = $planningRepository->findTous1();
        $planningsJson=$serializer->serialize($plannings, 'json', ['groups'=>"planning"]);
        return new Response($planningsJson);
    }

    #[Route('/{id}/new', name: 'app_planningJson_new', methods: ['GET', 'POST'])]
    public function new($id ,Request $request, UserRepository $medecinRep,PlanningRepository $planningRepository,NormalizerInterface $Normalizer): Response
    {   
        $planning = new Planning();
        $planning ->setMedecin($medecinRep->find($id));
        $planning ->setEtat("en cours");
        $date_creation = new DateTimeImmutable();
        $planning->setDateDeCreation($date_creation);
        $planning->setDateDeModification($date_creation);
        $planning->setDateDebut($request->get('dateDebut'));
        $planning->setDateFin($request->get('dateFin'));
        $planning->setHeureDebut($request->get('heureDebut'));
        $planning->setHeureFin($request->get('heureFin'));
        $planning->setDescription($request->get('description'));
        $planningRepository->save($planning, true);

        $jsonContent = $Normalizer->normalize($planning, 'json', ['groups' => 'planning']);
        return new Response(json_encode($jsonContent));
    }

    #[Route('/{id}', name: 'app_planningJson_show', methods: ['GET'])]
    public function show(Planning $planning,SerializerInterface $serializer): Response
    {
        $planningJson=$serializer->serialize($planning, 'json', ['groups'=>"planning"]);
        return new Response($planningJson);
    }

    #[Route('/{id}/edit', name: 'app_planningJson_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planning $planning, PlanningRepository $planningRepository,NormalizerInterface $Normalizer): Response
    {
        $date_modification = new DateTimeImmutable();
        $planning->setDateDeModification($date_modification);
        $planning->setDateDebut($request->get('dateDebut'));
        $planning->setDateFin($request->get('dateFin'));
        $planning->setHeureDebut($request->get('heureDebut'));
        $planning->setHeureFin($request->get('heureFin'));
        $planning->setDescription($request->get('description'));
        $planningRepository->save($planning, true);

        $jsonContent = $Normalizer->normalize($planning, 'json', ['groups' => 'planning']);
        return new Response(json_encode($jsonContent));

    }

}
