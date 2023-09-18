<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact_afficher')]
    public function afficher(Request $request, EntityManagerInterface $entityManager): Response
    {

        $commentaire = new Commentaire();

        $commentaireForm = $this->createForm(CommentaireType::class, $commentaire);

        $commentaireForm->handleRequest($request);

        if ($commentaireForm->isSubmitted() ){
            $commentaire->setUtilisateur($this->getUser());
            $entityManager->persist($commentaire);
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire ajouté');
            return $this->redirectToRoute('accueil_index');
        }

        return $this->render('contact/afficher.html.twig', [
            'controller_name' => 'ContactController',
            'commentaireForm' => $commentaireForm->createView()
        ]);
    }

}
