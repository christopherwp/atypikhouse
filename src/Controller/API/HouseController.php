<?php

namespace App\Controller\API;

use App\Serializer\ApiSerializer;
use App\Repository\HouseRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/houses')]
class HouseController extends AbstractController
{

    public function __construct(private HouseRepository $houseRepository, private ApiSerializer $apiSerializer)
    {
    }

    #[Route('/', name: 'api.house.get', methods: ['GET'])]
    public function index(): Response
    {
        /*$normalizers = [new ObjectNormalizer()];
        $encoders = [new JsonEncoder()];*/

        $result = $this->apiSerializer->serialize(
            $this->houseRepository->findAll(),

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
    #[Route('/', name: 'api.house.get', methods: ['GET'])]
    public function index(): Response
    {
        /*$normalizers = [new ObjectNormalizer()];
        $encoders = [new JsonEncoder()];*/

        $result = $this->apiSerializer->serialize(
            $this->houseRepository->findAll(),

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
