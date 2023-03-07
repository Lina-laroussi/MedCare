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



#[Route('/facture')]
class FactureController extends AbstractController
{
    #[Route('/', name: 'app_facture_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository): Response
    {
        return $this->render('facture/index.html.twig', [
            'factures' => $factureRepository->findAll(),
        ]);
        
    
    }
    #[Route('/new', name: 'app_facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FactureRepository $factureRepository, UserRepository $userrepository, SluggerInterface $slugger , MailerService $mailer , PharmacieRepository $pharmacieRepository): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);
        $pharmacie = $form->get('pharmacie')->getData();
        //$facture->getPatient($userrepository->getData());

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

           
            //l'image n'est pas obligatoire  donc field est lu seulement si l'image est uploaded
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // changement du nom de l'image

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                //transpérer 
                try {
                    $image->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $facture->setImageSignature($newFilename);
            }




          //  $mailer->sendEmail(content:'voila votre facture');
            //$mailer->sendEmail(from:'pharmacoemedcare@gmail.com',to:'feryelouerfelli@gmail.com',content:'votre facture',subject: 'Facture Pharmacie');
            $mailer->sendEmail(from:$facture->getPharmacie()->getEmail(),to:$facture->getOrdonnance()->getConsultation()->getRendezvous()->getPatient()->getEmail(),content:'votre facture',subject: 'Facture Pharmacie', tmpFile:'document.pdf');

            $factureRepository->save($facture, true);
            $data=[

                'idPatient'=>$facture->getOrdonnance()->getConsultation()->getRendezvous()->getPatient()->getId(),
                'mailPatient'=>$facture->getOrdonnance()->getConsultation()->getRendezvous()->getPatient()->getEmail(),
                'mailPharmacie'=>$facture->getPharmacie()->getEmail(),

            ]
        ;

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/{id}/pdf', name: 'app_facture_pdf', methods: ['GET'])]
    public function generatepdffacture(Facture $facture = null, DompdfService $pdf ) {
        return$this->render('facture/showpdf1.html.twig',['facture' => $facture]) ;
 // $html = $this->render('facture/showpdf1.html.twig',['facture' => $facture]) ;

//$pdf->showPdfFile($html);
//$pdf->generateBinaryPdf($html) ;

    }


    #[Route('/{id}/print', name: 'print_pdf')]
    public function printfacture(Facture $facture): Response
    {
        return $this->render('facture/showpdf.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, FactureRepository $factureRepository): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factureRepository->save($facture, true);

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, FactureRepository $factureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $factureRepository->remove($facture, true);
        }

        return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/recherche', name: 'app_facture_recherche', methods: ['GET' ,'POST'])]
    public function chercherRDV(FactureRepository $factureRepository,Request $req): Response
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
   /* #[Route('/statistics/ph', name: 'app_facture_stat')]
    public function Statistics(Facture $facture, FactureRepository $factureRepository): Response
    {
       $factures = $factureRepository->countfactures();

        return $this->render('facture/statistics.html.twig', [
            'factures' => $factures,
        ]);
    }
    */
    #[Route('/statistics/ph', name: 'app_fac_stat')]
    public function stat(FactureRepository $factureRepository): Response
    { 

        $totalfactures = $factureRepository->TotalFactures();
        $totalRevenus = $factureRepository ->getTotalRevenus();
        return $this->render('facture/statistics.html.twig', [
            'totalFactures' => $totalfactures,
             'TotalRevenus' => $totalRevenus,
        ]
        );
    
    }
  #[Route('/statistics/ph1', name: 'app_fact_stat')]

    public function FacturesStatistics(FactureRepository $factureRepository, PharmacieRepository $pharmacieRepository ): Response
    {
        $pharmacies = $pharmacieRepository->findAll();

        $pharmaNom = [];
      

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($pharmacies as $pharmacie){
            $pharmaNom[] = $pharmacie->getNom();
          
        }

        // On va chercher le nombre d'annonces publiées par date
        $factures = $factureRepository->countByDate();
        $dates = [];
        $facturesCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($factures as $facture){
            $dates[] = $facture['date'];
            $facturesCount[] = $facture['count'];
        }

        return $this->render('facture/statistics1.html.twig', [
            'pharmaNom' => json_encode($pharmaNom),
            'pharmaCount' => json_encode($pharmaCount),
            'dates' => json_encode($dates),
            'facturesCount' => json_encode($facturesCount),
        ]);
    }

    }
   


   
