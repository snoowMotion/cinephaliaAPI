<?php

namespace App\Controller;

use App\Entity\LinkReservationSiege;
use App\Entity\Seance;
use App\Entity\Siege;
use App\Form\SeanceType;
use App\Repository\SeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/seance/crud')]
final class SeanceCrudController extends AbstractController
{
    #[Route(name: 'app_seance_crud_index', methods: ['GET'])]
    public function index(SeanceRepository $seanceRepository): Response
    {
        return $this->render('seance_crud/index.html.twig', [
            'seances' => $seanceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_seance_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $seance = new Seance();
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($seance);
            $entityManager->flush();

            // Associate seats with the session
            $salle = $seance->getSalle();
            for ($i = 0; $i < $salle->getNbPlace(); $i++) {
                $siege = $entityManager->getRepository(Siege::class)->findOneBy(['salle' => $salle, 'numero' => $i]);
                $linkSeanceSiege = new LinkReservationSiege();
                $linkSeanceSiege->setSeance($seance);
                $linkSeanceSiege->setSiege($siege);
                $entityManager->persist($linkSeanceSiege);
            }

            for ($i = 0; $i < $salle->getNbPlacePmr(); $i++) {
                $siege = $entityManager->getRepository(Siege::class)->findOneBy(['salle' => $salle, 'numero' => $i]);
                $linkSeanceSiege = new LinkReservationSiege();
                $linkSeanceSiege->setSeance($seance);
                $linkSeanceSiege->setSiege($siege);
                $entityManager->persist($linkSeanceSiege);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_seance_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('seance_crud/new.html.twig', [
            'seance' => $seance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_seance_crud_show', methods: ['GET'])]
    public function show(Seance $seance): Response
    {
        return $this->render('seance_crud/show.html.twig', [
            'seance' => $seance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_seance_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Seance $seance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_seance_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('seance_crud/edit.html.twig', [
            'seance' => $seance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_seance_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Seance $seance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seance->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($seance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_seance_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
