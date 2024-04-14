<?php

namespace App\DataFixtures;

use App\Entity\House;
use App\Entity\Media;
use Random\Randomizer;
use App\Entity\Company;
use App\Entity\User;
use App\Entity\Rent;
use App\Entity\Category;
use App\Entity\Facility;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\HouseRepository;

class AppFixtures extends Fixture
{
    private $houseRepository;

    public function __construct( private readonly UserPasswordHasherInterface $hasher,private readonly SluggerInterface $slugger, \App\Repository\HouseRepository $houseRepository )
    {
        $this->houseRepository = $houseRepository;
        // LE CONSTRUCTEUR RETOURNE UN OBJET
    }
    public function load(ObjectManager $manager): void
    {
        $user = new User();
       
        $user->setRoles(['ROLE_ADMIN'])      
            ->setEmail('admin@example.com')
            ->setUsername('admin')
            ->setPassword($this->hasher->hashPassword($user, 'admin1234'));
            $manager->persist($user);
            
        // $product = new Product();
        // $manager->persist($product);
        $company = new company();
        $company
            ->setAddress('1 rue des champs 75000 Paris')
            ->setDescription('atypik house vous propose des logements atypiques')
            ->setEmail('atypik@hotmail.fr')
            ->setHosting('Hostinger');
          
        $manager->persist($company);

      
;
        $categories =  ['Cabanes', 'Cabanes perchées', 'Maisons cycladiques', 'Tiny houses', 'Chateaux'];
        $listCategories = [];

        foreach ($categories as $key => $value) {
            $category = new Category();
            $category
                ->setName($value)
                ->setSlug($this->slugger->slug($value));
            array_push($listCategories, $category);

            $manager->persist($category);
        }

        $facilities =  ['Sèches-cheveux', 'Wifi', 'Baignoire', 'Climatisation', 'Parking', 'PS5', 'Eau chaude'];
        $listFacilities = [];

        foreach ($facilities as $key => $value) {
            $facility = new Facility();
            $facility->setName($value);
            array_push($listFacilities, $facility);

            $manager->persist($facility);
        }

        $medias = [
            '1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg',
            '1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg',
            '1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg',
            '1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg',
            '1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg',
            '1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg',
            '1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg',
            '1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg'
        ];
        $listMedias = [];

        foreach ($medias as $key => $value) {
            $media = new Media();
            $media
                ->setName($value)
                ->setDescription("Description $value");

            array_push($listMedias, $media);
            $manager->persist($media);
        }


        for ($i = 0; $i < 8; $i++) {
            $house = new House();
            $house
                ->setTitle("House $i")
                ->setLatitude((new Randomizer())->getInt(0, 90))
                ->setLongitude((new Randomizer())->getInt(-180, 180))
                ->setAddress("Adress $i")
                ->setCapacity((new Randomizer())->getInt(1, 5))
                ->setNumRooms((new Randomizer())->getInt(1, 5))
                ->setNumBedrooms((new Randomizer())->getInt(1, 3))
                ->setNumBathrooms((new Randomizer())->getInt(1, 3))
                ->setDailyPrice((new Randomizer())->getInt(20, 300))
                ->setDescription("Description House $i")
                ->setCategory($listCategories[(new Randomizer())->getInt(0, count($listCategories) - 1)])
                ->setActif(true)
                ->setOwner($user);
            //->addMedium($listMedias[$i]);

            for ($j = ($i * 8); $j < (($i + 1) * 8); $j++) {
                $house->addMedium($listMedias[$j]);
            }

            foreach ($listMedias as $key => $value) {
                $house->addMedium($value);
            }

            $manager->persist($house);
        }

        $house = $this->houseRepository->find(1); 
        $rent = new rent();
        $rent->setHouse($house)
            ->setUserId($user)
            ->setStartDate(new \DateTimeImmutable('2022-01-01'))
            ->setNumDays(30)
            ->setTotalPrice(1000);
            
            $manager->persist($rent);
        


        $manager->flush();
    }
}
