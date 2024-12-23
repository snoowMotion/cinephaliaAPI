<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\LinkReservationSiege;
use App\Entity\Salle;
use App\Entity\Seance;
use App\Entity\Siege;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SeanceController extends AbstractController
{
    /**
     * Route pour créer une séance
     * @param Request                         $request            Requête
     * @param EntityManagerInterface $entityManager Gestionnaire d'entité
     * @return JsonResponse
     */
    #[Route('/api/seances', name: 'create_seance', methods: ['POST'])]
    #[IsGranted(new Expression('is_granted("ROLE_API_ECRITURE") or is_granted("ROLE_ADMIN")'))]
    public function createSeance(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // On récupère les données de la requête
        $data = json_decode($request->getContent(), true);
        // On vérifie que la data est bien complète
        if (empty($data['film']) || empty($data['salle']) || empty($data['dateDebut']) || empty($data['dateFin'])) {
            return new JsonResponse(['status' => 'Paramètre absent'], Response::HTTP_BAD_REQUEST);
        }
        // On vérifie que le film existe
        $film = $entityManager->getRepository(Film::class)->find($data['film']);
        if (empty($film)) {
            return new JsonResponse(['status' => 'Film introuvable'], Response::HTTP_NOT_FOUND);
        }
        // On vérifie que la salle existe
        $salle = $entityManager->getRepository(Salle::class)->find($data['salle']);
        if (empty($salle)) {
            return new JsonResponse(['status' => 'Salle introuvable'], Response::HTTP_NOT_FOUND);
        }
        // On vérifie le format des dates
        $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $data['dateDebut']);
        $dateFin = DateTime::createFromFormat('Y-m-d H:i:s', $data['dateFin']);
        if ($dateDebut === false || $dateFin === false) {
            return new JsonResponse(['status' => 'Format de date invalide'], Response::HTTP_BAD_REQUEST);
        }
        // On vérifie que la date de début est bien avant la date de fin
        if ($dateDebut >= $dateFin) {
            return new JsonResponse(['status' => 'Date de début après la date de fin'], Response::HTTP_BAD_REQUEST);
        }
        // On vérifie qu'il n'existe pas déjà une séance pour ce film dans cette salle à cette date et cette heure
        $seanceRepository = $entityManager->getRepository(Seance::class);
        if ($seanceRepository->isSeanceExists($film->getId(), $dateDebut, $dateFin)) {
            return new JsonResponse(['status' => 'Séance déjà existante'], Response::HTTP_CONFLICT);
        }
        // On crée la séance
        $seance = new Seance();
        $seance->setFilm($film);
        $seance->setSalle($salle);
        $seance->setDateDebut($dateDebut);
        $seance->setDateFin($dateFin);
        // On initialise la transaction
        $entityManager->beginTransaction();
        try {
            // On crée les liens entre les sièges et la séance
            for ($i = 0; $i < $salle->getNbPlace(); $i++) {
                $siege = $entityManager->getRepository(Siege::class)->findOneBy(['salle' => $salle, 'numero' => $i]);
                $linkSeanceSiege = new LinkReservationSiege();
                $linkSeanceSiege->setSiege($siege);
                $entityManager->persist($linkSeanceSiege);
            }
            // On créer les liens entre les places PMR et la séance
            for ($i = 0; $i < $salle->getNbPlacePmr(); $i++) {
                $siege = $entityManager->getRepository(Siege::class)->findOneBy(['salle' => $salle, 'numero' => $i]);
                $linkSeanceSiege = new LinkReservationSiege();
                $linkSeanceSiege->setSiege($siege);
                $entityManager->persist($linkSeanceSiege);
            }
            $entityManager->persist($seance);
            $entityManager->flush();
            $entityManager->commit();
            return new JsonResponse(['status' => 'Séance crée!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            $entityManager->rollback();
            return new JsonResponse(['status' => 'Erreur lors de la création de la séance', "e" => $e ->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
