<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'menu_afficher')]
    public function afficher(): Response
    {
        return $this->render('menu/afficher.html.twig', [
            'controller_name' => 'MenuController',
        ]);
    }
}
