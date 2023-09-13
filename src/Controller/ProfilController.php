<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

#[Route('/profil', name: 'profil_')]
class ProfilController extends AbstractController
{
    #[Route('/afficher', name: 'afficher')]
    public function afficher(): Response
    {
        $utilisateur = $this->getUser();

        return $this->render('profil/afficher.html.twig', [
            "utilisateur" => $utilisateur,
        ]);
    }

    #[Route('/supprimer', name: 'supprimer')]
    public function supprimer(Request $request, UtilisateurRepository $utilisateurRepository, TokenStorageInterface $tokenStorage, Security $security): Response
    {
        $utilisateur = $this->getUser();

        $utilisateurRepository->remove($utilisateur, true);



        // Déconnecter l'utilisateur
        $this->get('security.token_storage')->setToken(null);
        $this->get('session')->invalidate();


        $this->addFlash('sup', 'Votre compte a bien été supprimée');

        return $this->redirectToRoute('accueil_index');

    }
}
