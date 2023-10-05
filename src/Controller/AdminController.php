<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
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

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'totalUsers' => $totalUsers,
            'totalComments' => $totalComments,
            'averageRating' => $formattedAvgRating,
            'regionLaPlusFrequente' => $regionLaPlusFrequente,
            'nombreUtilisateurs' => $nombreUtilisateurs,
        ]);


    }
}
