<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Form\FactureType;
use App\Entity\Pharmacie;
use App\Entity\User;
use App\Entity\Ordonnance;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Attachment;
use App\Repository\PharmacieRepository;
use App\Repository\UserRepository;
use App\Repository\FactureRepository;
use App\Service\DompdfService;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;




#[Route('/jsonfacture')]
class FactureJsonController extends AbstractController
{
    #[Route('/', name: 'app_facturejson_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository , NormalizerInterface $Normalizer): Response
    {
        return $this->render('facture/index.html.twig', [
            'factures' => $factureRepository->findAll(),
        ]); 
    }

    #[Route('/new', name: 'app_facturejson_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FactureRepository $factureRepository, UserRepository $userrepository, SluggerInterface $slugger , MailerService $mailer , PharmacieRepository $pharmacieRepository,NormalizerInterface $Normalizer): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);
        $pharmacie = $form->get('pharmacie')->getData();
        //$facture->getPatient($userrepository->getData());

        if ($form->isSubmitted() && $form->isValid()) {

            $factureRepository->save($facture, true);
            $data=[

                'idPatient'=>$facture->getOrdonnance()->getConsultation()->getRendezvous()->getPatient()->getId(),
                'mailPatient'=>$facture->getOrdonnance()->getConsultation()->getRendezvous()->getPatient()->getEmail(),
                'mailPharmacie'=>$facture->getPharmacie()->getEmail(),

            ]
        ;

            return $this->redirectToRoute('app_facturejson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }




    #[Route('/{id}', name: 'app_facturejson_show', methods: ['GET'])]
    public function show(Facture $facture , NormalizerInterface $Normalizer): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    

    #[Route('/{id}/pdf', name: 'app_facturejson_pdf', methods: ['GET'])]
    public function generatepdffacture(Facture $facture = null, DompdfService $pdf , NormalizerInterface $Normalizer ) {
        return$this->render('facture/showpdf1.html.twig',['facture' => $facture]) ;
 // $html = $this->render('facture/showpdf1.html.twig',['facture' => $facture]) ;

//$pdf->showPdfFile($html);
//$pdf->generateBinaryPdf($html) ;

    }


    #[Route('/{id}/edit', name: 'app_facturejson_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, FactureRepository $factureRepository , NormalizerInterface $Normalizer): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factureRepository->save($facture, true);

            return $this->redirectToRoute('app_facturejson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facturejson_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, FactureRepository $factureRepository , NormalizerInterface $Normalizer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $factureRepository->remove($facture, true);
        }

        return $this->redirectToRoute('app_facturejson_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/recherche', name: 'app_facturejson_recherche', methods: ['GET' ,'POST'])]
    public function chercher(FactureRepository $factureRepository,Request $req , NormalizerInterface $Normalizer): Response
    {
        $form = $this->createForm(FactureRechercheType::class);
        $form->HandleRequest($req);
        
        if ($form -> isSUbmitted()) {
            $data=  $form->getData();
            $rech = $factureRepository->rechercherFacture($data);
             return $this->render('Front-Office/facture/index.html.twig', [
                 'factures' => $rech,
             ]);
         }
         $result = $factureRepository->findAll();
         return $this->render('Front-Office/facture/index.html.twig', [
            'factures' => $result,
            'f'=>$form->createView()
        ]);
    }
 
    #[Route('/statistics/ph', name: 'app_facturejson_stat')]
    public function stat(FactureRepository $factureRepository , NormalizerInterface $Normalizer): Response
    { 

        $totalfactures = $factureRepository->TotalFactures();
        $totalRevenus = $factureRepository ->getTotalRevenus();
        return $this->render('facture/statistics.html.twig', [
            'totalFactures' => $totalfactures,
             'TotalRevenus' => $totalRevenus,
        ]
        );
    
    }
    public function FacturesParpharmacie() {
        $factures = $this->getDoctrine()
            ->getRepository(Facture::class)
            ->findBy([], ['pharmacie' => 'ASC']);
        return $factures;
    }
    
    
    public function toutesLespharmacies() {
        $pharmacies = $this->getDoctrine()
            ->getRepository(Pharmacie::class)
            ->findAll();
        return $pharmacies;
    }
    
    
    
    public function statsFacturesParpharmacie() {
        $factures = $this->FacturesParpharmacie();
        $pharmacies =$this->toutesLespharmacies();
        $stats = array();
        foreach ($pharmacies as $pharmacie) {
            $nbfactures = 0;
            foreach ($factures as $facture) {
                if ($facture->getPharmacie() == $pharmacie) {
                    $nbfactures++;
                }
            }
            $stats[] = array(
                'pharmacie' => $pharmacie,
                'nbfactures' => $nbfactures
            );
        }
        return $stats;
    }
   

    #[Route('/statisticsadmin/ph', name: 'app_facjson_stat')]
    public function indexxx(NormalizerInterface $Normalizer): Response
    {
        return $this->render('facture/statisticsadmin.html.twig', [
            'stats' => $this->statsFacturesParpharmacie()
            
        ]);
    }

}



    
 


    
   


   
