<?php

namespace App\Repository;

use App\Entity\DocumentLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DocumentLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentLog[]    findAll()
 * @method DocumentLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentLog::class);
    }

    // /**
    //  * @return DocumentLog[] Returns an array of DocumentLog objects
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
    public function findOneBySomeField($value): ?DocumentLog
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
