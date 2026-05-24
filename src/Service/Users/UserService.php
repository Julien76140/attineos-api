<?php

namespace App\Service\Users;

use App\DTO\UserDTO;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    public function __construct(private ManagerRegistry             $managerRegistry,
                                private UserPasswordHasherInterface $passwordHasher,
                                private ValidatorInterface          $validator)
    {
    }

    public function getUsers(): array //Récupère tous les users
    {
        return $this->managerRegistry->getManager()->getRepository(User::class)
            ->findAll();
    }

    public function getUserById(int $id): User //Récupère un User
    {
        $user = $this->managerRegistry->getManager()
            ->getRepository(User::class)
            ->find($id);

        if (!$user) {
            throw new \Exception("Utilisateur introuvable");
        }

        return $user;
    }

    public function createUser(?array $data): void
    {
        if (!is_array($data)) {
            throw new \Exception('La requête est invalide !');
        }

        $this->checkInputs($data);

        $newUser = new User();
        $newUser->setFirstName($data['firstName']);
        $newUser->setLastName($data['lastName']);
        $newUser->setEmail($data['email']);
        $newUser->setRoles(['ROLE_USER']);

        // Hash du mdp (bcrypt)
        $hashedPassword = $this->passwordHasher->hashPassword($newUser, $data['password']);
        $newUser->setPassword($hashedPassword);

        try {
            $this->managerRegistry->getManager()->persist($newUser);
            $this->managerRegistry->getManager()->flush();
        } catch (\Exception $e) {
            throw new \Exception(('Cet email est déjà utilisé'));
        }

    }

    public function updateUser(?array $data, UserInterface $user): void // Met à jour un user
    {
        $this->checkInputs($data);

        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setEmail($data['email']);
        $user->setRoles(['ROLE_USER']);

        if (!empty($data['password'])) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        try {
            $this->managerRegistry->getManager()->flush();
        } catch (\Exception $e) {
            throw new \Exception(('Cet email est déjà utilisé'));
        }

    }

    public function deleteUser(UserInterface $user): void //Supprime un user
    {
        $this->managerRegistry->getManager()->remove($user);
        $this->managerRegistry->getManager()->flush();
    }

    private function checkInputs(array $data): void
    {
        $userDto = new UserDTO(firstName: $data['firstName'], lastName: $data['lastName'], email: $data['email'], password: $data['password']);
        $errors = $this->validator->validate($userDto);// On récupère les potentielles erreurs

        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }
            throw new \Exception(implode(', ', $messages));
        }
    }

}
