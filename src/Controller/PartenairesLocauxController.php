<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartenairesLocauxController extends AbstractController
{
    #[Route('/partenaireslocaux', name: 'partenaireslocaux_afficher')]
    public function afficher(): Response
    {
        return $this->render('partenaires_locaux/afficher.html.twig', [
            'controller_name' => 'PartenairesLocauxController',
        ]);
    }
}
