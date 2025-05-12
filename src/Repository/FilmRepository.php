<?php

namespace App\Repository;

use App\Entity\Cinema;
use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Film>
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    /**
     * Permet de récupérer les films par cinéma a venir
     * @param Cinema  $id  Cinéma dont on souhaite récupérer les films
     * @return Film[]
     */
    public function getFuturFilmByCinema(int $cinemaId): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT DISTINCT f
        FROM App\Entity\Film f
        JOIN f.seances s
        JOIN s.salle sa
        JOIN sa.cinema c
        WHERE c.id = :cinemaId
        AND s.dateDebut > CURRENT_TIMESTAMP()
        ORDER BY f.titre ASC'
        );

        $query->setParameter('cinemaId', $cinemaId);

        return $query->getResult();
    }

    public function findFiltered(?int $cinemaId, ?int $genreId, ?string $jour): array
    {
        $qb = $this->createQueryBuilder('f')
            ->join('f.seances', 's')
            ->join('f.genre', 'g')
            ->join('s.salle', 'sa')
            ->join('sa.cinema', 'c')
            ->groupBy('f.id');

        if ($cinemaId) {
            $qb->andWhere('c.id = :cinemaId')
                ->setParameter('cinemaId', $cinemaId);
        }

        if ($genreId) {
            $qb->andWhere('g.id = :genreId')
                ->setParameter('genreId', $genreId);
        }

        if ($jour) {
            $date = new \DateTimeImmutable($jour);
            $qb->andWhere('DATE(s.dateHeureDebut) = :jour')
                ->setParameter('jour', $date->format('Y-m-d'));
        }
        $qb->andWhere('s.dateDebut > CURRENT_TIMESTAMP()')
            ->orderBy('f.titre', 'ASC');
        return $qb->getQuery()->getResult();
    }

    public function getNoteMoyenneByFilm(Film $film): float
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT AVG(a.note) as noteMoyenne
            FROM App\Entity\Avis a
            JOIN a.reservation r
            JOIN r.seance s
            WHERE s.film = :film'
        )->setParameter('film', $film);

        return (float) $query->getSingleScalarResult();
    }

    //    /**
    //     * @return Film[] Returns an array of Film objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Film
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
