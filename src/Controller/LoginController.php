<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/api/login', name: 'api_login', methods: ['POST'])]
    public function apiLogin(Request $request, JWTTokenManagerInterface $JWTManager, UserProviderInterface $userProvider,UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $credentials = json_decode($request->getContent(), true);
        $email = $credentials['email'];
        $password = $credentials['password'];
        $user = $userProvider->loadUserByIdentifier($email);
        
        
        if (!$user || !$this->isPasswordValid($passwordHasher ,$user, $password)) {
            return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $JWTManager->create($user);
        return new JsonResponse(['token' => $token]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    private function isPasswordValid(UserPasswordHasherInterface $passwordHasher,$user, $password)
    {
        return $passwordHasher->isPasswordValid($user, $password);
    }
}
