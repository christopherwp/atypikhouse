<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CabanesController extends AbstractController
{
    #[Route('/cabanes', name: 'app_cabanes')]
    public function cabanes(): Response
    {
        return $this->render('cabanes/cabanes.html.twig', [
            'controller_name' => 'CabanesController',
        ]);
    }
}
