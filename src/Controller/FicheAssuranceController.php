<?php

namespace App\Controller;

use App\Entity\FicheAssurance;
use App\Form\FicheAssuranceType;
use App\Repository\FicheAssuranceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/fiche/assurance')]
class FicheAssuranceController extends AbstractController
{
 /*   #[Route('/', name: 'app_fiche_assurance_index', methods: ['GET'])]
    public function index(FicheAssuranceRepository $ficheAssuranceRepository  ,PaginatorInterface $paginator ,Request $request): Response
    {

 // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
 $donnees = $this->getDoctrine()->getRepository(FicheAssurance::class)->findAll();
 $fiche_assurances = $paginator->paginate(
     $donnees, // Requête contenant les données à paginer (ici nos articles)
     $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
     10 // Nombre de résultats par page
 );


        return $this->render('fiche_assurance/index.html.twig', [
            'fiche_assurances' => $fiche_assurances,
        ]);
    }
*/
#[Route('/', name: 'app_fiche_assurance_index', methods: ['GET'])]
    public function index(FicheAssuranceRepository $ficheAssuranceRepository , Request $request, PaginatorInterface $paginator,FlashyNotifier $Flashy): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
    
        // récupérer les paramètres de la requête
        $limit = $request->query->getInt('limit', 5);
        $page = $request->query->getInt('page', 1);
        $q = $request->query->get('q');
        
        // construire la requête pour récupérer les ficheAssurances
        $queryBuilder = $ficheAssuranceRepository->createQueryBuilder('t');
        $queryBuilder->orderBy('t.id', 'ASC');
        if ($q) {
            $queryBuilder->andWhere('t.etat LIKE :q')
           
            ->setParameter('q', '%'.$q.'%');
            
        }
        $query = $queryBuilder->getQuery();
    
        // paginer les résultats
        $pagination = $paginator->paginate(
            $query,
            $page,
            $limit
        );
    
        return $this->render('fiche_assurance/ficheassurancetri.html.twig', [
            'fiche_assurances' => $pagination,
            'q' => $q,
        ]);
    }

    #[Route('/fichesqassurance2', name: 'fiche_assurance_index', methods: ['GET'])]
    public function index2(FicheAssuranceRepository $repository,ChartBuilderInterface $chartBuilder): Response
    {

        $results = $repository->findAll();

        $labels=[];
        $data=[];
        
        foreach($results as $result){
            $labels[] = $result -> getDateCreation()->format('d/m/y');
            $data[] = $result -> getEtat();
        } 
        

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            
            'labels' => $labels,
            'datasets' => [
                [
                    
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $data,
                ],
            ],
        ]);
        
        $chart->setOptions([]);



        return $this->render('fiche_assurance/index.html.twig', [
            'fiche_assurances' => $repository->findAll(),
            'controller_name' => 'ChartjsController',
            'chart' => $chart,
        ]);
    }


 












#[Route('/fiches-assurance2', name: 'fiche_assurance_indexq')]
public function exampleAction(EntityManagerInterface $entityManager)
{
    $fiches = $entityManager->createQueryBuilder()
        ->select('f.num_adherent,f.description,f.date_creation,f.image_facture,f.etat')
        ->from(FicheAssurance::class, 'f')
        ->where('f.etat = :etat')
        ->setParameter('etat', 'Non confirmé')
        ->groupBy('f.etat')
        ->getQuery()
        ->getResult();


    return $this->render('fiche_assurance/groupedby.html.twig', [
        'fiches' => $fiches,
    ]);
}





    #[Route('/new', name: 'app_fiche_assurance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FicheAssuranceRepository $ficheAssuranceRepository,FlashyNotifier $flashy): Response
    {
        $ficheAssurance = new FicheAssurance();
        $form = $this->createForm(FicheAssuranceType::class, $ficheAssurance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $ficheAssuranceRepository->save($ficheAssurance, true);
          
            // Add success flash message
            $flashy->success("La fiche assurance a été créée avec succès", '');

            return $this->redirectToRoute('app_fiche_assurance_index', [], Response::HTTP_SEE_OTHER);
 
        }
        return $this->renderForm('fiche_assurance/new.html.twig', [
            'fiche_assurance' => $ficheAssurance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_assurance_show', methods: ['GET'])]
    public function show(FicheAssurance $ficheAssurance): Response
    {
        return $this->render('fiche_assurance/show.html.twig', [
            'fiche_assurance' => $ficheAssurance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fiche_assurance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FicheAssurance $ficheAssurance, FicheAssuranceRepository $ficheAssuranceRepository): Response
    {
        $form = $this->createForm(FicheAssuranceType::class, $ficheAssurance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheAssuranceRepository->save($ficheAssurance, true);

            return $this->redirectToRoute('app_fiche_assurance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fiche_assurance/edit.html.twig', [
            'fiche_assurance' => $ficheAssurance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_assurance_delete', methods: ['POST'])]
    public function delete(Request $request, FicheAssurance $ficheAssurance, FicheAssuranceRepository $ficheAssuranceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ficheAssurance->getId(), $request->request->get('_token'))) {
            $ficheAssuranceRepository->remove($ficheAssurance, true);
        }

        return $this->redirectToRoute('app_fiche_assurance_index', [], Response::HTTP_SEE_OTHER);
    }





///////////////////////////////////////////////////////////////////////////MOBILE
#[Route('/json', name: 'app_fiche_assurance_index_json')]
public function app_fiche_assurance_index_json(FicheAssuranceRepository $repo, SerializerInterface $serializer){
    $fichesAssurances=$repo->findAll();    
    $json=$serializer->serialize($fichesAssurances, 'json', ['grqoups'=>"ficheAssurance"]);
    return new Response($json);
}

#[Route("/json/{id}", name: "app_fiche_assurance_index_id_json")]
public function app_fiche_assurance_index_id_json($id, NormalizerInterface $normalizer, FicheAssuranceRepository $repo)
{
    $ficheAssurance = $repo->find($id);
    $ficheAssuranceNormalises = $normalizer->normalize($ficheAssurance, 'json', ['groups' => "ficheAssurance"]);
    return new Response(json_encode($ficheAssuranceNormalises));
}


    #[Route("/json/new", name: "app_fiche_assurance_json_new")]
    public function addFicheAssuranceJSON(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $FicheAssurance = new FicheAssurance();
     
        $FicheAssurance->setNumAdherent($req->get('NumAdherent'));
        $FicheAssurance->setDescription($req->get('Description'));
        $FicheAssurance->setDateCreation($req->get('DateCreation'));
        $FicheAssurance->setImageFacture($req->get('ImageFacture'));
        $FicheAssurance->setEtat($req->get('Etat'));

        $em->persist($FicheAssurance);
        $em->flush();

        $jsonContent = $Normalizer->normalize($FicheAssurance, 'json', ['groups' => 'students']);
        return new Response(json_encode($jsonContent));
    }





    
}
?>