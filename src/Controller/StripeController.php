<?php

namespace App\Controller;

use App\Controller\CartController;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;

class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(Request $request, CartController $cartController): Response
    {
        $user=$this->getUser();
        $total = $cartController->index($request->getSession(), $this->getDoctrine()->getRepository(Produit::class));

        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'total' => $total,
            'user'=>$user
        ]);
    }


    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request , EntityManagerInterface $entityManager)
    {
        $cartController = new CartController($entityManager);
        $panier = $this->get('session')->get('panier', []);
        $produitRepository = $this->getDoctrine()->getRepository(Produit::class);

        $total = 0;
        foreach ($panier as $id => $quantity) {
            $produit = $produitRepository->find($id);
            if (!$produit) {
                throw $this->createNotFoundException('Produit non trouvé pour l\'id '.$id);
            }
            $total += $produit->getPrix() * $quantity;
        }
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Stripe\Charge::create ([
            "amount" => $total * 100,
            "currency" => "usd",
            "source" => $request->request->get('stripeToken'),
            "description" => "Binaryboxtuts Payment Test"
        ]);

        // Envoi du SMS avec Twilio
        $twilioAccountSid = 'ACf27fc20c7fb0dee2aa25c1d473c90dce';
        $twilioAuthToken = '7ade6dda2e4d911f6b316779d5172cd6';
        $twilioFromNumber = '+12706790702';
        $twilioToNumber = '+21652999421';


        $client = new Client($twilioAccountSid, $twilioAuthToken);
        $message = $client->messages->create(
            $twilioToNumber,
            [
                'from' => $twilioFromNumber,
                'body' => 'Payment successful! Total amount: ' . $total
            ]
        );

        $this->addFlash(
            'success',
            'Payment Successful! Total amount: ' . $total
        );
        return $this->redirectToRoute('app_stripe',['total' => $total], Response::HTTP_SEE_OTHER);
    }

}