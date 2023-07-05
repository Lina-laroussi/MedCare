<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ProduitJsonController extends AbstractController
{




    //*-**-*-*-*-**-*-*-*-*-**-*-*-*-*-**-*-*-*-*-***-*-*--**-*-**-*-*--*


    //en JSON

    //https://localhost:8000/AllProduits
    #[Route("/AllProduits", name: "AllProduits")]
    public function AllProduits(ProduitRepository $repo, SerializerInterface $serializer)
    {
        $produits = $repo->findAll();
        $json = $serializer->serialize($produits, 'json', ['groups' => "produits"]);

        return new Response($json);
    }

    //https://127.0.0.1:8000/produit/1/detail
    #[Route("getProduitDetail", name: "getProduitDetail")]
    public function getProduitDetail($id, NormalizerInterface $normalizer, ProduitRepository $repo)
    {
        $produit = $repo->find($id);
        $produitNormalise = $normalizer->normalize($produit, 'json', ['groups' => "produits"]);
        return new Response(json_encode($produitNormalise));
    }

    //https://localhost:8000/addProduit/new
    #[Route("addProduit/new", name: "add_produit")]
    public function addProduit(Request $req, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = new Produit();
        $nom = $req->get('nom') ?? ''; // set default value to an empty string if $nom is null
        $produit->setNom($nom);
        $description = $req->get('description') ?? ''; // set default value to an empty string if $description is null
        $produit->setDescription($description);
        $prix = $req->get('prix') ?? 0.0; // set default value to 0.0 if $prix is null
        $produit->setPrix(floatval($prix));
        $etat = $req->get('etat') ?? 'disponible'; // set default value to 'pending' if $etat is null
        $produit->setEtat($etat);
        $quantite = $req->get('quantite') ?? 0.0; // set default value to 0.0 if $quantite is null
        $produit->setQuantite($quantite);
        $image = $req->get('image') ?? ''; // set default value to an empty string if $nom is null
        $produit->setImage($image);
        $em->persist($produit);
        $em->flush();

        $jsonContent = $Normalizer->normalize($produit, 'json', ['groups' => 'produits']);
        return new Response(json_encode($jsonContent));
    }

    #[Route("updateProduit/{id}", name: "update_produit")]
    public function updateProduit(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produit::class)->find($id);
        $nom = $req->get('nom') ?? ''; // set default value to an empty string if $nom is null
        $produit->setNom($nom);
        $description = $req->get('description') ?? ''; // set default value to an empty string if $description is null
        $produit->setDescription($description);
        $prix = $req->get('prix') ?? 0.0; // set default value to 0.0 if $prix is null
        $produit->setPrix(floatval($prix));
        $etat = $req->get('etat') ?? 'disponible'; // set default value to 'pending' if $etat is null
        $produit->setEtat($etat);
        $quantite = $req->get('quantite') ?? 0.0; // set default value to 0.0 if $quantite is null
        $produit->setQuantite($quantite);
        $image = $req->get('image') ?? ''; // set default value to an empty string if $nom is null
        $produit->setImage($image);
        $em->flush();

        $jsonContent = $Normalizer->normalize($produit, 'json', ['groups' => 'post:read']);
        return new Response("Produit modifié avec succès" . json_encode($jsonContent));
    }

    #[Route("deleteProduit/{id}", name: "delete_produit")]
    public function deleteProduit(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produit::class)->find($id);
        $em->remove($produit);
        $em->flush();
        $jsonContent = $Normalizer->normalize($produit, 'json', ['groups' => 'produits']);
        return new Response("Produit supprimé avec succès" . json_encode($jsonContent));
    }
}
?>
