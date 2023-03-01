<?php

namespace App\Controller;


use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }
    
    ///pour le Front 

    #[Route('/index1', name: 'app_produit_index1', methods: ['GET'])]
    public function index1(Request $request, ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        // Get the category ID from the request parameters
        $categorieId = $request->query->get('categorie');
    
        if (!$categorieId) {
            // If no category ID is specified, redirect to the category index page
            return $this->redirectToRoute('app_categorie_index');
        }
    
        // Get the category by ID
        $categorie = $categorieRepository->find($categorieId);
    
        if (!$categorie) {
            // If the category is not found, display an error message
            throw $this->createNotFoundException(sprintf('Category with ID %s not found', $categorieId));
        }
    
        // Get the products associated with the category
        $produits = $produitRepository->findByCategorie($categorie);
    
        return $this->render('produit/index1.html.twig', [
            'categorie' => $categorie,
            'produits' => $produits,
        ]);
    }
    


    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
// Get the uploaded file
$file = $form['image']->getData();

// Generate a unique name for the file before saving it
$fileName = md5(uniqid()) . '.' . $file->guessExtension();

// Move the file to the directory where images are stored
$file->move(
    $this->getParameter('product_images_directory'),
    $fileName
);

// Update the filename property of the produit entity
$produit->setImage($fileName);

// Save the produit entity to the database
$produitRepository->save($produit, true);

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        // lier les données de la requête HTTP aux champs du formulaire correspondant.
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

// Get the uploaded file
$file = $form['image']->getData();

if ($file) {
    // Generate a unique name for the file before saving it
    $fileName = md5(uniqid()) . '.' . $file->guessExtension();

    // Move the file to the directory where images are stored
    $file->move(
        $this->getParameter('product_images_directory'),
        $fileName
    );

    // Update the filename property of the produit entity
    $produit->setImage($fileName);
}
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        // Ce code vérifie si le jeton CSRF (Cross-Site Request Forgery) soumis avec la requête est valide,
        //  avant de supprimer un produit de la base de données.
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

       
    }