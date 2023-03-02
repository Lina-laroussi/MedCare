<?php

namespace App\Controller; 

use App\Entity\Consultation;
use App\Form\Consultation1Type;
use App\Repository\ConsultationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

#[Route('/consultation')]
class ConsultationController extends AbstractController
{
// afficher tous les consultation
    #[Route('/', name: 'app_consultation_index', methods: ['GET'])]
    public function index(ConsultationRepository $consultationRepository): Response
    {
        return $this->render('Front-Office/consultation/consultation.html.twig', [
            'consultations' => $consultationRepository->findAll(),
        ]);
    }

// ajouter/créer consultation
    #[Route('/new', name: 'app_consultation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ConsultationRepository $consultationRepository): Response
    {
        $consultation = new Consultation();
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
            $authToken = '20227668e51bcd646b9e534b6b3405e1';
            $twilio = new Client($accountSid, $authToken); 
 
            $message = $twilio->messages 
                            ->create($patientPhoneNumber, // to : Patient phone number 
                                    array(  
                                        "messagingServiceSid" => "MG35ecb99459fed44b9da5124d3041ece9",      
                                        "body" => "Rappel : Vous avez une consultation demain à ". $consultationDateTime->format('H:i') . '.' 
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

        return $this->renderForm('Front-Office/consultation/new.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
        ]);
    }

// afficher consultation par id
    #[Route('/{id}', name: 'app_consultation_show', methods: ['GET'])]
    public function show(Consultation $consultation): Response
    {
        return $this->render('Front-Office/consultation/show.html.twig', [
            'consultation' => $consultation,
        ]);
    }

// modifier consultation
    #[Route('/{id}/edit', name: 'app_consultation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Consultation $consultation, ConsultationRepository $consultationRepository): Response
    {
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
            $authToken = '20227668e51bcd646b9e534b6b3405e1';
            $twilio = new Client($accountSid, $authToken); 
 
            $message = $twilio->messages 
                            ->create($patientPhoneNumber, // to : Patient phone number 
                                    array(  
                                        "messagingServiceSid" => "MG35ecb99459fed44b9da5124d3041ece9",      
                                        "body" => "Rappel : Vous avez une consultation demain à ". $consultationDateTime->format('H:i') . '.' 
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
    ]);
}


// delete consultation
    #[Route('/{id}', name: 'app_consultation_delete', methods: ['POST'])]
    public function delete(Request $request, Consultation $consultation, ConsultationRepository $consultationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultation->getId(), $request->request->get('_token'))) {
            $consultationRepository->remove($consultation, true);
        }

        return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
    }


    

}
