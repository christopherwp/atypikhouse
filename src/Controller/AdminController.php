<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\AdminRegistrationFormType;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use App\Form\UserActivationType;

#[Route('/admin')]
class AdminController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier,RouterInterface $router)
    {
        $this->emailVerifier = $emailVerifier;
       
        
    }
    #[Route('/', name: 'app_admin_dashboard')]

    public function dashboard (EntityManagerInterface $entityManager): Response
    {
        $usersLoca = $entityManager->getRepository(User::class)->findByRole('ROLE_LOCA');
        $usersProprio = $entityManager->getRepository(User::class)->findByRole('ROLE_PROPRIO');
        $usersAdmin = $entityManager->getRepository(User::class)->findByRole('ROLE_ADMIN');

        return $this->render('admin/dashboard.html.twig', [
            'controller_name' => 'AdminController',
            'usersLoca' => $usersLoca,
            'usersProprio' => $usersProprio,
            'usersAdmin' => $usersAdmin,
        ]);
    }

    #[Route('/users', name: 'app_admin_user_list')]
    public function userList(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
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
         $form = $this->createForm(UserActivationType::class, $user);
         $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

            $this->addFlash('success', 'User status updated successfully!');

            return $this->redirectToRoute('admin_user_list'); // Route qui mène à liste d'utilisateurs
        }

        return $this->render('admin/activateUser.html.twig', [
        'user' => $user,
        'form' => $form->createView(),
        ]);
    }
}




    
