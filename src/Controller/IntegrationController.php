<?php

namespace App\Controller;

use App\Form\EditFormUserType;
use App\Form\ForgotPasswordFormType;
use App\Form\ResetPasswordFormType;
use App\Form\SearchFormType;
use App\Repository\UserRepository;
use App\Service\MailerService;
use App\Entity\Consultation;
use App\Repository\ConsultationRepository;
use App\Repository\OrdonnanceRepository;
use App\Service\MailerServicePharmacie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Mime;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Attachment;
use App\Entity\Pharmacie;
use App\Form\PharmacieType;
use App\Repository\PharmacieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;




class IntegrationController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('Front-Office/Landing.html.twig');
    }

//--------dashboard / stat:  number of medication per day = added per day in January-------------
    #[Route('/dashboard', name: 'app_consult')]
    public function dashboard(ConsultationRepository $consultationRepository, OrdonnanceRepository $ordonnanceRepository): Response
    {

        $user=$this->getUser();
        $totalConsultations = $consultationRepository->getTotalConsultations();
        $totalRevenus = $consultationRepository ->getTotalRevenus();
        $totalMedicaments = $ordonnanceRepository ->getTotalMedicament();

        return $this->render('Front-Office/dashboardDoc.html.twig', [
            'totalConsultations' => $totalConsultations,
            'TotalRevenus' => $totalRevenus,
            'TotalMedicament' => $totalMedicaments,
                'user'=>$user
        ]
        );

    }

    #[Route('/listMedecins/{page?1}/{nbre?5}', name: 'app_list_medecins')]
    public function listMedecins(UserRepository $repo,Request $req,$nbre,$page): Response
    {
        $user=$this->getUser();
        $nbMedecins = $repo->countUsersByRole('ROLE_MEDECIN');
        $nbrePage = ceil($nbMedecins / $nbre) ;
        $medecins = $repo->findUsersByRole($page,$nbre,'ROLE_MEDECIN');

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() ) {
            $searchTerm = $form->getData();
            $medecins = $repo->findUsersBySearchTerm($searchTerm);
        }
        return $this->render('Front-Office/profile/list-medecins.html.twig', [
            'controller_name' => 'ProfileController',
            'user'=>$user,
            'medecins'=>$medecins,
            'form'=>$form->createView(),
            'isPaginated'=>true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre
        ]);
    }

    #[Route('/connect', name: 'app_choose_profile')]
    public function profile(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_user_home');
        }
        return $this->render('Front-Office/choose_profile.html.twig', [
            'controller_name' => 'IntegrationController',
        ]);
    }

    #[Route('/detail', name: 'app_integration10')]
    public function detail(): Response
    {
        return $this->render('Front-Office/pharmacy-details1.html.twig', [
            'controller_name' => 'IntegrationController',
        ]);
    }




    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('Back-Office/login.html.twig');
    }
    #[Route('/searchpharmacie/{id}', name: 'app_integration4', methods: ['GET'])]
    public function  detailspharmacie(Pharmacie $pharmacie): Response
    {
        return $this->render('pharmacie/pharmacy-details.html.twig', [
            'pharmacie' => $pharmacie,
            'controller_name' => 'IntegrationController',

        ]);
    }
    #[Route('/searchpharmacie', name: 'app_integration3')]
    public function searchpharmacie(PharmacieRepository $pharmacieRepository): Response
    {

        $user=$this->getUser();
        return $this->render('pharmacie/pharmacy-search.html.twig', [
            'controller_name' => 'IntegrationController',
            'pharmacies' => $pharmacieRepository->findAll(),
            'user'=>$user

        ]);
    }

    #[Route('/searchpharmacieajax', name: 'app_searchpharmacieajax' )]

 public function searchpharmacieajax (Request $request)
    {
        $search =$request->get('info');
        $pharmacies =$this->getDoctrine()->getRepository(Pharmacie::class)->findName($search);
        $jsonData =array();
        $idx = 0 ;
        foreach($pharmacies as $pharmacie)
        {
              $temp = array(

                    //  'id' => $pharmacie->getId(),
                    'name' => $pharmacie->getNom(),
                    //'address' => $pharmacie->getAdresse(),
              );
              $jsonData[$idx++] = $temp ;
            }
            return new JsonResponse($jsonData) ;

        }



/*
#[Route('/ph', name: 'app_integration15')]

public function searchPharmacies(Request $request)
{
    $searchQuery = $request->request->get('search_query');
    $pharmacies = $this->getDoctrine()
        ->getRepository(Pharmacie::class)
        ->findNom($searchQuery); // replace findBySearchQuery with your custom repository method

    // create an array of data to return as JSON
    $data = [];
    foreach ($pharmacies as $pharmacie) {
        $data[] = [
            'id' => $pharmacie->getId(),
            'name' => $pharmacie->getNom(),
            'address' => $pharmacie->getAdresse(),
            // add other fields you want to return
        ];
    }

    return
    //new JsonResponse($data);
    $this->render('pharmacie/resultat.html.twig', [
        'controller_name' => 'IntegrationController',
    ]);
}

*/


    #[Route('/send', name: 'app_send')]
    public function sendEmail(MailerServicePharmacie $mailer )
    {         //    $mailer->sendEmail(from:$facture->getPharmacie()->getEmail(),to:$facture->getOrdonnance()->getConsultation()->getRendezvous()->getPatient()->getEmail(),subject: 'Facture Pharmacie',  template :'template' , tmpFile:'document.pdf');

         $mailer->sendEmail(from:'pharmaciemedcare@gmail.com',to:'feryelouerfelli@gmail.com' ,subject: 'Facture Pharmacie', tmpFile:'document.pdf' , htmltemplate:'template' , context:[facture]);
        return new Response("Success");
    }


    /*#[Route('/search', name: 'ajax_search')]

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $posts =  $em->getRepository('AppBundle:Post')->findEntitiesByString($requestString);
        if(!$posts) {
            $result['posts']['error'] = "Post Not found :( ";
        } else {
            $result['posts'] = $this->getRealEntities($posts);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($posts){
        foreach ($posts as $posts){
            $realEntities[$posts->getId()] = [$posts->getPhoto(),$posts->getTitle()];

        }
        return $realEntities;
    }*/

}
