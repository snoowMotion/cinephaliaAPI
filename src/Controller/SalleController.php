<?php

namespace App\Controller;

use App\Entity\Cinema;
use App\Entity\Qualite;
use App\Entity\Salle;
use App\Entity\Siege;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SalleController
{
    #[Route('/api/salles', name: 'create_salle', methods: ['POST'])]
    #[IsGranted(new Expression('is_granted("ROLE_API_ECRITURE") or is_granted("ROLE_ADMIN")'))]


    public function createSalle(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // On vérifie que la data est bien complète
        if (empty($data['cinema']) || empty($data['nbPlace']) || empty($data['numSalle']) || empty($data['qualite']) || empty($data['nbPlacePmr'])) {
            return new JsonResponse(['status' => 'Paramètre absent'], Response::HTTP_BAD_REQUEST);
        }
        // On vérifie que le cinema existe
        $cinema = $entityManager->getRepository(Cinema::class)->find($data['cinema']);
        if (empty($cinema)) {
            return new JsonResponse(['status' => 'Cinema not found'], Response::HTTP_NOT_FOUND);
        }
        // On vérifie que la qualité existe
        $qualite = $entityManager->getRepository(Qualite::class)->find($data['qualite']);
        if (empty($qualite)) {
            return new JsonResponse(['status' => 'Qualite not found'], Response::HTTP_NOT_FOUND);
        }
        // On crée la salle
        $salle = new Salle();
        $salle->setCinema($cinema);
        $salle->setNbPlace($data['nbPlace']);
        $salle->setNumSalle($data['numSalle']);
        $salle->setQualite($qualite);
        $salle->setNbPlacePmr($data['nbPlacePmr']);
        // On créer autant de siège que de place
        for ($i = 0; $i < $data['nbPlace']; $i++) {
            $siege = new Siege();
            $siege->setNumero($i);
            $siege->setSalle($salle);
            $siege->setPMR(false);
            $entityManager->persist($siege);
        }
        // On créer autant de sieges PMR que de place PMR
        for ($i = 0; $i < $data['nbPlacePmr']; $i++) {
            $siege = new Siege();
            $siege->setNumero($i);
            $siege->setSalle($salle);
            $siege->setPMR(true);
            $entityManager->persist($siege);
        }
        $entityManager->persist($salle);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Salle created!'], Response::HTTP_CREATED);
    }
}
