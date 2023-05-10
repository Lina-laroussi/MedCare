<?php

namespace App\Controller;

use App\Entity\Pharmacie;
use App\Form\PharmacieType;
use App\Repository\PharmacieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Attachment;
use Symfony\Component\HttpFoundation\JsonResponse;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/pharmaciejson')]
class PharmacieJsonController extends AbstractController
{
    #[Route('/show', name: 'app_pharmaciejson_index', methods: ['GET'])]
    public function index(PharmacieRepository $pharmacieRepository ,NormalizerInterface $Normalizer , SerializerInterface $serializer): Response
    {
        $pharmacies = $pharmacieRepository->findAll();
        $pharmaciesJson=$serializer->serialize($pharmacies, 'json', ['groups'=>"pharmacies"]);
        return new Response($pharmaciesJson);
    }




    #[Route('/new', name: 'app_pharmaciejson_new', methods: ['GET', 'POST'])]
    public function new(Request $req, PharmacieRepository $pharmacieRepository , NormalizerInterface $Normalizer , SerializerInterface $serializer): Response
    {
        $pharmacie = new Pharmacie();

        $nom = $req->get('nom') ?? ''; 
        $pharmacie->setNom($nom);

     

        $adresse = $req->get('adresse') ?? ''; 
        $pharmacie->setAdresse($adresse);
        $Gouvernorat = $req->get('gouvernorat') ?? ''; 
        $pharmacie->setGouvernorat($Gouvernorat);


        $email= $req->get('email') ?? ''; 
        $pharmacie->setEmail($email);
 
        $etat= $req->get('etat') ?? ''; 
        $pharmacie->setEtat($etat);

        $num_tel= $req->get('num_tel') ?? ''; 
        $pharmacie->setNumTel($num_tel);


        $matricule = $req->get('matricule') ?? ''; 
        $pharmacie->setMatricule($matricule);

        $horaire= $req->get('horaire') ?? ''; 
        $pharmacie->setHoraire($horaire);
        $description= $req->get('description') ?? ''; 
        $pharmacie->setDescription($description); 

        $services = $req->get('services') ?? ''; 
        $pharmacie->setServices($services);


        $pharmacieRepository->save($pharmacie, true);
        $jsonContent = $Normalizer->normalize($pharmacie, 'json', ['groups' => 'pharmacies']);
        return new Response(json_encode($jsonContent));
      
    }
    #[Route('/update', name: 'app_pharmaciejson_edit', methods: ['GET', 'POST'])]
    {    public function edit(Request $request, Pharmacie $pharmacie, PharmacieRepository $pharmacieRepository , NormalizerInterface $Normalizer): Response

        $id = $request->get('id');
        $pharmacie = $pharmacieRepository->find($id);
        $pharmacie->setNom($request->get('nom'));
        $pharmacie->setAdresse($request->get('adresse'));
        $pharmacie->setGouvernorat($request->get('Gouvernorat'));
        $pharmacie->setEmail($request->get('email'));
        $pharmacie->setEtat($request->get('etat'));
        $pharmacie->setNumTel($request->get('num_tel'));
        $pharmacie->setMatricule($request->get('matricule'));
        $pharmacie->setHoraire($request->get('horaire'));
        $pharmacie->setDescription($request->get('description')); 
        $pharmacie->setServices($request->get('services'));
        $pharmacieRepository->save($pharmacie, true);
        $jsonContent = $Normalizer->normalize($pharmacie, 'json', ['groups' => 'pharmacies']);
        return new Response(json_encode($jsonContent));


    }
}



    /*
    #[Route('/{id}', name: 'app_pharmaciejson_show', methods: ['GET'])]
    public function show(Pharmacie $pharmacie , NormalizerInterface $Normalizer): Response
    {
        return $this->render('pharmacie/show.html.twig', [
            'pharmacie' => $pharmacie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pharmaciekson_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pharmacie $pharmacie, PharmacieRepository $pharmacieRepository , NormalizerInterface $Normalizer): Response
    {
        $form = $this->createForm(PharmacieType::class, $pharmacie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pharmacieRepository->save($pharmacie, true);

            return $this->redirectToRoute('app_pharmaciejson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pharmacie/edit.html.twig', [
            'pharmacie' => $pharmacie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciejson_delete', methods: ['POST'])]
    public function delete(Request $request, Pharmacie $pharmacie, PharmacieRepository $pharmacieRepository , NormalizerInterface $Normalizer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pharmacie->getId(), $request->request->get('_token'))) {
            $pharmacieRepository->remove($pharmacie, true);
        }

        return $this->redirectToRoute('app_pharmaciejson_index', [], Response::HTTP_SEE_OTHER);
    }


}

    //$jsonUser = $serializer->serialize($user, 'json');

  /*  
  
  
  
  
  #[Route('/tri', name: 'app_pharmacie_tri')]
   public function tripharmacie(PharmacieRepository $pharmacieRepository)
{
    $pharmacies  = $pharmacieRepository->tri();
    return $this->render('pharmacie/index.html.twig', [
        'pharmacies' => $pharmacies,
    ]);
}
 
*/
/*#[Route('/tri', name: 'app_pharmacie_triii')]

    public function Tri(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        

        $query = $em->createQuery(
            'SELECT a FROM App\Entity\Pharmacie a 
            ORDER BY a.nom ASC' 
        );
        
        $pharmacies = $query->getResult(); 
        
        

        return $this->render('Back-Office/pharmacie/index.html.twig', 
        array('pharmacies' => $pharmacies));
    
    }
    */







  






















  

