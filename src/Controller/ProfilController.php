<?php

namespace App\Controller;

use App\Form\ModifierProfilType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/modifier', name: 'modifier')]
    public function modifier(Request $request, EntityManagerInterface $entityManager, UtilisateurRepository $utilisateurRepository): Response
    {
        $user = $this->getUser(); // Obtient l'utilisateur connecté

        // Crée un formulaire en utilisant le FormBuilder

        $form = $this->createForm(ModifierProfilType::class, $user);

        // Traite la soumission du formulaire

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            // Enregistre les modifications dans la base de données
            $entityManager->flush();

            // Redirige vers une page de confirmation ou ailleurs
            return $this->redirectToRoute('profil_afficher');
        }

        return $this->render('profil/modifier.html.twig', [
            'ModifierProfil' => $form->createView(),
        ]);
    }

}
