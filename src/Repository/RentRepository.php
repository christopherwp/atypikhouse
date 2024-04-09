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

    // récupérer les locations par user
    public function findPaidRentsByUser(User $user)
    {
        return $this->createQueryBuilder('r')
        ->andWhere('r.user = :user')
        ->andWhere('r.isPaid = :isPaid')
        ->setParameter('user', $user)
        ->setParameter('isPaid', true)
        ->getQuery()
        ->getResult();
        
    }
}