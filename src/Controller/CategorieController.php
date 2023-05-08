<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/categorie')]
class CategorieController extends AbstractController
{
    #[Route('/', name: 'app_categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository ,PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $categorieRepository->findAll();
        $pagination = $paginator->paginate(
            $categories,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('categorie/index.html.twig', [
            'pagination' => $pagination,
            'categories' => $pagination,
        ]);
    }

    #[Route('/indexC', name: 'app_categorie_indexC' , methods: ['GET'])]
    public function indexC(CategorieRepository $categorieRepository): Response
    {
        $user=$this->getUser();
        return $this->render('categorie/indexC.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'user'=>$user
        ]);
    }

    #[Route('/new', name: 'app_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieRepository $categorieRepository): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->save($categorie, true);

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->save($categorie, true);

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $categorieRepository->remove($categorie, true);
        }

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/chercherr', name: 'app_categorie_chercher', methods: ['GET'])]
    public function chercherParNom(Request $request, CategorieRepository $CategorieRepository): Response
    {
        $nom = $request->query->get('nom');
        $categories = $CategorieRepository->findByNom($nom);

        return $this->render('categorie/chercher.html.twig', [
            'nom' => $nom,
            'categories' => $categories,
        ]);
    }


    #[Route('/chercheretat', name: 'app_categorie_chercher_etat', methods: ['GET'])]
    public function chercheretat(Request $request, CategorieRepository $CategorieRepository): Response
    {
        $etat = $request->query->get('etat');
        $categories = $CategorieRepository->findByEtat($etat);

        return $this->render('categorie/chercher.html.twig', [
            'etat' => $etat,
            'categories' => $categories,
        ]);
    }













    //__________////////________________EN JSON _______________///////////




    //https://localhost:8000/AllCategorie
    #[Route("/AllCategorie", name: "AllCategorie")]
    public function AllCategories(CategorieRepository $repo, SerializerInterface $serializer)
    {
        $categories = $repo->findAll();
        $json = $serializer->serialize($categories, 'json', ['groups' => "categories"]);

        return new Response($json);
    }


    //https://127.0.0.1:8000/Categorie/1/detail
    #[Route("getCategoriedetail", name: "getCategoriedetail")]
    public function getCategoriedetail($id, NormalizerInterface $normalizer, CategorieRepository $repo)
    {
        $categories = $repo->find($id);
        $CategorieNormalise = $normalizer->normalize($categories, 'json', ['groups' => "categories"]);
        return new Response(json_encode($CategorieNormalise));
    }

//https://localhost:8000/addCategorie/new
    #[Route("addCategorie/new", name: "addCategorie")]
    public function addCategorie(Request $req, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $Categorie = new Categorie();
        $nom = $req->get('nom') ?? ''; // set default value to an empty string if $nom is null
        $Categorie->setNom($nom);
        $description = $req->get('description') ?? ''; // set default value to an empty string if $description is null
        $Categorie->setDescription($description);
        $etat = $req->get('etat') ?? 'disponible'; // set default value to 'pending' if $etat is null
        $Categorie->setEtat($etat);
        $marque = $req->get('marque') ?? 0.0; // set default value to 0.0 if $marque is null
        $Categorie->setMarque(floatval($marque));
        $groupe_age = $req->get('groupe_age') ?? ''; // set default value to an empty string if $nom is null
        $Categorie->setGroupeAge($groupe_age);
        $em->persist($Categorie);
        $em->flush();

        $jsonContent = $Normalizer->normalize($Categorie, 'json', ['groups' => 'Categories']);
        return new Response(json_encode($jsonContent));
    }

    #[Route("updateCategorie/{id}", name: "update_Categorie")]
    public function updateCategorie(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $Categorie = $em->getRepository(Categorie::class)->find($id);
        $nom = $req->get('nom') ?? ''; // set default value to an empty string if $nom is null
        $Categorie->setNom($nom);
        $description = $req->get('description') ?? ''; // set default value to an empty string if $description is null
        $Categorie->setDescription($description);
        $marque = $req->get('marque') ?? 0.0; // set default value to 0.0 if $marque is null
        $Categorie->setmarque(floatval($marque));
        $etat = $req->get('etat') ?? 'disponible'; // set default value to 'pending' if $etat is null
        $Categorie->setEtat($etat);
        $groupe_age = $req->get('groupe_age') ?? ''; // set default value to an empty string if $nom is null
        $Categorie->setGroupeAge($groupe_age);
        $em->flush();

        $jsonContent = $Normalizer->normalize($Categorie, 'json', ['groups' => 'post:read']);
        return new Response("Categorie modifié avec succès" . json_encode($jsonContent));
    }

    #[Route("delateCategorie/{id}", name: "delateCategorie")]
    public function delateCategorie(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $Categorie = $em->getRepository(Categorie::class)->find($id);
        $em->remove($Categorie);
        $em->flush();
        $jsonContent = $Normalizer->normalize($Categorie, 'json', ['groups' => 'categories']);
        return new Response("Categorie supprimé avec succès" . json_encode($jsonContent));
    }




}