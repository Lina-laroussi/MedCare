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


#[Route('/pharmacie')]
class PharmacieController extends AbstractController
{
    #[Route('/show/{page?1}/{nbre?2}', name: 'app_pharmacie_index', methods: ['GET'])]
    public function index(PharmacieRepository $pharmacieRepository ,$nbre, $page): Response
    { 
        $nbrePharmacies = $pharmacieRepository->countPharmacies();
        $nbrePage = ceil($nbrePharmacies / $nbre) ;

        return $this->render('pharmacie/index.html.twig', [
            
     
        
            'pharmacies' => $pharmacieRepository->findTous($nbre,$page),
           'isPaginated' => true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre
        ]);
    }
    #[Route('/searchlist', name: 'app_pharmacie_s', methods: ['GET'])]
    public function searchph(PharmacieRepository $pharmacieRepository): Response
    {
        return $this->render('pharmacie/listsearchpharmacie.html.twig', [
            'pharmacies' => $pharmacieRepository->findAll(),
        ]);
    }
    #[Route('/detailpharmacie/{id}', name: 'app_pharmacie_details', methods: ['GET'])]
    public function  detailspharmacie(Pharmacie $pharmacie): Response
    {
        return $this->render('pharmacie/detailsearch.html.twig', [
            'pharmacie' => $pharmacie,
 
        ]);
    }
    #[Route('/new', name: 'app_pharmacie_new')]
    public function new(Request $request, PharmacieRepository $pharmacieRepository , FlashyNotifier $Flashy): Response
    {
        $pharmacie = new Pharmacie();
        $form = $this->createForm(PharmacieType::class, $pharmacie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pharmacieRepository->save($pharmacie, true);
            $Flashy->success("La pharmacie est ajoutée avec succées", '');

            return $this->redirectToRoute('app_pharmacie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pharmacie/new.html.twig', [
            'pharmacie' => $pharmacie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmacie_show', methods: ['GET'])]
    public function show(Pharmacie $pharmacie): Response
    {
        return $this->render('pharmacie/show.html.twig', [
            'pharmacie' => $pharmacie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pharmacie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pharmacie $pharmacie, PharmacieRepository $pharmacieRepository): Response
    {
        $form = $this->createForm(PharmacieType::class, $pharmacie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pharmacieRepository->save($pharmacie, true);

            return $this->redirectToRoute('app_pharmacie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pharmacie/edit.html.twig', [
            'pharmacie' => $pharmacie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmacie_delete', methods: ['POST'])]
    public function delete(Request $request, Pharmacie $pharmacie, PharmacieRepository $pharmacieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pharmacie->getId(), $request->request->get('_token'))) {
            $pharmacieRepository->remove($pharmacie, true);
        }

        return $this->redirectToRoute('app_pharmacie_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/tri/a', name: 'app_pharmacie_tri')]
    public function trier(EntityManagerInterface $entityManager)
        {
            $pharmacies = $entityManager->getRepository(Pharmacie::class)->createQueryBuilder('p')
                ->orderBy('p.nom', 'ASC')
                ->getQuery()
                ->getResult();
    
            return $this->render('Back-Office/pharmacie/indextri.html.twig', [
                'pharmacies' => $pharmacies,
            ]);
        }
    }
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







  






















  

