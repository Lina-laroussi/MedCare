<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Form\RendezVousCalendarType;
use App\Repository\PlanningRepository;
use App\Repository\RendezVousRepository;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/calendar')]
class CalendarController extends AbstractController
{
    #[Route('/', name: 'app_calendar', methods: ['GET', 'POST'])]
    public function index(Request $request,PlanningRepository $planningRepository,UserRepository $patientRep,RendezVousRepository $rendezVousRepository): Response
    {
        $getRendezVous = $rendezVousRepository->findUpcomingRendezVouses(new DateTime('today'));
        $rendeVouses = [];
        foreach($getRendezVous as $rendezVous){
            $rendeVouses[] = [
                'id'=> $rendezVous->getId(),
                'start'=> $rendezVous->getDate()->format('Y-m-d ').$rendezVous->getHeureDebut()->format('H:i:s'),
                'end'=> $rendezVous->getDate()->format('Y-m-d ').$rendezVous->getHeureFin()->format('H:i:s'),
                'title'=> $rendezVous->getPatient()->getNom()." ".$rendezVous->getPatient()->getPrenom(),
                'type'=>'rendezVous',
                'height' => '50',
               // 'allDay' => true,
                'symptomes'=> $rendezVous->getSymptomes(),
            ];
        }
        $getPlannings = $planningRepository->findAll();
        foreach($getPlannings as $planning){
            $startDate =new \DateTime( $planning->getDateDebut()->format('Y-m-d ').$planning->getHeureDebut()->format('H:i:s'));
            $endDate = new \DateTime($planning->getDateFin()->format('Y-m-d ').$planning->getHeurefin()->format('H:i:s'));
            $startTime = $planning->getHeureDebut()->format('H:i:s');
            $endTime = $planning->getHeureFin()->format('H:i:s');

            for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
            $rendeVouses[] = [
                'id'=> $planning->getId(),
                'planningStart'=>$startDate->modify('-1 day')->format('Y-m-d H:i:s'),
                'planningEnd'=>$endDate->format('Y-m-d H:i:s'),
                'start' => $date->format('Y-m-d ').$startTime,
                'end' => $date->format('Y-m-d ').$endTime,
                'startTime'=>$startTime,
                'endeTime'=>$endTime,
                'description'=> $planning->getDescription(),
                'planningId'=>$planning->getId(),
                'type'=>'planning',
                //'allDay' => true,
                'extendedProps' => [
                    'type' => 'recurring',
                ],
        
                'rendering'=> 'background',
                
            ];}
            
        }
        foreach($getPlannings as $planning){
            $startDate =new \DateTime( $planning->getDateDebut()->format('Y-m-d ').$planning->getHeureDebut()->format('H:i:s'));
            $endDate = new \DateTime($planning->getDateFin()->format('Y-m-d ').$planning->getHeurefin()->format('H:i:s'));
            $startTime = $planning->getHeureDebut()->format('H:i:s');
            $endTime = $planning->getHeureFin()->format('H:i:s');

            for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
            $rendeVouses[] = [
                'id'=> $planning->getId(),
                'start' => $date->format('Y-m-d ').$startTime,
                'end' => $date->format('Y-m-d ').$endTime,
                'startTime'=>$startTime,
                'endeTime'=>$endTime,
                'planningId'=>$planning->getId(),
                'type'=>'planningAllDay',
                'allDay' => true,
                'extendedProps' => [
                    'type' => 'recurring',
                ],
        
                'rendering'=> 'background',
                
            ];}
            
        }
        $data = json_encode($rendeVouses);
        $rendezVou = new RendezVous();

        return $this->render('Front-Office/calendar/index.html.twig', compact('data'));
    }
    #[Route('/new/{planningId}', name: 'app_rendez_vous_calendar_new', methods: ['GET', 'POST'])]
    public function new($planningId,Request $request,PlanningRepository $planningRep,UserRepository $patientRep ,RendezVousRepository $rendezVousRepository): Response
    {
        $rendezVou = new RendezVous();
        $planning= $planningRep->find($planningId);
        $rendezVou->setPlanning($planning);
        $rendezVou->setPatient($patientRep->find(2));
        $rendezVou->setEtat("en attente");
        $date_creation = new DateTimeImmutable();
        $rendezVou->setDateDeCreation($date_creation);
        $form = $this->createForm(RendezVousCalendarType::class, $rendezVou);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $rendezVousRepository->save($rendezVou, true);

           return $this->redirectToRoute('app_calendar', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Front-Office/calendar/_form.html.twig', [
            'form' => $form,
        ]);
    }


}
