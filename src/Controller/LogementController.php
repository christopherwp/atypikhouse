<?php

namespace App\Controller;

use App\Entity\Rent;
use App\Entity\User;
use App\Entity\House;
use App\Form\HouseType;
use App\Repository\HouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/logement')]
class LogementController extends AbstractController
{
    #[Route('/', name: 'app_logement_index', methods: ['GET'])]
    public function index(HouseRepository $houseRepository): Response
    {
        // dd($this->getUser());
        return $this->render('logement/index.html.twig', [
            'houses' => $houseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_logement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $house = new House();
        $form = $this->createForm(HouseType::class, $house);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $proprio = $this->getUser();

            if ($proprio !== null) { 
                $house->setUser($proprio); // Définissez l'ID de l'utilisateur sur l'entité Rent
            }
            
           // $entityManager->persist($rent);
            $entityManager->persist($house);
            $entityManager->flush();

            return $this->redirectToRoute('app_logement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('logement/new.html.twig', [
            'house' => $house,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_logement_show', methods: ['GET'])]
    public function show(House $house): Response
    {
       dump($house); 
      
        return $this->render('logement/show.html.twig', [
             'house' => $house,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_logement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, House $house, EntityManagerInterface $entityManager): Response
    {
        
        $form = $this->createForm(HouseType::class, $house);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_logement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('logement/edit.html.twig', [
            'house' => $house,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_logement_delete', methods: ['POST'])]
    public function delete(Request $request, House $house, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$house->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($house);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_proprio_index', [], Response::HTTP_SEE_OTHER);
    }
}
