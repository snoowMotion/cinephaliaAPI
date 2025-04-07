<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\AvisRepository;
use App\Repository\LinkReservationSiegeRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MySpaceController extends AbstractController
{
    #[Route('/mon-espace', name: 'app_my_space')]
    #[IsGranted('ROLE_USER')]
    public function index(ReservationRepository $reservationRepository, Security $security, LinkReservationSiegeRepository $linkRepo, AvisRepository $avisRepo): Response
    {
        /** @var User $user */
        $user = $security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $reservations = $reservationRepository->findBy(['client' => $user]);
        // On construit le retour
        $rep = [];
        foreach ($reservations as $reservation) {
            $seance = $reservation->getSeance();
            $film = $seance->getFilm();
            $salle = $seance->getSalle();
            // On récupère les sièges réservés
            $sieges = $linkRepo->findBy(['reservation' => $reservation]);
            // On récupère le prix total (nbSiege * Qualite.prix)
            $prix = 0;
            foreach ($sieges as $siege) {
                $prix += $salle->getQualite()->getPrix();
            }
            //On recupère les avis si ils existent
            $avis = $avisRepo->findBy(['reservation' => $reservation]);

            // On construit la réponse
            $rep[] = [
                'id' => $reservation->getId(),
                'film' => $film->getTitre(),
                'seance' => $seance->getDateDebut()->format('Y-m-d H:i:s'),
                'numSalle' => $salle->getNumSalle(),
                'qualite' => $salle->getQualite()->getLibelle(),
                'sieges' => $sieges,
                'prix' => $prix,
                'avis' => $avis,
            ];
        }

        return $this->render('my_space/index.html.twig', [
            'reservations' => $rep,
        ]);
    }
    #[Route('/mon-espace/noter/{id}', name: 'noter_reservation', methods: ['POST'])]
    public function noterReservation(
        Request $request,
        EntityManagerInterface $em,
        Security $security,
        ReservationRepository $reservationRepository,
        Reservation $id
    ): Response {
        /** @var User $user */
        $user = $security->getUser();

        if (!$id) {
            throw $this->createNotFoundException('Reservation not found');
        }
        if (!$user || $id->getClient() !== $user) {
            throw $this->createAccessDeniedException();
        }

        $seance = $id->getSeance();
        $now = new \DateTime();

        if ($seance->getDateFin() > $now) {
            $this->addFlash('danger', 'Vous ne pouvez noter que les séances passées.');
            return $this->redirectToRoute('app_my_space');
        }

        $note = $request->request->get('note');
        $commentaire = $request->request->get('commentaire');

        // Créer l'objet Note (ou Avis) si tu as une entité dédiée
        $avis = new Avis();
        $avis->setNote($note);
        $avis->setCommentaire($commentaire);

        $avis->setUtilisateur($user);
        $avis->setReservation($id);
        $avis->setCreatedAt(new \DateTimeImmutable());



        $em->persist($avis);
        $em->flush();

        $this->addFlash('success', 'Merci pour votre retour ! Votre avis sera publié après validation.');

        return $this->redirectToRoute('app_my_space');
    }
}
