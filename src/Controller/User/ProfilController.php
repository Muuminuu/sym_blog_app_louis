<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {

        $user = $this->getUser();

        $rolesArray = $user->getRoles();
        $rolesString = implode(' ', array_map('ucwords', array_map('strtolower', $rolesArray)));

        $roles = $rolesString; // Affichera "Roles User"

        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
            'roles' => $roles
        ]);
    }

}
