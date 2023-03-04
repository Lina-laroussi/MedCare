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
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

#[Route('/rendezVous')]
class RendezVousController extends AbstractController
{
    #[Route('/', name: 'app_rendez_vous_index', methods: ['GET','POST'])]
    public function index(RendezVousRepository $rendezVousRepository,Request $req): Response
    {
        $date = new DateTime('today');
        $todaysDate = $date->format('Y-m-d');
        $form = $this->createForm(RendezVousRechercheType::class);
        $form->HandleRequest($req);
        
        if ($form -> isSUbmitted()) {
            $data=  $form->getData();
            $rech = $rendezVousRepository->rechercherRDV($data);
             return $this->render('Front-Office/rendez_vous/index2.html.twig', [
                 'rendez_vouses' => $rech,
                 'form'=>$form->createView()
             ]);
         }
        return $this->render('Front-Office/rendez_vous/index.html.twig', [
            'rendez_vouses' => $rendezVousRepository->findUpcomingRendezVouses($todaysDate),
            'today_rendez_vouses' => $rendezVousRepository->findRendezVousesBydate($todaysDate),
            'date'=> $todaysDate,
            'form'=>$form->createView()

        ]);
    }
    #[Route('/admin', name: 'app_rendez_vous_admin_index', methods: ['GET'])]
    public function indexAdmin(RendezVousRepository $rendezVousRepository): Response
    {
 
        return $this->render('Back-Office/rendez_vous/index.html.twig', [
            'rendez_vouses' => $rendezVousRepository->findAll(),
          

        ]);
    }

    #[Route('/{id}/new', name: 'app_rendez_vous_new', methods: ['GET', 'POST'])]
    public function new(Request $request,Planning $planning,UserRepository $patientRep ,RendezVousRepository $rendezVousRepository,HubInterface $hub): Response
    {
        $rendezVou = new RendezVous();
        $rendezVou->setPlanning($planning);
        $rendezVou->setPatient($patientRep->find(2));
        $rendezVou->setEtat("en attente");
        $date_creation = new DateTimeImmutable();
        $rendezVou->setDateDeCreation($date_creation);
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $rendezVousRepository->save($rendezVou, true);
            $data=[

                    'date' => $rendezVou->getDate()->format('Y-m-d'),
                    'heure'=>$rendezVou->getHeureDebut()->format('H:i:s'),
                    'idPatient'=>$rendezVou->getPatient()->getId(),
                    'nomPatient'=>$rendezVou->getPatient()->getNom().$rendezVou->getPatient()->getPrenom(),
                    'idMedecin'=>$rendezVou->getPlanning()->getMedecin()->getId()

                ]
            ;
            $update = new Update(
                '/rdvAjouter',  //.$rendezVou->getPlanning()->getMedecin()->getId(),
                json_encode($data)
            );
    
            $hub->publish($update);
            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Front-Office/rendez_vous/new.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rendez_vous_show', methods: ['GET'])]
    public function show(RendezVous $rendezVou): Response
    {
        return $this->render('Front-Office/rendez_vous/show.html.twig', [
            'rendez_vou' => $rendezVou,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rendez_vous_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository): Response
    {
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rendezVousRepository->save($rendezVou, true);

            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('Front-Office/rendez_vous/edit.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);
        
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
            $update = new Update(
                '/rdvConfirme',  //.$rendezVou->getPlanning()->getMedecin()->getId(),
                json_encode($data)
            );

            $hub->publish($update);
        

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
    #[Route('/recherche', name: 'app_rendez_vous_recherche', methods: ['GET' ,'POST'])]
    public function chercherRDV(RendezVousRepository $rendezVousRepository,Request $req): Response
    {
        $form = $this->createForm(RendezVousRechercheType::class);
        $form->HandleRequest($req);
        
        if ($form -> isSUbmitted()) {
            $data=  $form->getData();
            $rech = $rendezVousRepository->rechercherRDV($data);
             return $this->render('Front-Office/rendez_vous/index.html.twig', [
                 'rendez_vouses' => $rech,
             ]);
         }
         $result = $rendezVousRepository->findAll();
         return $this->render('Front-Office/rendez_vous/index.html.twig', [
            'rendez_vouses' => $result,
            'f'=>$form->createView()
        ]);
    }
}
