<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ContactController extends AbstractController
{
    private $client;

    // Injectez HttpClientInterface via le constructeur
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('contact/contact.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }

    #[Route('/submit-contact-form', name: 'submit_contact_form', methods: ['POST'])]
    public function handleFormSubmission(Request $request): Response
    {
        $recaptchaResponse = $request->request->get('g-recaptcha-response');
        $recaptchaSecret = '6LdQo7opAAAAAG2kWul1gO-wHAcj9ZS8JMrw6WCB';

        $response = $this->client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret' => $recaptchaSecret,
                'response' => $recaptchaResponse
            ]
        ]);

        $results = $response->toArray();

        if (true !== $results['success']) {
            // Gérer l'échec de la vérification du CAPTCHA
            return new Response('CAPTCHA validation failed.', Response::HTTP_FORBIDDEN);
        }

        // Traitement du formulaire après la validation du CAPTCHA
        // Ajoutez ici votre logique de traitement du formulaire

        return new Response('Form submitted successfully.');
    }
}

