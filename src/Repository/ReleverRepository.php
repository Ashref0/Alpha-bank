<?php

namespace App\Repository;

use App\Entity\Relever;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Relever>
 *
 * @method Relever|null find($id, $lockMode = null, $lockVersion = null)
 * @method Relever|null findOneBy(array $criteria, array $orderBy = null)
 * @method Relever[]    findAll()
 * @method Relever[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReleverRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Relever::class);
    }

//    /**
//     * @return Relever[] Returns an array of Relever objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Relever
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
