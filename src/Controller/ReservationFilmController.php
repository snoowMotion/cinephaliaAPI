<?php

namespace App\Controller;

use App\Entity\Cinema;
use App\Entity\Reservation;
use App\Repository\CinemaRepository;
use App\Repository\FilmRepository;
use App\Repository\LinkReservationSiegeRepository;
use App\Repository\ReservationRepository;
use App\Repository\SeanceRepository;
use App\Repository\SiegeRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ReservationFilmController extends AbstractController
{
    #[Route('/reservation/film', name: 'app_reservation_film')]
    #[IsGranted('ROLE_USER')]
    public function index(CinemaRepository $cinemaRepository, Request $request): Response
    {
        // Lecture des valeurs envoyées par POST ou GET
        $cinemaId = $request->get('cinemaId');
        $filmId = $request->get('filmId');
        $seanceId = $request->get('seanceId');
        return $this->render('reservation_film/index.html.twig', [
            'cinemas' => $cinemaRepository->findAll(),
            'cinemaId' => $cinemaId,
            'filmId' => $filmId,
            'seanceId' => $seanceId
        ]);
    }

    /**
     * Permet de récupérer les films par cinéma
     * @param FilmRepository $filmRepo Injection du repository Film
     * @param Request           $request  Injection de la requête
     * @return JsonResponse
     */
    #[Route('/reservation/getFilmByCinema', name: 'getFilmByCinema', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getFilmByCinema(FilmRepository $filmRepo, Request $request,CinemaRepository $cinemaRepository ): JsonResponse
    {
       // On récupère l'id du cinema
        $id = $request->query->get('cinemaId');
        // On vérifie que le cinema existe
        $cinema = $cinemaRepository->find($id);
        if(!$cinema){
            return new JsonResponse(['message' => 'Cinema not found'], Response::HTTP_NOT_FOUND);
        }
        // On récupère les séances à venir du cinema
        $films = $filmRepo->getFuturFilmByCinema($id);
        // On construit la réponse
        $rep = [];
        foreach ($films as $film){
            $rep[] = [
                'id' => $film->getId(),
                'titre' => $film->getTitre(),
            ];
        }
        return new JsonResponse($rep, Response::HTTP_OK);
    }

    /**
     * Permet de récupérer les séances par film et par cinéma
     * @param SeanceRepository $seanceRepo Injection du repository Seance
     * @param Request           $request  Injection de la requête
     * @return JsonResponse
     */
    #[Route('/reservation/getSeanceByFilmAndCinema', name: 'getSeanceByFilmAndCinema', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getSeanceByFilmAndCinema(SeanceRepository $seanceRepo,LinkReservationSiegeRepository $siegeRepository,  CinemaRepository $cineRepo, FilmRepository $filmRepo,  Request $request): JsonResponse
    {
        // On récupère l'id du cinema
        $cinemaId = $request->query->get('cinemaId');
        // On vérifie que le cinema existe
        $cinema = $cineRepo->find($cinemaId);
        if(!$cinema){
            return new JsonResponse(['message' => 'Cinema not found'], Response::HTTP_NOT_FOUND);
        }
        // On récupère l'id du film
        $filmId = $request->query->get('filmId');
        // On vérifie que le film existe
        $film = $filmRepo->find($filmId);
        if(!$film){
            return new JsonResponse(['message' => 'Film not found'], Response::HTTP_NOT_FOUND);
        }
        // On récupère les séances à venir du cinema
        $seances = $seanceRepo->getSeanceByFilmAndCinema($filmId, $cinemaId);
        // On construit la réponse
        $ret = [];
        foreach ($seances as $seance){
            //On récupère le nombre de siège disponible
            $nbSiege = $siegeRepository->getNbPlaceDisponible($seance[0]->getId(), false);
            $nbSiegePmr = $siegeRepository->getNbPlaceDisponible($seance[0]->getId(), true);

            $ret[] = [
                'id' => $seance[0]->getId(),
                'dateDebut' => $seance[0]->getDateDebut(),
                'dateFin' => $seance[0]->getDateFin(),
                'nbSiege' => $nbSiege,
                'nbSiegePmr' => $nbSiegePmr,
                'qualite' => $seance[0]->getSalle()->getQualite()->getLibelle()
            ];
        }
        return new JsonResponse($ret, Response::HTTP_OK);
    }

    /**
     * Permet de réserver des sièges
     * @param Request $request Injection de la requête
     * @param LinkReservationSiegeRepository $linkReservationSiegeRepository Injection du repository LinkReservationSiege
     * @param SiegeRepository $siegeRepository Injection du repository Siege
     * @param SeanceRepository $seanceRepository Injection du repository Seance
     * @return JsonResponse
     */
    #[Route('/reservation/reserve', name: 'reserve', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function reserverSiege(
        Request $request,
        LinkReservationSiegeRepository $linkReservationSiegeRepository,
        SiegeRepository $siegeRepository,
        SeanceRepository $seanceRepository,
        ReservationRepository $reservationRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        // On récupère les données de la requête
        $seance = $request->request->get('seance');
        $nbSieges = $request->request->get('nbSieges');
        $nbSiegesPmr = $request->request->get('nbSiegesPMR');

        if (!isset($seance) || !isset($nbSieges) || !isset($nbSiegesPmr)) {
            return new JsonResponse(['message' => 'Missing data'], Response::HTTP_BAD_REQUEST);
        }

        $seance = $seanceRepository->find($seance);
        if (!$seance) {
            return new JsonResponse(['message' => 'Seance not found'], Response::HTTP_NOT_FOUND);
        }

        // Création de l'entité Reservation
        $reservation = new Reservation();
        $reservation->setClient($this->getUser());
        $reservation->setSeance($seance);
        $reservation->setFinish(false);
        $entityManager->persist($reservation);

        // Réservation des places normales
        for ($i = 0; $i < $nbSieges; $i++) {
            $link = $linkReservationSiegeRepository->getFirstPlace($seance ->getId(), false);
            if (!$link) {
                return new JsonResponse(['message' => 'Not enough normal seats available'], Response::HTTP_BAD_REQUEST);
            }
            $link->setReservation($reservation);
            $entityManager->persist($link);
            $entityManager->flush();
        }

        // Réservation des places PMR
        for ($i = 0; $i < $nbSiegesPmr; $i++) {
            $link = $linkReservationSiegeRepository->getFirstPlace($seance ->getId(), true);
            if (!$link) {
                return new JsonResponse(['message' => 'Not enough PMR seats available'], Response::HTTP_BAD_REQUEST);
            }
            $link->setReservation($reservation);
            $entityManager->persist($link);
            $entityManager->flush();
        }
        $entityManager->flush();
        return new JsonResponse(['message' => 'Reservation completed successfully'], Response::HTTP_OK);
    }
}
