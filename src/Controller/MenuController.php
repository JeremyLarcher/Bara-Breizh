<?php

namespace App\Controller;

use App\Repository\ArdoiseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'menu_afficher')]
    public function afficher(ArdoiseRepository $ardoiseRepository): Response
    {

        // Récupérer les données de la table Ardoise
        $ardoise = $ardoiseRepository->findOneBy([]);

        return $this->render('menu/afficher.html.twig', [
            'controller_name' => 'MenuController',
            'ardoise' => $ardoise,
        ]);
    }

    #[Route('/telecharger', name: 'menu_telecharger')]
    public function telecharger(): BinaryFileResponse
    {
        $pdfPath = $this->getParameter('kernel.project_dir') . '/public/img/menu/menu.pdf'; // Chemin vers le fichier PDF

        // Créez une réponse binaire pour le fichier PDF
        $response = new BinaryFileResponse($pdfPath);

        // Définissez les en-têtes de réponse pour forcer le téléchargement du fichier
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'menu.pdf' // Nom du fichier téléchargé
        ));

        return $response;
    }
}
