<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Form\UserActivationType;

class AdminController extends AbstractController
{   
    #[Route('/admin/users', name: 'admin_user_list')]
    public function userList(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/userList.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/register', name: 'app_admin-register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
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
        }
        return $this->render('accueill/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
        //return $this->redirectToRoute('app_admin_register');
    }

    #[Route('/admin/user/{id}/activate', name: 'admin_user_activate')]
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



    
