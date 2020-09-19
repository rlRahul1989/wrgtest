<?php

namespace App\Repository;

use App\Entity\DeletedAt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DeletedAt|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeletedAt|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeletedAt[]    findAll()
 * @method DeletedAt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeletedAtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeletedAt::class);
    }

    // /**
    //  * @return DeletedAt[] Returns an array of DeletedAt objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DeletedAt
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
