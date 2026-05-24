<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserDTO // class permettant de vérifier les inputs reçus
{
    public function __construct(
        #[Assert\NotBlank(message: 'Le prénom est obligatoire')] // On utilise les asserts Symfony pour vérifier chaque input
        #[Assert\Length(min: 2, minMessage: 'Le prénom doit faire au moins {{ limit }} caractères')]
        public string $firstName,

        #[Assert\NotBlank(message: 'Le nom est obligatoire')]
        #[Assert\Length(min: 2, minMessage: 'Le nom doit faire au moins {{ limit }} caractères')]
        public string $lastName,

        #[Assert\NotBlank(message: "L'email est obligatoire")]
        #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide")]
        public string $email,

        #[Assert\NotBlank(message: 'Le mot de passe est obligatoire')]
        #[Assert\Length(min: 8, minMessage: 'Le mot de passe doit faire au moins {{ limit }} caractères')]
        #[Assert\Regex(pattern: '/^(?=.*[A-Za-z])(?=.*\d).+$/', //Regex pour le mdp
            message: 'Le mot de passe doit contenir au moins une lettre et un chiffre'
        )]
        public string $password,
    ) {

    }
}
