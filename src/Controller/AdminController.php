<?php

namespace App\Controller;

use App\Entity\Ardoise;
use App\Entity\Commentaire;
use App\Entity\Region;
use App\Entity\Utilisateur;
use App\Form\ArdoiseType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {


        // Récupérer le nombre total d'utilisateur
        $totalUsers = $entityManager->getRepository(Utilisateur::class)->count([]);
        // Récupérer le nombre total de commentaires
        $totalComments = $entityManager->getRepository(Commentaire::class)->count([]);

        // Récupérer la moyenne des notes
        $avgRating = $entityManager->getRepository(Commentaire::class)->createQueryBuilder('c')
            ->select('AVG(c.note) as averageRating')
            ->getQuery()
            ->getSingleScalarResult();

        $formattedAvgRating = number_format($avgRating, 1);

        // Récupérer les regions
        $query = $entityManager->createQuery(
            'SELECT r.nom AS region, COUNT(u.id) AS nombreUtilisateurs
            FROM App\Entity\Region r
            JOIN App\Entity\Utilisateur u WITH r.id = u.region
            GROUP BY r.id
            ORDER BY nombreUtilisateurs DESC'
        );

        $resultats = $query->getResult();

        // La région la plus fréquemment utilisée est la première dans le résultat
        $regionLaPlusFrequente = $resultats[0]['region'];
        $nombreUtilisateurs = $resultats[0]['nombreUtilisateurs'];

        // Récupérer le nombre d'utilisateurs par région
        $query = $entityManager->createQueryBuilder()
            ->select('r.nom AS region, COUNT(u.id) AS nombreUtilisateurs')
            ->from(Region::class, 'r')
            ->innerJoin('r.utilisateurs', 'u')
            ->groupBy('r.nom')
            ->orderBy('nombreUtilisateurs', 'DESC')
            ->getQuery();

        $resultats = $query->getResult();

        // Récupérer les commentaires et la note avec le nom de l'utilisateur
        $query = $entityManager->createQueryBuilder()
            ->select('c.id AS id, c.texte AS commentaire, c.note AS note, u.nom AS utilisateurNom')
            ->from(Commentaire::class, 'c')
            ->innerJoin('c.utilisateur', 'u') // Jointure avec la table Utilisateur
            ->getQuery();

        $commentairesAvecUtilisateur = $query->getResult();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'totalUsers' => $totalUsers,
            'totalComments' => $totalComments,
            'averageRating' => $formattedAvgRating,
            'regionLaPlusFrequente' => $regionLaPlusFrequente,
            'nombreUtilisateurs' => $nombreUtilisateurs,
            'resultats' => $resultats,
            'commentairesAvecUtilisateur' => $commentairesAvecUtilisateur,
        ]);


    }

    #[Route('/commentaire/supprimer/{id}', name: 'supprimer_commentaire')]


    public function supprimerCommentaire($id, EntityManagerInterface $entityManager, CommentaireRepository $commentaireRepository): Response
    {
        // Trouver le commentaire par son ID
        $commentaire = $commentaireRepository->find($id);

        if (!$commentaire) {
            // Gérer le cas où le commentaire n'existe pas
            // ...

            return $this->redirectToRoute('admin_dashboard');
        }

        // Récupérer l'utilisateur associé au commentaire
        $utilisateur = $commentaire->getUtilisateur();



        // Supprimer le commentaire de la base de données
        $entityManager->remove($commentaire);
        $entityManager->flush();

        // Mettre à jour le champ commentaire_id de l'utilisateur
        $utilisateur->setCommentaire(null);
        $entityManager->persist($utilisateur);
        $entityManager->flush();

        // Rediriger l'administrateur vers la page administrateur
        return $this->redirectToRoute('admin_dashboard');
    }


   /* #[Route('/editArdoise', name: 'editArdoise')]
    public function editArdoise(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'instance existante d'Ardoise depuis la base de données (par exemple, en utilisant l'ID 1)
        $repository = $this->getDoctrine()->getRepository(Ardoise::class);
        $ardoise = $repository->find(1);

        if (!$ardoise) {
            throw $this->createNotFoundException('Aucune instance d\'Ardoise trouvée');
        }

        $form = $this->createForm(ArdoiseType::class, $ardoise);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // L'entité $ardoise est déjà synchronisée avec les données du formulaire
            // Vous n'avez pas besoin de faire $entityManager->persist($ardoise) car il est déjà géré par Doctrine


            $entityManager->flush();

            // Redirigez vers la page de confirmation ou une autre page
            return $this->redirectToRoute('menu_afficher');
        }

        return $this->render('admin/edit_ardoise.html.twig', [
            'form' => $form->createView(),
        ]);
    }*/

}
