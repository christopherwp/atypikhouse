<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    
    #[Route("/sitemap.xml", name:"app_sitemap", defaults:["_format" => "xml"])]
    
    public function index(): Response
    {
        // Les données du sitemap (URLs, dates de dernière modification, priorités) peuvent être stockées dans un tableau associatif
        $urls = [
            [
                'loc' => 'https://f2i-dev23a-cw-ey-mgv.fr/',
                'lastmod' => '2024-04-19T06:59:52+00:00',
                'priority' => '1.00',
            ],
            [
                'loc' => 'https://f2i-dev23a-cw-ey-mgv.fr/hebergements/',
                'lastmod' => '2024-04-19T06:59:52+00:00',
                'priority' => '0.80',
            ],
            [
                'loc' => 'https://f2i-dev23a-cw-ey-mgv.fr/contact',
                'lastmod' => '2024-04-19T06:59:52+00:00',
                'priority' => '0.80',
            ],
            [
                'loc' => 'https://f2i-dev23a-cw-ey-mgv.fr/registerproprio',
                'lastmod' => '2024-04-19T06:59:52+00:00',
                'priority' => '0.80',
            ],
            [
                'loc' => 'https://f2i-dev23a-cw-ey-mgv.fr/registerloca',
                'lastmod' => '2024-04-19T06:59:52+00:00',
                'priority' => '0.80',
            ],
            [
                'loc' => 'https://f2i-dev23a-cw-ey-mgv.fr/login',
                'lastmod' => '2024-04-19T06:59:52+00:00',
                'priority' => '0.80',
            ],
            [
                'loc' => 'https://f2i-dev23a-cw-ey-mgv.fr/legal',
                'lastmod' => '2024-04-19T06:59:52+00:00',
                'priority' => '0.80',
            ],
            [
                'loc' => 'https://f2i-dev23a-cw-ey-mgv.fr/cgu',
                'lastmod' => '2024-04-19T06:59:52+00:00',
                'priority' => '0.80',
            ],
            [
                'loc' => 'https://f2i-dev23a-cw-ey-mgv.fr/cgv',
                'lastmod' => '2024-04-19T06:59:52+00:00',
                'priority' => '0.80',
            ],
            [
                'loc' => 'https://f2i-dev23a-cw-ey-mgv.fr/faq',
                'lastmod' => '2024-04-19T06:59:52+00:00',
                'priority' => '0.80',
            ],
        ];

        // Rendre le template Twig en lui passant les données nécessaires
        return $this->render('sitemap/index.html.twig', [
            'urls' => $urls,
        ]);
    }
}
