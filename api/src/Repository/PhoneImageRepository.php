<?php
// api\src\Repository\PhoneImageRepository.php

namespace App\Repository;

use App\Entity\PhoneImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PhoneImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhoneImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhoneImage[]    findAll()
 * @method PhoneImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhoneImage::class);
    }

    // /**
    //  * @return PhoneImage[] Returns an array of PhoneImage objects
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
    public function findOneBySomeField($value): ?PhoneImage
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
