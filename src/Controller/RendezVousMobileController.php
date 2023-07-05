<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Entity\RendezVous;
use App\Repository\PlanningRepository;
use App\Repository\RendezVousRepository;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RendezVousMobileController extends AbstractController
{
    #[Route('/rendez/vous/mobile', name: 'app_rendez_vous_mobile')]
    public function index(RendezVousRepository $rendezVousRepository,Request $req,SerializerInterface $serializer): Response
    {

        $rdvs = $rendezVousRepository->findAll();
        $rendezVousesJson=$serializer->serialize($rdvs, 'json', ['groups'=>"rendezVous"]);
        return new Response($rendezVousesJson);
    }
    #[Route('/rendez/vous/mobile/new', name: 'app_rendez_json_vous_new', methods: ['GET', 'POST'])]
    public function new(Request $request,UserRepository $patientRep ,PlanningRepository $planningRepo,RendezVousRepository $rendezVousRepository,NormalizerInterface $Normalizer): Response
    {
        $rendezVou = new RendezVous();
        $rendezVou->setPlanning($planningRepo->find(2));
        $rendezVou->setPatient($patientRep->find(2));
        $rendezVou->setEtat("en attente");
        $date_creation = new DateTimeImmutable();
        $rendezVou->setDateDeCreation($date_creation);
        $rendezVou->setDate(new DateTime($request->get('date')));
        $rendezVou->setHeureDebut(new DateTime($request->get('heureDebut')));
        $rendezVou->setHeureFin(new DateTime($request->get('heureFin')));
        $rendezVou->setSymptomes($request->get('symptomes'));
        $rendezVousRepository->save($rendezVou, true);
        $jsonContent = $Normalizer->normalize($rendezVou, 'json', ['groups' => 'rendezVous']);
        return new Response(json_encode($jsonContent));


    }

    #[Route('/rendez/vous/mobile/confirm', name: 'app_rendez_vous_mobile_confirm', methods: ['GET', 'POST'])]
    public function confirm(Request $request, RendezVousRepository $rendezVousRepository,NormalizerInterface $Normalizer): Response
    {
        $id = $request->get('id');
        $rendezVou = $rendezVousRepository->find($id);
        $rendezVou->setEtat("confirmé");
        $rendezVousRepository->save($rendezVou, true);
        $jsonContent = $Normalizer->normalize($rendezVou, 'json', ['groups' => 'rendezVous']);
        return new Response(json_encode($jsonContent));
    }
    #[Route('/rendez/vous/mobile/delete', name: 'app_rendez_vous_mobile_delete')]
    public function delete(Request $request, RendezVousRepository $rendezVousRepository,NormalizerInterface $Normalizer): Response
    {
        $id = $request->get('id');
        $rendezVou = $rendezVousRepository->find($id);
        $request->request->get('_token') ;
        $rendezVousRepository->remove($rendezVou, true);

        $jsonContent = $Normalizer->normalize($rendezVou, 'json', ['groups' => 'rendezVous']);
        return new Response(json_encode($jsonContent));
    }

    #[Route('/rendez/vous/mobile/update', name: 'app_rendez_json_vous_mobile_update', methods: ['GET', 'POST'])]
    public function update(Request $request,UserRepository $patientRep ,PlanningRepository $planningRepo,RendezVousRepository $rendezVousRepository,NormalizerInterface $Normalizer): Response
    {
        $id = $request->get('id');
        $rendezVou = $rendezVousRepository->find($id);
        $date_creation = new DateTimeImmutable();
        $rendezVou->setDateDeCreation($date_creation);
        $rendezVou->setDate(new DateTime($request->get('date')));
        $rendezVou->setHeureDebut(new DateTime($request->get('heureDebut')));
        $rendezVou->setHeureFin(new DateTime($request->get('heureFin')));
        $rendezVou->setSymptomes($request->get('symptomes'));
        $rendezVousRepository->save($rendezVou, true);
        $jsonContent = $Normalizer->normalize($rendezVou, 'json', ['groups' => 'rendezVous']);
        return new Response(json_encode($jsonContent));


    }
}
