<?php

namespace App\Controller;


use App\Entity\Ordonnance;
use App\Form\OrdonnanceType;
use App\Repository\OrdonnanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Writer;


#[Route('/ordonnance')]
class OrdonnanceController extends AbstractController
{
// afficher tous les ordenance
    #[Route('/', name: 'app_ordonnance_index', methods: ['GET'])]
    public function index(OrdonnanceRepository $ordonnanceRepository): Response
    {
        return $this->render('Front-Office/ordonnance/ordonnance.html.twig', [
            'ordonnances' => $ordonnanceRepository->findAll(),
        ]);
    }

// creation new ordenance
    #[Route('/new', name: 'app_ordonnance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OrdonnanceRepository $ordonnanceRepository): Response
    {
        // Generate the pre-generated code, convert it to a hexadecimal string bin2hex
        $preGeneratedCode = substr(bin2hex(random_bytes(5)), 0, 10);
        
        $ordonnance = new Ordonnance();
        $form = $this->createForm(OrdonnanceType::class, $ordonnance, [
            'pre_generated_code' => $preGeneratedCode, // pass the pre-generated code to the form builder
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ordonnanceRepository->save($ordonnance, true);

            return $this->redirectToRoute('app_ordonnance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Front-Office/ordonnance/new.html.twig', [
            'ordonnance' => $ordonnance,
            'form' => $form,
        ]);
    }


    
// afficher ordenance par id
    #[Route('/{id}', name: 'app_ordonnance_show', methods: ['GET'])]
    public function show(Ordonnance $ordonnance): Response
    {
        return $this->render('Front-Office/ordonnance/show.html.twig', [
            'ordonnance' => $ordonnance,
        ]);
    }

// modifier ordenance
    #[Route('/{id}/edit', name: 'app_ordonnance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ordonnance $ordonnance, OrdonnanceRepository $ordonnanceRepository): Response
    {
        $form = $this->createForm(OrdonnanceType::class, $ordonnance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ordonnanceRepository->save($ordonnance, true);

            return $this->redirectToRoute('app_ordonnance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Front-Office/ordonnance/edit.html.twig', [
            'ordonnance' => $ordonnance,
            'form' => $form,
        ]);
    }

// delete ordenance
    #[Route('/{id}', name: 'app_ordonnance_delete', methods: ['POST'])]
    public function delete(Request $request, Ordonnance $ordonnance, OrdonnanceRepository $ordonnanceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ordonnance->getId(), $request->request->get('_token'))) {
            $ordonnanceRepository->remove($ordonnance, true);
        }

        return $this->redirectToRoute('app_ordonnance_index', [], Response::HTTP_SEE_OTHER);
    }

// print ordenance by id
    #[Route('/ordonnance/{id}/print', name: 'print_ordonnance')]
    public function printOrdonnance(Ordonnance $ordonnance): Response
    {
        // Generate QR code
        $renderer = new ImageRenderer(
        new \BaconQrCode\Renderer\RendererStyle\RendererStyle(400),
        new ImagickImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeData = sprintf(
            "Medicaments: %s, Dosage: %s, Nombre de jours: %s",
            $ordonnance->getMedicaments(),
            $ordonnance->getDosage(),
            $ordonnance->getNombreJours()
        );
        $qrCode = $writer->writeString($qrCodeData);
        
        // Save QR code image to public directory
        $publicDir = $this->getParameter('kernel.project_dir') . '/public/FrontOffice/img/qr-code';
        $qrCodeFilename = uniqid() . '.png';
        $qrCodePath = $publicDir . '/' . $qrCodeFilename;
        file_put_contents($qrCodePath, $qrCode);

        return $this->render('Front-Office/ordonnance/print_ordonnance.html.twig', [
            'ordonnance' => $ordonnance, 
            'qrCodeFilename' => $qrCodeFilename,
            'qrCodeUrl' => '/FrontOffice/img/qr-code/' . $qrCodeFilename,
        ]);
    }






}
