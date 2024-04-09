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
use Ramsey\Uuid\Uuid;

class PaiementController extends AbstractController
{
    #[Route('/payer', name: 'app_payer', methods: ['POST', 'GET'])]
    public function payer(Request $request, EntityManagerInterface $entityManager): Response
    {
    // pour simuler la vérification d'authentification de l'utilisateur
    // utilisateur est authentifié, dans un projet réel, nous devons utiliser ->  $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $transactionId = Uuid::uuid4()->toString(); // Génère un UUID pour la transaction
    // Ici, vous récupérez les identifiants de House et User depuis la requête, pour la simulation on va les définir manuellement
        $houseId = 1; // Exemple : l'identifiant du logement
        $userId = 1; // Exemple : l'identifiant de l'utilisateur

    // Récupération de l'entité House et User
        $house = $entityManager->getRepository(House::class)->find($houseId);
        $user = $entityManager->getRepository(User::class)->find($userId);
      
        
        if(!$user){
            $user = $this->getUser();
        }

        if (!$house || !$user) {
            return $this->json(['message' => 'House or User not found'], Response::HTTP_BAD_REQUEST);
        }

    // Création et configuration de la nouvelle réservation
        $rent = new Rent();
        $rent->setHouseId($house);
        $rent->setUserId($user);
    //$rent->setStartDate(new \DateTime()); // Date de début fictive
    //$rent->setNumDays(5); // Durée fictive
        $rent->setTotalPrice($house->getDailyPrice());
        $rent->setReservation(true); // Indique que la réservation est confirmée
        $rent->setStartDate(new \DateTime());
        $rent->setNumDays(3);
    // Assumer que vous voulez stocker cet UUID dans une propriété de l'entité Rent ou en tant que référence de paiement
    //$rent->setPaymentId($transactionId); // Cela suppose que vous avez une méthode setPaymentId dans votre entité Rent

    // Simuler la sauvegarde de la réservation dans la base de données
        $entityManager->persist($rent);
        $entityManager->flush();

        //$rentId = $rent->getId(); // Récupérer l'ID de la réservation après sa sauvegarde

    // Après avoir enregistré le paiement
        return $this->render('paiement/confirmation.html.twig', [
        'rent_id' => $rent->getId(),
        'transaction_id' => $transactionId,
        ]);
    }
    #[Route('/confirmation', name: 'app_confirmation')]
    public function confirmation(Request $request): Response
    {
        $rentId = $request->query->get('rent_id');
        $transactionId = $request->query->get('transaction_id');

        return $this->render('paiement/confirmation.html.twig', [
            'rent_id' => $rentId,
            'transaction_id' => $transactionId,
        ]); 
    }
}