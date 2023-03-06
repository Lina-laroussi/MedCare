<?php

namespace App\Controller;

use App\Entity\FicheMedicale;
use App\Form\FicheMedicaleType;
use App\Repository\FicheMedicaleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ficheMedicale')]
class FicheMedicaleController extends AbstractController
{
// ----------------------------afficher tous les fiche med--------------------------------------------
    #[Route('/show/{page?1}/{nbre?4}', name: 'app_fiche_medicale_index', methods: ['GET'])]
    public function index(FicheMedicaleRepository $ficheMedicaleRepository, $nbre,$page ): Response
    {
        $nbFich = $ficheMedicaleRepository->countFichMed();
        $nbrePage = ceil((int)$nbFich/(int)$nbre) ;
        return $this->render('Front-Office/ficheMedicale/ficheMedicale.html.twig', [
            'fiche_medicales' => $ficheMedicaleRepository->findTous($page,$nbre),
            'isPaginated'=>true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre
        ]);
    }

// -------------------------------Ajout/create new fiche med---------------------------------------------------
    #[Route('/new', name: 'app_fiche_medicale_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FicheMedicaleRepository $ficheMedicaleRepository): Response
    {
        $ficheMedicale = new FicheMedicale();
        $form = $this->createForm(FicheMedicaleType::class, $ficheMedicale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheMedicaleRepository->save($ficheMedicale, true);

            return $this->redirectToRoute('app_fiche_medicale_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Front-Office/ficheMedicale/new.html.twig', [
            'fiche_medicale' => $ficheMedicale,
            'form' => $form,
        ]);
    }

// ----------------------------------------afficher les fiche med par id-----------------------------------
    #[Route('/{id}', name: 'app_fiche_medicale_show', methods: ['GET'])]
    public function show(FicheMedicale $ficheMedicale): Response
    {
        return $this->render('Front-Office/ficheMedicale/show.html.twig', [
            'fiche_medicale' => $ficheMedicale,
        ]);
    }

// ----------------------------------------modifier fiche med--------------------------------------------
    #[Route('/{id}/edit', name: 'app_fiche_medicale_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FicheMedicale $ficheMedicale, FicheMedicaleRepository $ficheMedicaleRepository): Response
    {
        $form = $this->createForm(FicheMedicaleType::class, $ficheMedicale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheMedicaleRepository->save($ficheMedicale, true);

            return $this->redirectToRoute('app_fiche_medicale_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Front-Office/ficheMedicale/edit.html.twig', [
            'fiche_medicale' => $ficheMedicale,
            'form' => $form,
        ]);
    }
// ----------------------------------------delete fiche med----------------------------------------------
    #[Route('/{id}', name: 'app_fiche_medicale_delete', methods: ['POST'])]
    public function delete(Request $request, FicheMedicale $ficheMedicale, FicheMedicaleRepository $ficheMedicaleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ficheMedicale->getId(), $request->request->get('_token'))) {
            $ficheMedicaleRepository->remove($ficheMedicale, true);
        }

        return $this->redirectToRoute('app_fiche_medicale_index', [], Response::HTTP_SEE_OTHER);
    }
}
