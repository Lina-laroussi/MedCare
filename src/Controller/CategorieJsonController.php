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

class CategorieJsonController extends AbstractController
{


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
