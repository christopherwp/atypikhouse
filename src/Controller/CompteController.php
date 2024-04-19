<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;

class CompteController extends AbstractController
{
    #[Route('/compte', name: 'app_compte')]
    public function index(): Response
    {
        $user = $this->getUser(); // Récupérez l'utilisateur actuel

        // Vérifiez si l'utilisateur a le rôle de propriétaire
        if ($this->isGranted('ROLE_PROPRIO')) {
            return $this->render('compte/proprietaire.html.twig', [
                'controller_name' => 'CompteController',
                'user' => $user
            ]);
        }

        // Vérifiez si l'utilisateur a le rôle de locataire
        if ($this->isGranted('ROLE_LOCA')) {
            return $this->render('compte/locataire.html.twig', [
                'controller_name' => 'CompteController',
                'user' => $user
            ]);
        }

        // Si l'utilisateur n'a aucun des rôles spécifiés, retournez un template par défaut ou gérez l'erreur
        return $this->render('app_login', [
            'user' => $user
        ]);
    }
}

