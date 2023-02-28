<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;

use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use App\Service\DompdfService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pdf')]
class PdfGeneratorController extends AbstractController
{
#[Route('/print/{id}', name: 'app_fact', methods: ['GET'])]

public function generatePdf(Facture $facture = null)
{   //$pdfOptions = new Options();
    //$pdfOptions->set('defaultFont', 'Arial');
    //$pdfOptions->setChroot (__DIR__);
    $dompdf = new Dompdf();
    $dompdf->set_option('isRemoteEnabled',TRUE);
    $dompdf->set_option('isHtmlParserEnabled',TRUE);

 

    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ]);
    $dompdf->setHttpContext($context);

    $html = $this->render('facture/showpdf1.html.twig',['facture' => $facture]) ;
    $dompdf->loadHtml($html);
    $dompdf->render();
    $fichier = 'Facture.pdf';

     $dompdf->stream($fichier, [
        'Attachment' => true
    ]);

    $output = $dompdf->output();
file_put_contents("file.pdf", $output);
    return new Response();
}


   /* return new Response($dompdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="document.pdf"',
    ]);
    */
}
