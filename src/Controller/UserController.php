<?php

namespace App\Controller;

use App\Service\Users\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{

    public function __construct(private UserService $userService)
    {
    }

    #[Route('/api/users', name: 'app_all_users', methods: ['GET'])]
    public function getUsers(): JsonResponse
    {

        $users = $this->userService->getUsers();

        return $this->json($users, Response::HTTP_OK, [], ['groups' => ['user:read']]);
    }


    #[Route('/api/users/{id}', name: 'app_user_by_id', methods: ['GET'])]
    public function getUserById(int $id): JsonResponse
    {
        try {
            $user = $this->userService->getUserById($id);

            return $this->json($user, Response::HTTP_OK, [], ['groups' => ['user:read']]);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/api/users', name: 'app_users_register', methods: ['POST'])]
    public function registerUser(Request $request): JsonResponse
    {

        try {
            $data = json_decode($request->getContent(), true);
            $this->userService->createUser($data);

            return $this->json(['message' => 'L\'utilisateur a été créé !'], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    #[Route('/api/users', name: 'app_update_user', methods: ['PUT'])]
    public function updateUser(Request $request): JsonResponse
    {

        try {
            $user = $this->getUser();
            $data = json_decode($request->getContent(), true);
            $this->userService->updateUser($data, $user);

            return $this->json(['message' => 'L\'utilisateur a été mis à jour !'], Response::HTTP_OK);
        } catch (Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/users', name: 'app_delete_user', methods: ['DELETE'])]
    public function deleteUser(): JsonResponse
    {
        try {
            $user = $this->getUser();

            $this->userService->deleteUser($user);

            return $this->json(['message' => 'L\'utilisateur a été supprimé !'], Response::HTTP_OK);
        } catch (Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

}
