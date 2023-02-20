<?php

namespace App\Controller;

use App\Repository\PlanningRepository;
use App\Repository\RendezVousRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    #[Route('/calendar', name: 'app_calendar')]
    public function index(PlanningRepository $planningRepository,RendezVousRepository $rendezVousRepository): Response
    {
        $getRendezVous = $rendezVousRepository->findUpcomingRendezVouses(new DateTime('today'));
        $rendeVouses = [];
        foreach($getRendezVous as $rendezVous){
            $rendeVouses[] = [
                'id'=> $rendezVous->getId(),
                'start'=> $rendezVous->getDate()->format('Y-m-d ').$rendezVous->getHeureDebut()->format('H:i:s'),
                'end'=> $rendezVous->getDate()->format('Y-m-d ').$rendezVous->getHeureFin()->format('H:i:s'),
                'title'=> $rendezVous->getPatient()->getNom()." ".$rendezVous->getPatient()->getPrenom(),
                'symptomes'=> $rendezVous->getSymptomes()
            ];
        }
        $getPlannings = $planningRepository->findAll();
        foreach($getPlannings as $planning){
            $rendeVouses[] = [
                'id'=> $planning->getId(),
                'start'=> $planning->getDateDebut()->format('Y-m-d ').$planning->getHeureDebut()->format('H:i:s'),
                'end'=> $planning->getDateFin()->format('Y-m-d ').$planning->getHeurefin()->format('H:i:s'),
                'description'=> $planning->getDescription(),
                'rendering'=> 'background',
                'allDay'=> 'allDay',
                
            ];
        }
        $data = json_encode($rendeVouses);
        return $this->render('Front-Office/calendar/index.html.twig', compact('data'));
    }
}
