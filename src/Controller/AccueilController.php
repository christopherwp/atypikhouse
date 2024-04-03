<?php

namespace App\Controller;

use App\Repository\HouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function accueil(HouseRepository $houseRepository): Response
    {

        $houses = $houseRepository->findAll();

        return $this->render('accueil/index.html.twig', [
            '$houses' => $houses,
        ]);
    }
}
