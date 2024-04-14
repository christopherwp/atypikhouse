<?php

namespace App\Repository;

use App\Entity\Rent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<Rent>
 *
 * @method Rent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rent[]    findAll()
 * @method Rent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rent::class);
    }

    // récupérer les locations par locataire
 //   public function findPaidRentsByLocataire(User $locataire): array
 //   {
 //       return $this->createQueryBuilder('r')
 //           ->andWhere('r.locataire = :locataire')
 //           ->andWhere('r.isPaid = :isPaid')
 //           ->setParameter('locataire', $locataire)
 //           ->setParameter('isPaid', true)
//            ->getQuery()
//            ->getResult();
//    }

    // récupérer les locations par proprietaire
//    public function findPaidRentsByProprietaire(User $proprietaire): array
//    {
//      return $this->createQueryBuilder('r')
//            ->andWhere('r.proprietaire = :proprietaire')
//            ->andWhere('r.isPaid = :isPaid')
//            ->setParameter('proprietaire', $proprietaire)
//            ->setParameter('isPaid', true)
//            ->getQuery()
//            ->getResult();
//    }
    //    public function findRentsWithUsersByProprietaire(User $proprietaire)
    //    {
    //        return $this->createQueryBuilder('r')
    //           ->join('r.user', 'u')
    //            ->addSelect('u')
    //            ->where('r.proprietaire = :proprietaire')
    //            ->setParameter('proprietaire', $proprietaire)
    //            ->getQuery()
    //            ->getResult();
    //    }

    // récupérer les locations - dashboardAdmin
//    public function findAllPaidRents(): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.isPaid = :isPaid')
//            ->setParameter('isPaid', true)
//            ->getQuery()
//            ->getResult();
//    }

}
