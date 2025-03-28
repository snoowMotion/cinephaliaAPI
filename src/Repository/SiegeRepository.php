<?php

namespace App\Repository;

use App\Entity\Siege;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Siege>
 */
class SiegeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Siege::class);
    }

    public function getNbSiegeDispo(int $salleId, $isPmr): int
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('COUNT(s.id)')
            ->where('s.salle = :salleId')
            ->andWhere('s.reservation IS NULL')
            ->setParameter('salleId', $salleId);

            $qb->andWhere('s.isPmr = :isPmr')
                ->setParameter('isPmr', $isPmr);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }



//    /**
//     * @return Siege[] Returns an array of Siege objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Siege
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
