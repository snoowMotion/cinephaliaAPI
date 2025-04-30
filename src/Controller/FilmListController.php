<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Repository\CinemaRepository;
use App\Repository\FilmRepository;
use App\Repository\GenreRepository;
use App\Repository\SeanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FilmListController extends AbstractController
{
    /**
     * Affiche la liste des films
     * @param Request                $request                 Injection de la requête
     * @param FilmRepository      $filmRepository       Injection du repository de Film
     * @param CinemaRepository $cinemaRepository Injection du repository de Cinema
     * @param GenreRepository   $genreRepository  Injection du repository de Genre
     * @return Response
     */
    #[Route('/filmVisiteur/list', name: 'app_film_list')]
    public function index(
        Request $request,
        FilmRepository $filmRepository,
        CinemaRepository $cinemaRepository,
        GenreRepository $genreRepository
    ): Response
    {
    $cinemas = $cinemaRepository->findAll();
    $genres = $genreRepository->findAll();



        return $this->render('film_list/index.html.twig', [
        'cinemas' => $cinemas,
        'genres' => $genres,
        ]);
    }

    /**
     * Recherche des films en fonction des filtres
     * @param Request                $req                        Injection de la requête
     * @param FilmRepository      $filmRepository       Injection du repository de Film
     * @param SeanceRepository $seanceRepository Injection du repository de Seance
     * @return JsonResponse
     */
    #[Route('/filmVisiteur/list/search', name: 'app_film_list_search', methods: ['GET'])]
    public function filteredSearch(Request $req, FilmRepository $filmRepository, SeanceRepository $seanceRepository): Response
    {
        // Récupération des paramètres de la requête
        $cinemaId = $req->get('cinema');
        $genreId = $req->get('genre');
        $jour = $req->get('jour');
        // Si aucun filtre n'est selectionné, on retourne une erreur
        if (!$cinemaId && !$genreId && !$jour) {
            return new JsonResponse(['error' => 'Aucun filtre n\'a été selectionné'], Response::HTTP_BAD_REQUEST);
        }
        $films = $filmRepository->findFiltered($cinemaId, $genreId, $jour);
        // On construit la réponse
        $rep = [];
        foreach($films as $film) {
            // On récupère les séances du film dans ce cinema
            $seances = $seanceRepository->getSeanceByFilmAndCinema($film->getId(), $cinemaId);
            $seanceData = [];
            /**
             * @var Seance $seance
             */
            foreach ($seances as $seance) {
                $seanceData[] = [
                    'id' => $seance[0]->getId(),
                    'dateDebut' => $seance[0]->getDateDebut(),
                    'dateFin' => $seance[0]->getDateFin(),
                    'qualite' => [
                        'libelle' => $seance['libelle'],
                        'prix' => $seance['prix']
                    ]
                ];
            }
            $rep[] = [
                'id' => $film->getId(),
                'titre' => $film->getTitre(),
                'synopsis' => $film->getSynopsis(),
                'afficheUrl' => $film->getAfficheUrl(),
                'ageMini' => $film->getAgeMini(),
                'label' => $film->getLabel(),
                'noteAvis' => 4,
                'seances' => $seanceData
            ];
        }
        return new JsonResponse($rep, Response::HTTP_OK);
    }


}
