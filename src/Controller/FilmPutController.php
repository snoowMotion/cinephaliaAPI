<?php

namespace App\Controller;

use App\Entity\Film;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FilmPutController
{
    public function __invoke(Film $data, Request $request, EntityManagerInterface $em): Response
    {
        // Ici, $data est déjà un objet Film désérialisé du JSON envoyé dans la requête PUT
        // Vous pouvez faire des traitements supplémentaires si besoin, par exemple vérifier certains champs ou gérer des fichiers

        // Exemple de mise à jour d'un champ supplémentaire :
        // $content = $request->getContent();
        // $jsonData = json_decode($content, true);
        // if (isset($jsonData['unAutreChamp'])) {
        //     $data->setUnAutreChamp($jsonData['unAutreChamp']);
        // }

        // Persistance de l'entité mise à jour
        $em->persist($data);
        $em->flush();

        return new Response("Film mis à jour", Response::HTTP_OK);
    }
}
