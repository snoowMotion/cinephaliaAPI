<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\LinkReservationSiege;
use App\Repository\LinkReservationSiegeRepository;
use App\Repository\ReservationRepository;
use App\Repository\SeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\LockMode;

class ReservationController extends AbstractController
{
    #[Route('/api/reservations', name: 'create_reservation', methods: ['POST'])]
    public function createReservation(Request $request, EntityManagerInterface $entityManager, SeanceRepository $seanceRepository, LinkReservationSiegeRepository $lrsRepo): JsonResponse
    {
        // On récupère les données de la requête
        $data = json_decode($request->getContent(), true);
        $seanceId = $data['seance'];
        $nbSiege = $data['nbSiege'];
        $nbSiegePmr = $data['nbSiegePmr'];
        $client = $this->getUser();
        // On vérifie que la data est bien complète
        if (empty($seanceId) || empty($nbSiege) || empty($nbSiegePmr)) {
            return new JsonResponse(['error' => 'Paramètre absent'], 400);
        }
        // On vérifie que la séance existe
        $seance = $seanceRepository->find($seanceId);
        if (!$seance) {
            return new JsonResponse(['error' => 'Seance introuvable'], 404);
        }
        // On commence la transaction
        $entityManager->beginTransaction();
        try {
            // On vérifie que le nombre de place réservées ne dépasse pas le nombre de place disponible
            $repository = $entityManager->getRepository(Reservation::class);
            $nbSiegeLibre = $lrsRepo->getNbPlaceDisponible($seanceId, false);
            $nbSiegePmrLibre = $lrsRepo->getNbPlaceDisponible($seanceId, true);
            if ($nbSiegeLibre < $nbSiege) {
                return new JsonResponse(['error' => 'Nombre de place reservées dépasse le nombre de place disponible'], 400);
            }

            if ($nbSiegePmrLibre < $nbSiegePmr ) {
                return new JsonResponse(['error' => 'Nombre de place PMR reservées dépasse le nombre de place PMR disponible'], 400);
            }
            // On crée la réservation
            $reservation = new Reservation();
            $reservation->setClient($client);
            $reservation->setSeance($seance);
            $reservation->setFinish(false);
            // On crée les liens entre les sièges et la réservation
            for ($i = 0; $i < $nbSiege; $i++) {

                $lrs = $lrsRepo->getFirstPlace($seanceId, false);
                if (!$lrs) {
                    throw new \Exception('No available seats');
                }

                $entityManager->lock($lrs, LockMode::PESSIMISTIC_WRITE);
                $lrs->setReservation($reservation);
                $entityManager->persist($lrs);
            }
            // On crée les liens entre les sièges PMR et la réservation
            for ($i = 0; $i < $nbSiegePmr; $i++) {

                $lrs = $lrsRepo->getFirstPlace($seanceId, true);
                if (!$lrs) {
                    throw new \Exception('No available seats PMR');
                }

                $entityManager->lock($lrs, LockMode::PESSIMISTIC_WRITE);
                $lrs->setReservation($reservation);
                $entityManager->persist($lrs);
            }

            $entityManager->persist($reservation);
            $entityManager->flush();
            $entityManager->commit();

            return new JsonResponse(['message' => 'Reservation crée avec succès'], 201);
        } catch (\Exception $e) {
            $entityManager->rollback();
            return new JsonResponse(['error' => 'Erreur lors de la création de la réservation', "e" => $e -> getMessage()], 500);
        }
    }
}
