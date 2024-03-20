<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegalController extends AbstractController
{
    #[Route('/legal', name: 'app_legal')]
    public function mentionslegales(): Response
    {
        return $this->render('legal/mentionslegales.html.twig', [
            'controller_name' => 'LegalController',
        ]);
    }

}
