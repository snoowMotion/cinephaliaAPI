<?php

namespace App\Repository;

use App\Entity\LinkReservationSiege;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LinkReservationSiege>
 */
class LinkReservationSiegeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LinkReservationSiege::class);
    }

    public function getFirstPlace(int $seanceId, bool $isPmr): ?LinkReservationSiege
    {
        return $this->createQueryBuilder('lrs')
            ->join('lrs.siege', 'si')
            ->where('lrs.seance = :seanceId')
            ->andWhere('si.isPMR = :isPmr')
            ->andWhere('lrs.reservation IS NULL')
            ->setParameter('seanceId', $seanceId)
            ->setParameter('isPmr', $isPmr)
            ->orderBy('lrs.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getNbPlaceDisponible(int $seanceId, bool $isPmr): int
    {
        return $this->createQueryBuilder('lrs')
            ->select('COUNT(lrs)')
            ->join('lrs.siege', 'si')
            ->where('lrs.seance = :seanceId')
            ->andWhere('si.isPMR = :isPmr')
            ->andWhere('lrs.reservation IS NULL')
            ->setParameter('seanceId', $seanceId)
            ->setParameter('isPmr', $isPmr)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return LinkReservationSiege[] Returns an array of LinkReservationSiege objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?LinkReservationSiege
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
