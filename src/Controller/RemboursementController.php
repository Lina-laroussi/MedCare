<?php

namespace App\Controller;

use App\Entity\FicheAssurance;
use App\Entity\Remboursement;
use App\Form\RemboursementType;
use App\Repository\FicheAssuranceRepository;
use App\Repository\RemboursementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/remboursement')]
class RemboursementController extends AbstractController
{
    #[Route('/', name: 'app_remboursement_index', methods: ['GET'])]
    public function index(RemboursementRepository $remboursementRepository): Response
    {
        return $this->render('remboursement/index.html.twig', [
            'remboursements' => $remboursementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_remboursement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RemboursementRepository $remboursementRepository): Response
    {
        $remboursement = new Remboursement();
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $remboursementRepository->save($remboursement, true);

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





    #[Route('/{idRemboursement}/{idassurance}',name:'idRemboursement')]
    public function assignfichetoretuyèmboursement($idRemboursement,$idassurance, RemboursementRepository $remRepo,FicheAssuranceRepository $ficheRepo)
    {
    $ficheassurance=$remRepo->find($idassurance);
    $remboursement=$remRepo->find($idRemboursement);
    $remboursement->setFicheAssurance($ficheassurance);
    return $this->render ('test.html.twig');

    }
    
    #[Route('/a/{remboursementid}',name:'remboursementid')]
    public function showe(Remboursement $remboursementid)
    {
    return $this->render('test.html.twig', [
        'remboursementid' => $remboursementid,
    ]);
    }

    #[Route('/{idRemboursement}/ficheassurance/{ficheId}',name:'assign_remboursement_to_ficheassurance')]
    public function assignfichetoremboursement($idRemboursement, $ficheId)
{
    // Retrieve the user and profile objects from the database
    $entityManager = $this->getDoctrine()->getManager();
    

    $remboursement = $entityManager->getRepository(Remboursement::class)->find($idRemboursement);
    if ($remboursement) {
    $ficheassurance = $entityManager->getRepository(FicheAssurance::class)->find($ficheId);

    // Assign the profile to the user
    $remboursement->setFicheAssurance($ficheassurance);

    // Save the changes to the database
    $entityManager->persist($remboursement);
    $entityManager->flush();
}
    // Redirect the user back to the page where they came from
    return $this->redirectToRoute('remboursementid', ['remboursementId' => $idRemboursement]);
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


