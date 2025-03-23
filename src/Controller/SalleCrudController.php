<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Entity\Siege;
use App\Form\SalleType;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/salle/crud')]
final class SalleCrudController extends AbstractController
{
    #[Route(name: 'app_salle_crud_index', methods: ['GET'])]
    public function index(SalleRepository $salleRepository): Response
    {
        return $this->render('salle_crud/index.html.twig', [
            'salles' => $salleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_salle_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $salle = new Salle();
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($salle);
            $entityManager->flush();

            // Create as many Siege entities as there are places
            for ($i = 0; $i < $salle->getNbPlace(); $i++) {
                $siege = new Siege();
                $siege->setNumero($i);
                $siege->setSalle($salle);
                $siege->setPMR(false);
                $entityManager->persist($siege);
            }

            // Create as many Siege entities as there are PMR places
            for ($i = 0; $i < $salle->getNbPlacePmr(); $i++) {
                $siege = new Siege();
                $siege->setNumero($i);
                $siege->setSalle($salle);
                $siege->setPMR(true);
                $entityManager->persist($siege);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_salle_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('salle_crud/new.html.twig', [
            'salle' => $salle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salle_crud_show', methods: ['GET'])]
    public function show(Salle $salle): Response
    {
        return $this->render('salle_crud/show.html.twig', [
            'salle' => $salle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_salle_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Salle $salle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Remove existing Siege entities
            foreach ($salle->getSieges() as $siege) {
                $entityManager->remove($siege);
            }
            $entityManager->flush();

            // Create new Siege entities based on updated number of places
            for ($i = 0; $i < $salle->getNbPlace(); $i++) {
                $siege = new Siege();
                $siege->setNumero($i);
                $siege->setSalle($salle);
                $siege->setPMR(false);
                $entityManager->persist($siege);
            }

            // Create new Siege entities based on updated number of PMR places
            for ($i = 0; $i < $salle->getNbPlacePmr(); $i++) {
                $siege = new Siege();
                $siege->setNumero($i);
                $siege->setSalle($salle);
                $siege->setPMR(true);
                $entityManager->persist($siege);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_salle_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('salle_crud/edit.html.twig', [
            'salle' => $salle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salle_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Salle $salle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($salle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_salle_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
