<?php

namespace App\Controller;

use App\Entity\FicheAssurance;
use App\Entity\Remboursement;
use App\Form\FileSearch;
use App\Form\FileType;
use App\Form\RemboursementType;
use App\Repository\FicheAssuranceRepository;
use App\Repository\RemboursementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use \Swift_Mailer;

#[Route('/remboursement')]
class RemboursementController extends AbstractController
{
    /*
    #[Route('/', name: 'app_remboursement_index', methods: ['GET'])]
    public function index(RemboursementRepository $remboursementRepository): Response
    {
        return $this->render('remboursement/index.html.twig', [
            'remboursements' => $remboursementRepository->findAll(),
        ]);
    }
 */


 #[Route('/', name: 'app_remboursement_index', methods: ['GET'])]
 public function index(RemboursementRepository $RemboursementRepository , Request $request, PaginatorInterface $paginator,FlashyNotifier $Flashy): Response
 {
     $entityManager = $this->getDoctrine()->getManager();
 
     // récupérer les paramètres de la requête
     $limit = $request->query->getInt('limit', 99);
     $page = $request->query->getInt('page', 1);
     $q = $request->query->get('q');
     
     // construire la requête pour récupérer les ficheAssurances
     $queryBuilder = $RemboursementRepository->createQueryBuilder('t');
     $queryBuilder->orderBy('t.id', 'ASC');
     if ($q) {
         $queryBuilder->Where('t.etat LIKE :q')
         ->orWhere('t.id LIKE :q')
                      
        
         ->setParameter('q', '%'.$q.'%');
         
     }
     $query = $queryBuilder->getQuery();
 
     // paginer les résultats
     $pagination = $paginator->paginate(
         $query,
         $page,
         $limit
     );
 
     return $this->render('remboursement/index.html.twig', [
         'remboursements' => $pagination,
         'q' => $q,
     ]);
 }

    #[Route('/new', name: 'app_remboursement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RemboursementRepository $remboursementRepository,FlashyNotifier $flashy ): Response
    {
        $remboursement = new Remboursement();
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $remboursementRepository->save($remboursement, true);
            // Add success flash message
            $flashy->success("La fiche assurance a été créée avec succès", '');
            return $this->redirectToRoute('app_remboursement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('remboursement/new.html.twig', [
            'remboursement' => $remboursement,
            'form' => $form,
        ]);
    } 
 

    #[Route('/{id}', name: 'app_remboursement_show', methods: ['GET'])]
    public function show(Remboursement $remboursement): Response
    {
        return $this->render('remboursement/show.html.twig', [
            'remboursement' => $remboursement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_remboursement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Remboursement $remboursement, RemboursementRepository $remboursementRepository): Response
    {
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $remboursementRepository->save($remboursement, true);

            return $this->redirectToRoute('app_remboursement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('remboursement/edit.html.twig', [
            'remboursement' => $remboursement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_remboursement_delete', methods: ['POST'])]
    public function delete(Request $request, Remboursement $remboursement, RemboursementRepository $remboursementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$remboursement->getId(), $request->request->get('_token'))) {
            $remboursementRepository->remove($remboursement, true);
        }

        return $this->redirectToRoute('app_remboursement_index', [], Response::HTTP_SEE_OTHER);
    }


////////////////////////////////// rechercher par DATE ///////////////////////
    #[Route('/file/date', name: 'file_list', methods: ['GET', 'POST'],)]
    public function filterDate(Request $request,RemboursementRepository $Repo ,PaginatorInterface $paginator)
    {

         $search = new FileSearch();
        $form = $this->createForm(FileType::class, $search);
        $form->handleRequest($request);

        $files = [];

        $entityManager = $this->getDoctrine()->getManager();
        $queryBuilder = $Repo ->createQueryBuilder('f');
        if ($form->isSubmitted() && $form->isValid()) {
            $startDate = $search->startDate;
            $endDate = $search->endDate;
          
            $queryBuilder
                ->where('f.date_remboursement BETWEEN :start_date AND :end_date')
                ->setParameter('start_date', $startDate)
                ->setParameter('end_date', $endDate);
               
        }

        $files = $queryBuilder->getQuery()->getResult();
         
        return $this->render('remboursement/file.html.twig', [
            'form' => $form->createView(),
            'files' => $files,
        ]); 
        
    }


    ////////////////////////////////////changer etat ////////////////////////////
    #[Route('/{id}/confirm', name: 'app_confirm_remboursemet', methods: ['GET', 'POST'])]
    public function confirm(Request $request, Remboursement $Remboursement, RemboursementRepository $RemboursementRepository,HubInterface $hub,$id): Response
    {

        $Remboursement->setEtat("confirmé");
        $RemboursementRepository->save($Remboursement, true);
        $data=[

            'DateRemboursement' => $Remboursement->getDateRemboursement()->format('Y-m-d'),
            'MontantARembourser'=>$Remboursement->getMontantARembourser(),
            'MontantMaximale'=>$Remboursement->getMontantMaximale(),
            'TauxRemboursement'=>$Remboursement->getTauxRemboursement(),
            'Etat'=>$Remboursement->getEtat(),
            'idFicheAssurance'=>$Remboursement->getFicheAssurance(),
        ];
       
       
      
   


        return $this->redirectToRoute('app_remboursement_index', [], Response::HTTP_SEE_OTHER);
    }
























}

   /* #[Route('/findmoyenne/{id}', name: 'find_moyenne')]
    public function  findMoyenne( ClassroomRepository $repo ,$id   ) : Response
    {
        $classroom =$repo->find($id);
        $moyenne= $repo->findStudentByClassAVG($classroom->getName());
        return $this->render('classroom/find.html.twig', [
            "classroom" => $classroom,
            "moyenne"=>$moyenne]);
    }
*/

