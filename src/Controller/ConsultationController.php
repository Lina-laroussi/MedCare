<?php

namespace App\Controller; 

use App\Entity\Consultation;
use App\Form\Consultation1Type;
use App\Repository\ConsultationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

#[Route('/consultation'),
    IsGranted ('IS_AUTHENTICATED_FULLY')]
class ConsultationController extends AbstractController
{
// -----------------------afficher tous les consultation-----------------------------------
    #[Route('/show/{page?1}/{nbre?5}', name: 'app_consultation_index', methods: ['GET'])]
    public function index(ConsultationRepository $consultationRepository,$nbre,$page): Response
    {
        $user=$this->getUser();
        $nbConsult = $consultationRepository->countConsult();
        $nbrePage = ceil((int)$nbConsult /(int)$nbre) ;
        return $this->render('Front-Office/consultation/consultation.html.twig', [
            'consultations' => $consultationRepository->findTous($page,$nbre),
            'isPaginated'=>true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre,
            'user'=>$user
        ]);
    }

// --------------------------------ajouter/créer consultation----------------------------------------
    #[Route('/new', name: 'app_consultation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ConsultationRepository $consultationRepository): Response
    {
        $user=$this->getUser();
        $consultation = new Consultation();
        $form = $this->createForm(Consultation1Type::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $consultationRepository->save($consultation, true);        

        return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Front-Office/consultation/new.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
            'user'=>$user
        ]);
    }

// ---------------------------------afficher consultation par id---------------------------------------
    #[Route('/{id}', name: 'app_consultation_show', methods: ['GET'])]
    public function show(Consultation $consultation): Response
    {
        $user=$this->getUser();
        return $this->render('Front-Office/consultation/show.html.twig', [
            'consultation' => $consultation,
            'user'=>$user
        ]);
    }

// --------------------------------------modifier consultation---------------------------------------
    #[Route('/{id}/edit', name: 'app_consultation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Consultation $consultation, ConsultationRepository $consultationRepository): Response
    {
        $user=$this->getUser();
    $form = $this->createForm(Consultation1Type::class, $consultation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $consultationRepository->save($consultation, true);

        // Get patient phone number : $consultation->getRendezVous()->getPatient()->getNumTel()
        $patientPhoneNumber = '+21696082716';

        // Get consultation date and time
        $consultationDateTime = $consultation->getDateRendezVous();

        // Get current time
        $currentTime = new \DateTime('now');

        // Calculate time difference in hours
        $diff = $consultationDateTime->diff($currentTime);
        $hoursDiff = $diff->h + ($diff->days * 24);


        // Check if there's 24 hours or less until the consultation
        if ($hoursDiff <= 24) {
            // Send reminder SMS to patient
            //require_once '/path/to/vendor/autoload.php';
            $accountSid = 'ACf8d83ce8d583440b6c27987c10a0b106';
            $authToken = '4c6e49b373389a81c36227d2ee80dbac';
            $twilio = new Client($accountSid, $authToken); 
 
            $message = $twilio->messages 
                            ->create($patientPhoneNumber, // to : Patient phone number 
                                    array(  
                                        "messagingServiceSid" => "MG35ecb99459fed44b9da5124d3041ece9",      
                                        "body" => "Rappel : Vous avez une consultation demain à ". $consultationDateTime->format('H:i') . "avec docteur " . '.'
                                    ) 
                            ); 
            
            print($message->sid);
            /*
            $twilioNumber = '+13087304924';

            $client = new Client($accountSid, $authToken);

            $client->messages->create(
                $patientPhoneNumber, // Patient phone number
                array(
                    'from' => $twilioNumber,
                    'body' => 'You have a consultation tomorrow at ' . $consultationDateTime->format('H:i') . '.'
                )
            );*/
        }

        return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('Front-Office/consultation/edit.html.twig', [
        'consultation' => $consultation,
        'form' => $form,
        'user'=>$user
    ]);
}


// -------------------------------------------delete consultation-------------------------------------------
    #[Route('/{id}', name: 'app_consultation_delete', methods: ['POST'])]
    public function delete(Request $request, Consultation $consultation, ConsultationRepository $consultationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultation->getId(), $request->request->get('_token'))) {
            $consultationRepository->remove($consultation, true);
        }

        return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
    }

// -------------------------------------------download consultation en excel-----------------------

    #[Route('/consultation/export/csv', name: 'consultation_export_csv')]
    public function exportCsv(EntityManagerInterface $entityManager): Response
    {
        $consultations = $entityManager->getRepository(Consultation::class)->findAll();

        $array = [
            ['ID', 'Patient Name', 'Immatricule', 'Poids', 'Taille', 'Temperature', 'Pression Arterielle',
             'Frequence Cardiaque','Taux Glycemie', 'Symptomes', 'Traitement', 'Prix','Date'
             //,'Allergies', 'Code Ordonnance','Medicaments', 'Dosage', 'Nombre de Jours','Observation','ID Facture'
             ]
        ];
        foreach ($consultations as $consultation) {
            $csvData[] = [
                $consultation->getId(),
                $consultation->getRendezVous() ? $consultation->getRendezVous()->getPatient()->getNom() : 'MedCare',
                $consultation->getImc(),
                $consultation->getPoids(),
                $consultation->getTaille(),
                $consultation->getTemperature(),
                $consultation->getPressionArterielle(),
                $consultation->getFrequenceCardiaque(),
                $consultation->getTauxGlycemie(),
                $consultation->getmaladie(),
                $consultation->getTraitement(),
                $consultation->getPrix(),//  ? to chek if get... is not null
                $consultation->getRendezVous() ? $consultation->getRendezVous()->getDate()->format('Y-m-d H:i:s') : (new \DateTime())->format('Y-m-d H:i:s'),
                //$consultation->getFicheMedicale()-> getAllergies(),
                //$consultation->getOrdonnance()-> getCodeOrdonnance(),
                //$consultation->getOrdonnance()-> getMedicaments(),
                //$consultation->getOrdonnance()-> getDosage(),
                //$consultation->getOrdonnance()-> getNombreJours(),                
                //$consultation->getObservation()
                //$consultation->getOrdonnance()-> getFacture()-> getId()
            ];
        }        
        $data = $csvData;
       
/*
        $csvData = [
            ['ID', 'Patient Name', 'Date', 'Prix']
        ];
        foreach ($consultations as $consultation) {
            $rowData = [
                $consultation->getId(),
                $consultation->getRendezVous() ? $consultation->getRendezVous()->getPatient()->getNom() : 'MedCare',
                $consultation->getRendezVous() ? $consultation->getRendezVous()->getDate()->format('Y-m-d H:i:s') : (new \DateTime())->format('Y-m-d H:i:s'),
                //$consultation->getDuree(),
                $consultation->getPrix()
            ];
            $csvData[] = $rowData; //This will create a new variable $rowData and assign the value to it.
        }   
         i will change it to this code later when the merge happen
        foreach ($consultations as $consultation) {
            $csvData[] = [
                $consultation->getId(),
                //$consultation->getRendezVous()->getPatient()->getNom(),
                $consultation->getRendezVous()->getDate()->format('Y-m-d H:i:s'),
                //$consultation->getDuree(),
                $consultation->getPrix()
            ];
        }
*/
        $response = new Response($this->arrayToCsv($array, $data));
        $filename = 'consultations' . date('Ymd_His') . '.csv'; // e.g. consultations20230222.csv
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $filename
        );
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    
    //this function will replace the csv file to excel 
    private function arrayToCsv(array $array, array $data): string

    {
    $spreadsheet = new Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();

    // Add headers to the worksheet
    $headers = array_shift($array);
    foreach ($headers as $col => $header) {
        $cell = chr(65+$col).'1';
        $worksheet->setCellValue($cell, $header);
    }  

    // Add data
    foreach ($data as $rowIndex => $row) {
        foreach ($row as $col => $cell) {
            $cell = $cell ?? ''; // Set empty cells to an empty string
            $cellIndex = chr(65+$col).($rowIndex+2); // Increment row index by 2 to account for headers
            $worksheet->setCellValue($cellIndex, $cell);
        }
    } 

    // Save to a file
    $writer = new Xlsx($spreadsheet);
    $tempFile = tmpfile();
    $filePath = stream_get_meta_data($tempFile)['uri'];
    $writer->save($filePath);

    // Read the file contents and return them
    $csv = file_get_contents($filePath);
    fclose($tempFile);
    return $csv;
}





}
