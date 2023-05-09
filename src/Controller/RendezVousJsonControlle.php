<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Entity\RendezVous;
use App\Form\RendezVousRechercheType;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
/*
#[Route('/rendezVousJson')]
class RendezVousJsonController extends AbstractController
{
    #[Route('/show', name: 'app_rendez_vous_json_index', methods: ['GET'])]
    public function index(RendezVousRepository $rendezVousRepository,Request $req,SerializerInterface $serializer): JsonResponse
    {
   
        $rdvs = $rendezVousRepository->findAll();
        $rendezVousesJson=$serializer->serialize($rdvs, 'json', ['groups'=>"rendezVous"]);
        return new JsonResponse($rendezVousesJson);
    }
    #[Route('/admin', name: 'app_rendez_vous_admin_json_index', methods: ['GET'])]
    public function indexAdmin(RendezVousRepository $rendezVousRepository,SerializerInterface $serializer): Response
    {
        $rendezVouses = $rendezVousRepository->findAll();
        $rendezVousesJson=$serializer->serialize($rendezVouses, 'json', ['groups'=>"rendezVous"]);
        return new Response($rendezVousesJson);
    }

    #[Route('/{id}/new', name: 'app_rendez_json_vous_new', methods: ['GET', 'POST'])]
    public function new(Request $request,Planning $planning,UserRepository $patientRep ,RendezVousRepository $rendezVousRepository,NormalizerInterface $Normalizer): Response
    {
        $rendezVou = new RendezVous();
        $rendezVou->setPlanning($planning);
        $rendezVou->setPatient($patientRep->find(2));
        $rendezVou->setEtat("en attente");
        $date_creation = new DateTimeImmutable();
        $rendezVou->setDate($request->get('date'));
        $rendezVou->setHeureDebut($request->get('heureDebut'));
        $rendezVou->setHeureFin($request->get('heureFin'));
        $rendezVou->setSymptomes($request->get('symptomes'));
        $rendezVousRepository->save($rendezVou, true);
        $jsonContent = $Normalizer->normalize($rendezVou, 'json', ['groups' => 'rendezVous']);
        return new Response(json_encode($jsonContent));


    }

    #[Route('/{id}', name: 'app_rendez_vous_json_show', methods: ['GET'])]
    public function show(RendezVous $rendezVou,SerializerInterface $serializer): Response
    {
        $rendezVousesJson=$serializer->serialize($rendezVou, 'json', ['groups'=>"rendezVous"]);
        return new Response($rendezVousesJson);
    }

    #[Route('/{id}/edit', name: 'app_rendez_vous_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository,NormalizerInterface $Normalizer): Response
    {

        $rendezVou->setDate($request->get('date'));
        $rendezVou->setHeureDebut($request->get('heureDebut'));
        $rendezVou->setHeureFin($request->get('heureFin'));
        $rendezVou->setSymptomes($request->get('symptomes'));

        $rendezVousRepository->save($rendezVou, true);
        $jsonContent = $Normalizer->normalize($rendezVou, 'json', ['groups' => 'rendezVous']);
        return new Response(json_encode($jsonContent));
        
    }
    
    #[Route('/{id}/confirm', name: 'app_rendez_vous_confirm', methods: ['GET', 'POST'])]
    public function confirm(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository,HubInterface $hub): Response
    {

        $rendezVou->setEtat("confirmé");
        $rendezVousRepository->save($rendezVou, true);
        $data=[

            'date' => $rendezVou->getDate()->format('Y-m-d'),
            'heure'=>$rendezVou->getHeureDebut()->format('H:i:s'),
            'idPatient'=>$rendezVou->getPatient()->getId(),
            'nomMedecin'=>$rendezVou->getPlanning()->getMedecin()->getNom().$rendezVou->getPlanning()->getMedecin()->getPrenom(),
            'idMedecin'=>$rendezVou->getPlanning()->getMedecin()->getId(),
            

        ];
        
        return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/cancel', name: 'app_rendez_vous_cancel', methods: ['GET', 'POST'])]
    public function cancel(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository): Response
    {

        $rendezVou->setEtat("annulé");
        $rendezVousRepository->save($rendezVou, true);
        return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}', name: 'app_rendez_vous_delete', methods: ['POST'])]
    public function delete(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rendezVou->getId(), $request->request->get('_token'))) {
            $rendezVousRepository->remove($rendezVou, true);
        }

        return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
    }
}
*/