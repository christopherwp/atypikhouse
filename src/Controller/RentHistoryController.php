<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RentRepository;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RentHistoryController extends AbstractController
{
    #[Route('/rent/history', name: 'app_rent_history')]
    public function showRentHistory(RentRepository $rentRepository): Response
    {   
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        

        if ($user->hasRole('ROLE_LOCA')) {
            $paidRentsLoca = $rentRepository->findPaidRentsByUser($user);

            

            return $this->render('compte/historyLocataire.html.twig', [
                'paidRents' => $paidRentsLoca,
            ]);
        } elseif ($user->hasRole('ROLE_PROPRIO')) {
            $paidRentsProprio = $rentRepository->findPaidRentsByUser($user);

            

            return $this->render('proprietaire/historyProprietaire.html.twig', [ // Assurez-vous que ce template existe
                'paidRents' => $paidRentsProprio,
            ]);
        } elseif ($user->hasRole('ROLE_ADMIN')) {
            $paidRents = $rentRepository->findAll();


            return $this->render('admin/dashboard.html.twig', [ // Utilisez le bon chemin vers votre template admin
                'paidRents' => $paidRents,
            ]);
        } else {
            throw new AccessDeniedException('Vous n\'êtes autorisé à accéder à cette page.');
        }
    }
}