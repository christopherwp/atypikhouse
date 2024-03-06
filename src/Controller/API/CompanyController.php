<?php

namespace App\Controller\API;

use App\Repository\CompanyRepository;
use App\Serializer\ApiSerializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/company')]
class CompanyController extends AbstractController
{

    public function __construct(private CompanyRepository $companyRepository, private ApiSerializer $apiSerializer)
    {
    }

    #[Route('/', name: 'api.company.get', methods: ['GET'])]
    public function index(): Response
    {
        /*$normalizers = [new ObjectNormalizer()];
        $encoders = [new JsonEncoder()];*/

        $result = $this->apiSerializer->serialize(
            $this->companyRepository->findAll()[0],

        );
        //dd($this->companyRepository->find(1));
        return new Response(
            $result,
            200,
            [
                'Content-type' => 'application/json'
            ]
        );
    }
}
