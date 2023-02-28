<?php

namespace App\Service;
use Dompdf\Dompdf;
use Dompdf\Options;

class DompdfService
{
    private $domPdf ;

    public function __construct() {
 $this->domPdf = new DomPdf() ;

$pdfOptions = new Options();
$pdfOptions->set('isRemoteEnabled', true);
$pdfOptions->set('defaultFont','Garamond');

$this->domPdf->setOptions($pdfOptions);
    }

public function showPdfFile($html) {

$this->domPdf->loadHtml($html);
$html = '<img src= " public/FrontOffice/img/logoMedB.png" alt="logo">';

$this->domPdf->render(); 
$this->domPdf->stream("facture.pdf", [
    'Attachement' => false]) ;



}
public function generateBinaryPdf($html) {

$this->domPdf->loadHtml($html);
$this->domPdf->render(); 
$this->domPdf->output(); 
$output = $this->dompdf->output();
file_put_contents($pdfFilepath, $output);
    
}


}
    


    

