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
    #[Route('/payer', name: 'app_payer', methods: ['POST'])]
    public function payer(Request $request, EntityManagerInterface $entityManager): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $rentId = $request->request->get('rentId');
        $rent = $entityManager->getRepository(Rent::class)->find($rentId);
        // Assume payment is processed here
        $transactionId = Uuid::uuid4()->toString();

        if (!$rent) {
            // Gérer l'erreur si aucune réservation n'est trouvée avec cet ID
            throw $this->createNotFoundException('Aucune réservation trouvée avec l\'ID : ' . $rentId);
        }

        $rent->setTransactionId($transactionId);
        $rent->setPaid(true);
        $entityManager->flush();

        
        return $this->redirectToRoute('app_confirmation', [
            'rentId' => $rent->getId(), 
            'transactionId' => $transactionId,
        ]);
    }


    #[Route('/confirmation', name: 'app_confirmation', methods: ['GET'])]
    public function confirmation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rentId = $request->query->get('rentId');    
        $transactionId = $request->query->get('transactionId');

        $rent = $entityManager->getRepository(Rent::class)->find($rentId);

        if (!$rent) {  
            throw $this->createNotFoundException('La réservation demandée n\'a pas été trouvée.');
        }

        // Assurez-vous que rent contient une propriété 'house' valide
        $house = $rent->getHouse();
        if (!$house) {
            throw $this->createNotFoundException('Le logement associé à cette réservation n\'a pas été trouvé.');
        }

        return $this->render('paiement/confirmation.html.twig', [
            'rent' => $rent,  // cela devrait s'afficher dans les historiques du locataire, du proprietaire et dans le dashboardAdmin (j'ai recupere ça dans la base de donnees)
            'transactionId' => $transactionId,  // cela devrait s'afficher dans les historiques du locataire, du proprietaire et dans le dashboardAdmin (je dois recuperer ça dans la base de donnees)
        ]);
    }
}


