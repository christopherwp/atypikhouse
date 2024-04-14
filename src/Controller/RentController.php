<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Rent;
use App\Entity\House;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RentRepository;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RentController extends AbstractController
{   
    /**
     * Override to ensure IDE completion and type check.
     * @return \App\Entity\User|null
     */
    public function getUser(): ?User
    {
        return parent::getUser();
    }


    #[Route('/reservation', name: 'app_reservation', methods: ['POST'])]
    public function reservation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour faire une réservation.');
            return $this->redirectToRoute('app_login');
        }
        // Utilisez $request->request pour obtenir les données POST au lieu de json_decode
        $houseId = $request->request->get('house_id');
        $house = $entityManager->getRepository(House::class)->find($houseId);

        if (!$house) {
            $this->addFlash('error', 'Logement non trouvé.');
            return $this->redirectToRoute('home');
        }

        $startDate = $request->request->get('startDate');
        $endDate = $request->request->get('endDate');
        $numDays = $request->request->get('numberOfDays');
        $totalPrice = $request->request->get('totalPrice');

        // Validez les données reçues ici...
        if ($startDate && $endDate && $numDays && $totalPrice) {
            $startDate = new \DateTime($startDate);
            $endDate = new \DateTime($endDate);

            $rent = new Rent();
            $rent->setHouse($house);
            $rent->setStartDate($startDate);
            $rent->setEndDate($endDate);
            $rent->setNumDays($numDays);
            $rent->setTotalPrice($totalPrice);
            $rent->setProprietaire($house->getProprietaire());
            $rent->setUser($user);

            // Persist and flush the Rent entity
            $entityManager->persist($rent);
            $entityManager->flush();

            // Après avoir enregistré la réservation, redirigez vers la route de récapitulatif
            return $this->redirectToRoute('app_recapitulatif', ['rentId' => $rent->getId()]);
        } else {
            // Si les données ne sont pas valides, redirigez vers un autre endroit ou affichez une erreur
            $this->addFlash('error', 'Invalid reservation data');
            return $this->redirectToRoute('home'); // Redirigez vers la route 'home' ou une autre route appropriée
        }
    }


    #[Route('/recapitulatif/{rentId}', name: 'app_recapitulatif', methods: ['GET'])]
    public function recapitulatif(EntityManagerInterface $entityManager, int $rentId): Response
    {
        $rent = $entityManager->getRepository(Rent::class)->find($rentId);

        if (!$rent) {

            throw $this->createNotFoundException('La réservation demandée n\'a pas été trouvée.');
        }

        $house = $rent->getHouse();

        if (!$house) {
            throw $this->createNotFoundException('Le logement associé à cette réservation n\'a pas été trouvé.');
        }

        $user = $rent->getUser();
        $proprietaire = $house->getProprietaire();

        return $this->render('rent/recapitulatif.html.twig', [
            'rent' => $rent,
            'house' => $house,
            'proprietaire' => $proprietaire ? $proprietaire : null,
            'user' => $user ? $user : null
        ]);
    }

    #[Route('/historique', name: 'app_historique')]
    public function historique(RentRepository $rentRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $template = '';
        $rents = [];
        
        // Choix du template basé sur le rôle de l'utilisateur
        if ($user->hasRole('ROLE_ADMIN')) {
            $rents = $rentRepository->findAll();
            $template = 'admin/dashboard.html.twig';

        } elseif ($user->hasRole('ROLE_PROPRIO')) {
            $rents = $rentRepository->findBy(['proprietaire' => $user]);
        $template = 'proprietaire/historyProprietaire.html.twig';

        } elseif ($user->hasRole('ROLE_LOCA')) {
            $rents = $rentRepository->findBy(['user' => $user]);
            $template = 'compte/historyLocataire.html.twig';
            
        } else {
            throw new AccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette page.');
        }

        return $this->render($template, [
            'rents' => $rents
        ]);
    }
  
}
