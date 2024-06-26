<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Mime\Address;
use App\Entity\User;
use App\Entity\House;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Form\AdminRegistrationFormType;
use App\Form\UserActivationFormType;
use App\Repository\HouseRepository;
use App\Repository\RentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Rent;

#[Route('/admin')]
class AdminController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier, RouterInterface $router)
    {
        $this->emailVerifier = $emailVerifier;
    }
    #[Route('/', name: 'app_admin_dashboard')]

    public function dashboard(
        Request $request,
        EntityManagerInterface $entityManager,
        HouseRepository $houseRepository,
        RentRepository $rentRepository,
        UserRepository $userRepository,
    ): Response {
        // Utilisateurs
        $usersLoca = $userRepository->findByRole('ROLE_LOCA');
        $usersProprio = $userRepository->findByRole('ROLE_PROPRIO');
        $usersAdmin = $userRepository->findByRole('ROLE_ADMIN');

        $user = $this->getUser();

        // Hébergements
        $houses = $houseRepository->findAll();

        //Statistiques
        $totalHouses = $entityManager->getRepository(House::class)->count([]);


        $userRepository = $entityManager->getRepository(User::class);
        $roles = ['ROLE_LOCA', 'ROLE_PROPRIO', 'ROLE_ADMIN'];

        foreach ($roles as $role) {
            $totalsByRole[$role] = $userRepository->countByRole($role);
        }

        $totalUsersLoca = $totalsByRole['ROLE_LOCA'];
        $totalUsersProprio = $totalsByRole['ROLE_PROPRIO'];
        $totalUsersAdmin = $totalsByRole['ROLE_ADMIN'];

        // Récupérer la requête de recherche
        $search = $request->query->get('search', '');

        // Récupérer la requête de history location
        $paidRents = $rentRepository->findAll();
        $housesQuery = $entityManager->getRepository(House::class)->createQueryBuilder('h')
            ->where('h.id LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery();

        $houses = $housesQuery->getResult();

        $roleFilter = $request->query->get('roleFilter', '');

        if ($roleFilter) {

            $users = $entityManager->getRepository(User::class)->findByRole($roleFilter);
        } else {
            $users = $entityManager->getRepository(User::class)->findAll();
        }
        return $this->render('admin/dashboard.html.twig', [
            'controller_name' => 'AdminController',
            'usersLoca' => $usersLoca,
            'usersProprio' => $usersProprio,
            'usersAdmin' => $usersAdmin,
            'houses' => $houses,
            'totalHouses' => $totalHouses,
            'totalUsersLoca' => $totalUsersLoca,
            'totalUsersProprio' => $totalUsersProprio,
            'totalUsersAdmin' => $totalUsersAdmin,
            'users' => $users,
            'paidRents' => $paidRents,
            'user' => $user,
        ]);
    }

    #[Route('/register', name: 'app_admin_register')]
    public function registerAdmin(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(AdminRegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->addRole('ROLE_ADMIN');

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('atypikhouse@hotmail.com', 'atypikhouse'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('admin/adminregister.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/user/{id}/activate', name: 'admin_user_activate')]
    public function activateUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {

        if ($user->isActive()) {
            $user->setIsActive(0);
        } else {
            $user->setIsActive(1);
        }
        $entityManager->persist($user);
        $entityManager->flush();


        return $this->redirectToRoute('app_admin_dashboard');
    }

    #[Route('/house/{id}/delete', name: 'admin_house_delete')]
    public function deleteHouse(EntityManagerInterface $entityManager, House $house): Response
    {
        $entityManager->remove($house);
        $entityManager->flush();
        $this->addFlash('success', 'La maison a été supprimée avec succès.');
        return $this->redirectToRoute('app_admin_dashboard');
    }
}
