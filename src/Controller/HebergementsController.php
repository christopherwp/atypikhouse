<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HebergementsController extends AbstractController
{
    #[Route('/hebergements', name: 'app_hebergements')]
    public function index(): Response
    {
        return $this->render('hebergements/index.html.twig', [
            'controller_name' => 'HebergementsController',
        ]);
    }
}
