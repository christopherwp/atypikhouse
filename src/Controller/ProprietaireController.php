<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\House;
use App\Entity\Images;
use App\Form\HouseType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/proprietaire')]
class ProprietaireController extends AbstractController
{
    #[Route('/', name: 'app_proprio_index', methods: ['GET'])]
    public function index(): Response
    {   
        $user = $this->getUser();
        $proprio = $this->getUser();
    
        if (!$proprio) {
            throw $this->createNotFoundException('User not found.');
        }

        $houses = $proprio->getHouse();

        
        
        return $this->render('proprietaire/index.html.twig', [
            'houses' => $houses,
            'user' => $user,
        ]);

    }

    #[Route('/new', name: 'app_proprio_new', methods: ['GET', 'POST'])]
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

            $house->setProprietaire($proprio);

            if ($request->files->count() > 0) {
                // Obtient les objets UploadedFile pour les fichiers téléchargés
                $images = $form->get('images')->getData();

                // Traitez chaque fichier téléchargé
                foreach ($images as $image) {
                    // Génère un nouveau nom de fichier unique
                    $fileName = md5(uniqid()) . '.' . $image->guessExtension();

                    // Déplace le fichier téléchargé vers le dossier de destination
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );

                    // Crée une nouvelle instance de l'entité Images et associe le fichier téléchargé
                    $img = new Images();
                    $img->setFile($fileName);

                    // Ajoute l'image à la maison
                    $house->addImage($img);
                    
                }
            }

            $entityManager->persist($house);
            $entityManager->flush();

            return $this->redirectToRoute('app_proprio_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('proprietaire/new.html.twig', [
            'house' => $house,
            'form' => $form,
        ]);
    }

    
  
    
    #[Route('/logement/{id}', name: 'app_proprio_show', methods: ['GET'])]
    public function show(House $house): Response
    {   
        return $this->render('proprietaire/show.html.twig', [
            'house' => $house,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_proprio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, House $house, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HouseType::class, $house);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            
            $images = $form->get('images')->getData();
            foreach($images as $picture){
                $fichier = md5(uniqid()) . '.' . $picture->guessExtension();

                $picture->move( $this->getParameter('images_directory'),
                $fichier);

                $img = new Images();
                $img->setFile($fichier);

                $house->addImage($img);

            }

            $entityManager->flush();

            return $this->redirectToRoute('app_proprio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('proprietaire/edit.html.twig', [
            'house' => $house,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_proprio_delete', methods: ['POST'])]
    public function delete(Request $request, House $house, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$house->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($house);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_proprio_index', [], Response::HTTP_SEE_OTHER);
    }

   
    public function deleteImage(Request $request,EntityManagerInterface $entityManager): Response
    {
        $refererUrl = $request->headers->get('referer');
        // Récupérez l'identifiant de l'image à supprimer à partir de la demande
        $imageId = $request->request->get('image_id');

        // Votre logique pour supprimer l'image
        // Par exemple, si vous utilisez Doctrine pour gérer votre base de données
        
        $image = $entityManager->getRepository(Images::class)->find($imageId);

        if (!$image) {
            throw $this->createNotFoundException("L'image n'existe pas.");
        }

        // Supprimer l'image de la base de données
        $entityManager->remove($image);
        $entityManager->flush();

        // Supprimer le fichier physique de l'image du système de fichiers
        $filesystem = new Filesystem();
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/img/' . $image->getFile(); // chemin de l'image à supprimer
        if ($filesystem->exists($imagePath)) {
            $filesystem->remove($imagePath);
        }

        // Rediriger l'utilisateur vers une page appropriée après la suppression
        return $this->redirect($refererUrl);
    }
}




