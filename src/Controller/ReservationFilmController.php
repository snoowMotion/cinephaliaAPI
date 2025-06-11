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
use App\Service\ReservationNoSqlService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
                'qualite' => $seance[0]->getSalle()->getQualite()->getLibelle(),
                'prix' => $seance[0]->getSalle()->getQualite()->getPrix(),
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
     * @return RedirectResponse
     */
    #[Route('/reservation/reserve', name: 'reserve', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function reserverSiege(
        Request $request,
        LinkReservationSiegeRepository $linkReservationSiegeRepository,
        SiegeRepository $siegeRepository,
        SeanceRepository $seanceRepository,
        ReservationRepository $reservationRepository,
        EntityManagerInterface $entityManager,
        ReservationNoSQLService $reservationNoSQLService
    ): RedirectResponse
    {
        // On récupère les données de la requête
        $seance = $request->request->get('seance');
        $nbSieges = $request->request->get('nbSieges');
        $nbSiegesPmr = $request->request->get('nbSiegesPMR');

        if (!isset($seance) || !isset($nbSieges) || !isset($nbSiegesPmr)) {
            $this->addFlash('error', 'Données manquantes.');
            return $this->redirectToRoute('app_reservation_film');
        }

        $seance = $seanceRepository->find($seance);
        if (!$seance) {
            $this->addFlash('error', 'Séance introuvable.');
            return $this->redirectToRoute('app_reservation_film');
        }

        // Création de l'entité Reservation
        $reservation = new Reservation();
        $reservation->setClient($this->getUser());
        $reservation->setSeance($seance);
        $reservation->setFinish(false);
        $entityManager->persist($reservation);
        $allSiege = array();
        // Réservation des places normales
        for ($i = 0; $i < $nbSieges; $i++) {
            $link = $linkReservationSiegeRepository->getFirstPlace($seance ->getId(), false);
            if ($link) {
                $allSiege[] = [
                    'salle' => $link->getSiege()->getSalle()->getId(),
                    'numeroSiege' => $link->getSiege()->getNumero()
                ];
            }
            if (!$link) {
                $this->addFlash('error', 'Pas assez de sièges disponibles.');
                return $this->redirectToRoute('app_reservation_film');
            }
            $link->setReservation($reservation);
            $entityManager->persist($link);
            $entityManager->flush();
        }

        // Réservation des places PMR
        for ($i = 0; $i < $nbSiegesPmr; $i++) {
            $link = $linkReservationSiegeRepository->getFirstPlace($seance ->getId(), true);
            if (!$link) {
                $this->addFlash('error', 'Pas assez de sièges PMR disponibles.');
                return $this->redirectToRoute('app_reservation_film');
            }
            if ($link) {
                $allSiege[] = [
                    'salle' => $link->getSiege()->getSalle()->getId(),
                    'numeroSiege' => $link->getSiege()->getNumero()
                ];
            }
            $link->setReservation($reservation);
            $entityManager->persist($link);
            $entityManager->flush();
        }
        $entityManager->flush();
        $this->addFlash('success', 'Votre réservation a été effectuée avec succès !');
        $noSqlArray = array();
        $noSqlArray["userId"] = $this->getUser()->getId();
        $noSqlArray["filmId"] = $seance->getFilm()->getId();
        $noSqlArray["seanceId"] = $seance->getId();
        $noSqlArray["cinema"] = $seance->getSalle()->getCinema()->getId();
        $noSqlArray["places"] = $allSiege;
        $noSqlArray["qualite"] = $seance->getSalle()->getQualite()->getLibelle();
        $noSqlArray["prixTotal"] = $seance->getSalle()->getQualite()->getPrix() * ($nbSieges + $nbSiegesPmr);
        $this->SaveRegisistration($reservationNoSQLService, $noSqlArray);
        return $this->redirectToRoute('app_my_space');
    }


    public function SaveRegisistration(ReservationNoSQLService $service, array $data): bool
    {
        // Exemple de données à enregistrer
        $reservation = $service->enregistrerReservation([
            'userId' => $data["userId"],
            'filmId' => $data['filmId'],
            'seanceId' => $data['seanceId'],
            'cinema' => $data['cinema'],
            'places' => $data['places'],
            'qualite' => $data['qualite'],
            'prixTotal' => $data['prixTotal'],
        ]);

        return true;
    }
}
