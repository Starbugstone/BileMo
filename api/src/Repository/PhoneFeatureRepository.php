<?php

namespace App\Repository;

use App\Entity\PhoneFeature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhoneFeature|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhoneFeature|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhoneFeature[]    findAll()
 * @method PhoneFeature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneFeatureRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhoneFeature::class);
    }

    // /**
    //  * @return PhoneFeature[] Returns an array of PhoneFeature objects
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
    public function findOneBySomeField($value): ?PhoneFeature
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
