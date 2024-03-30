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
       

        return $this->render('compte/index.html.twig', [
            'controller_name' => 'CompteController',
            'user' => $this->getUser()
        ]);
    }
    #[Route('/compte/edit/{id}', name: 'app_compte_edit')]
    public function editCompte(User $user): Response
    {
       

        return $this->render('compte/edit.html.twig', [
            'controller_name' => 'CompteController',
       
        ]);
    }
}



