<?php

namespace App\Controller;

use App\Entity\FicheAssurance;

use App\Repository\FicheAssuranceRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/mobile')]
class MobileController extends AbstractController
{
///////////////////////////////////////////////////////////////////////////MOBILE
#[Route('/json', name: 'app_fiche_assurance_index_json')]
public function app_fiche_assurance_index_json(FicheAssuranceRepository $repo, SerializerInterface $serializer){
    $fichesAssurances=$repo->findAll();    
    $json=$serializer->serialize($fichesAssurances, 'json', ['groups'=>"ficheAssurance"]);
    return new Response($json);
}

#[Route("/json/{id}", name: "app_fiche_assurance_index_id_json")]
public function app_fiche_assurance_index_id_json($id, NormalizerInterface $normalizer, FicheAssuranceRepository $repo)
{
    $ficheAssurance = $repo->find($id);
    $ficheAssuranceNormalises = $normalizer->normalize($ficheAssurance, 'json', ['groups' => "ficheAssurance"]);
    return new Response(json_encode($ficheAssuranceNormalises));
}


    #[Route("/json/new", name: "app_fiche_assurance_mobile_new" , methods: ['GET', 'POST'])]
    public function addFicheAssuranceJSON(Request $req,   NormalizerInterface $Normalizer, FicheAssuranceRepository $repo)
    {

        $em = $this->getDoctrine()->getManager();
        $FicheAssurance = new FicheAssurance();
     
        $FicheAssurance->setNumAdherent($req->get('NumAdherent'));
        $FicheAssurance->setDescription($req->get('Description'));
        //$FicheAssurance->setDateCreation(new \DateTime());
        $FicheAssurance->setImageFacture($req->get('ImageFacture'));
        $FicheAssurance->setEtat($req->get('Etat'));
        
        $repo->save($FicheAssurance, true);

        $jsonContent = $Normalizer->normalize($FicheAssurance, 'json', ['groups' => 'ficheAssurance']);
        return new Response(json_encode($jsonContent));
    }


    #[Route("/json/update/{id}", name: "app_fiche_assurance_update_id_json")]
public function app_fiche_assurance_update_json($id,Request $req, NormalizerInterface $normalizer)
{
    
    $em = $this->getDoctrine()->getManager();
    $ficheAssurance= $em->getRepository(FicheAssurance::class)->find($id);

    $ficheAssurance->setNumAdherent($req->get('NumAdherent'));
    $ficheAssurance->setDescription($req->get('Description'));
    $ficheAssurance->setDateCreation(new \DateTime());
    $ficheAssurance->setImageFacture($req->get('ImageFacture'));
    $ficheAssurance->setEtat($req->get('Etat'));

    $em->flush();
    $jsonContent=$normalizer->normalize($ficheAssurance,'json', ['groups'=>'ficheAssurance']);
    return new Response("student updated successfully". json_encode($jsonContent));
}


#[Route("/json/delete/{id}", name: "app_fiche_assurance_delete_json")]
public function app_fiche_assurance_delete_json($id,Request $req, NormalizerInterface $normalizer)
{
    $em = $this ->getDoctrine()->getManager();
    $fiche_assurance=$em->getRepository(FicheAssurance::class)->find($id);
    $em->remove($fiche_assurance);
    $em->flush();
    $jsonContent = $normalizer->normalize($fiche_assurance, 'json', ['groups'=>'ficheAssurance']); 
    return new Response("fiche effacée" . json_encode($jsonContent));
}

    
}
?>