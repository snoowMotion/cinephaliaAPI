<?php

namespace App\Repository;

use App\Entity\Seance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Seance>
 */
class SeanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seance::class);
    }

    /**
     * Check if there is already a session of the same film during the time interval
     * @param int $filmId ID of the film
     * @param \DateTimeInterface $dateDebut Start date and time
     * @param \DateTimeInterface $dateFin End date and time
     * @return bool
     */
    public function isSeanceExists(int $filmId, \DateTimeInterface $dateDebut, \DateTimeInterface $dateFin): bool
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('COUNT(s.id)')
            ->where('s.film = :filmId')
            ->andWhere('s.dateDebut < :dateFin')
            ->andWhere('s.dateFin > :dateDebut')
            ->setParameter('filmId', $filmId)
            ->setParameter('dateDebut', $dateDebut)
            ->setParameter('dateFin', $dateFin);

        return (int) $qb->getQuery()->getSingleScalarResult() > 0;
    }

    public function getSeanceByFilmAndCinema(int $filmId, int $cinemaId): array
    {
        return $this->createQueryBuilder('s')
            ->join('s.film', 'f')
            ->join('s.salle', 'sa')
            ->join('sa.cinema', 'c')
            ->join('sa.qualite', 'q')
            ->andWhere('f.id = :filmId')
            ->andWhere('c.id = :cinemaId')
            ->setParameter('filmId', $filmId)
            ->setParameter('cinemaId', $cinemaId)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Seance[] Returns an array of Seance objects
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

//    public function findOneBySomeField($value): ?Seance
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
