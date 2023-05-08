<?php

namespace App\Controller;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/", name="index")
     */
    public function index(SessionInterface $session, ProduitRepository $produitRepository)
    {
        $user=$this->getUser();
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total =0;

        foreach($panier as $id => $quantite){
            $produit = $produitRepository->find($id);
            if ($produit !== null) {
                $dataPanier[] = [
                    "produit" => $produit,
                    "quantite"   => $quantite
                ];

                $produitPrix = $produit->getPrix();
                var_dump($produitPrix);
                $total += $produitPrix * $quantite;
            }
        }

        return $this->render('cart/index.html.twig', compact("dataPanier", "total",'user'));
    }


    /**
     * @Route("/add/{id}", name="add")
     */
    public function add($id, ProduitRepository $produitRepository, SessionInterface $session)
    {
        $produit = $produitRepository->find($id);

        // Vérifie si le produit existe
        if (!$produit) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }

        // On récupère le panier actuel
        $panier = $session->get("panier", []);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(int $id, SessionInterface $session, ProduitRepository $produitRepository)
    {
        $produit = $produitRepository->find($id);
        if (!$produit) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }

        // On récupère le panier actuel
        $panier = $session->get("panier", []);

        if (isset($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }



    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Produit $produit, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $produit->getId();

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/delete", name="delete_all")
     */
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("cart_index");
    }

    public function checkout(SessionInterface $session ,  ProduitRepository $produitRepository)
    {
        // récupérer le panier depuis la session
        $cart = $session->get('panier', []);

        // calculer le montant total des produits dans le panier
        $total = 0;
        foreach ($cart as $id => $quantity) {
            $product = $produitRepository->find($id);
            if ($product) {
                $total += $product->getPrix() * $quantity;
            }
        }

        // rendre la vue avec le montant total
        return $this->render('cart/checkout.html.twig', [
            'stripe_key' => $this->getParameter('stripe_public_key'),
            'total' => $total
        ]);
    }

}