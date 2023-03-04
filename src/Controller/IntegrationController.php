<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Mime;
use App\Service\MailerService;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Attachment;
use App\Entity\Pharmacie;
use App\Form\PharmacieType;
use App\Repository\PharmacieRepository;
use Doctrine\ORM\EntityManagerInterface;




class IntegrationController extends AbstractController
{
    #[Route('/integration', name: 'app_integration')]
    public function index(): Response
    {
        return $this->render('Front-Office/Landing.html.twig', [
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

    #[Route('/admin', name: 'app_integration2')]
    public function admin(): Response
    {
        return $this->render('Back-Office/DashboardAdmin.html.twig', [
            'controller_name' => 'IntegrationController',
        ]);
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
        
        return $this->render('pharmacie/pharmacy-search.html.twig', [
            'controller_name' => 'IntegrationController',
            'pharmacies' => $pharmacieRepository->findAll(),
 
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
    public function sendEmail(MailerService $mailer )
    {   $mailer->sendEmail(from:'pharmaciemedcare@gmail.com',to:'feryelouerfelli@gmail.com' , content:'votre facture',subject: 'Facture Pharmacie', tmpFile:'document.pdf');
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
