<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(): JsonResponse
    {
        return $this->json([
            'message' => 'Bienvenue sur l\'API Attineos !',
            'version' => '1.0.0',
            'routes' => [
                'publiques' => [
                    [
                        'methode' => 'GET',
                        'route' => '/api/users',
                        'description' => 'Récupère la liste de tous les utilisateurs',
                    ],
                    [
                        'methode' => 'GET',
                        'route' => '/api/users/{id}',
                        'description' => 'Récupère un utilisateur par son identifiant',
                    ],
                    [
                        'methode' => 'POST',
                        'route' => '/api/users',
                        'description' => 'Crée un nouvel utilisateur',
                        'body' => ['firstName', 'lastName', 'email', 'password'],
                    ],
                    [
                        'methode' => 'POST',
                        'route' => '/api/auth/login',
                        'description' => 'Connexion — retourne un token JWT',
                        'body' => ['email', 'password'],
                    ],
                ],
                'protegees' => [
                    [
                        'methode' => 'PUT',
                        'route' => '/api/users',
                        'description' => 'Met à jour l\'utilisateur connecté',
                        'body' => ['firstName', 'lastName', 'email', 'password'],
                        'auth' => 'Bearer token JWT requis',
                    ],
                    [
                        'methode' => 'DELETE',
                        'route' => '/api/users',
                        'description' => 'Supprime l\'utilisateur connecté',
                        'auth' => 'Bearer token JWT requis',
                    ],
                ],
            ],
        ], Response::HTTP_OK);
    }
}
