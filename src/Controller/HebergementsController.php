<?php

namespace App\Controller;

use App\Entity\House;
use App\Entity\Media;
use App\Repository\HouseRepository;
use App\Repository\MediaRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/hebergements')]
class HebergementsController extends AbstractController
{
    #[Route('/', name: 'app_hebergements')]
    public function hebergements(HouseRepository $houseRepository,MediaRepository $mediaRepository): Response
    {
      

        $houses = $houseRepository->findAll();
        $medias = $mediaRepository->findAll();
        dump($medias); 


        return $this->render('hebergements/hebergements.html.twig', [
            'medias' => $medias,
            'houses' => $houses,
        ]);
    }

    #[Route('/{id}', name: 'app_hebergements_show', methods: ['GET'])]
    public function show(House $house): Response
    {
        return $this->render('logement/show.html.twig', [
            'house' => $house,
        ]);
    }
}
