<?php

namespace App\Repository;

use App\Entity\Facility;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Facility>
 *
 * @method Facility|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facility|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facility[]    findAll()
 * @method Facility[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facility::class);
    }

  /**
     * Recherche les installations par leur nom.
     *
     * @param string $name Le nom de l'installation à rechercher.
     * @return array Un tableau d'installations correspondantes.
     */
    public function findByFacilityName($name): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult();

    }

    }
        












        

    //    public function findOneBySomeField($value): ?Facility
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

