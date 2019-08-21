<?php
// api\src\Repository\PhoneHasFeatureRepository.php

namespace App\Repository;

use App\Entity\PhoneHasFeature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhoneHasFeature|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhoneHasFeature|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhoneHasFeature[]    findAll()
 * @method PhoneHasFeature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneHasFeatureRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhoneHasFeature::class);
    }

    // /**
    //  * @return PhoneHasFeature[] Returns an array of PhoneHasFeature objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PhoneHasFeature
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
