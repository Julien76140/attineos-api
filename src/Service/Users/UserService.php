<?php

namespace App\Service\Users;

use App\DTO\UserDTO;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    public function __construct(private ManagerRegistry $managerRegistry,
                                private UserPasswordHasherInterface $passwordHasher,
                                private ValidatorInterface $validator)
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

    public function createUser(?array $data)
    {
        if (!is_array($data)){
            throw new \Exception('La requête est invalide !');
        }

        $userDto = new UserDTO(firstName: $data['firstName'], lastName: $data['lastName'], email: $data['email'], password: $data['password']);
        $errors = $this->validator->validate($userDto);// On récupère les potentielles erreurs

        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }
            throw new \Exception(json_encode($messages));
        }


        $newUser = new User();
        $newUser->setFirstName($data['firstName']);
        $newUser->setLastName($data['lastName']);
        $newUser->setEmail($data['email']);
        $newUser->setRoles(['ROLE_USER']);

        // Hash du mdp (bcrypt)
        $hashedPassword = $this->passwordHasher->hashPassword($newUser,$data['password']);
        $newUser->setPassword($hashedPassword);

        try {
            $this->managerRegistry->getManager()->persist($newUser);
            $this->managerRegistry->getManager()->flush();
        } catch (\Exception $e) {
            throw new \Exception(('Cet email est déjà utilisé'));
        }

    }

}
