<?php

namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Film;
use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;

class FilmInputDataTransformer implements ProcessorInterface
{
    private RequestStack $requestStack;
    private EntityManagerInterface $em;
    private string $uploadDir;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
        $this->uploadDir = "upload/affiche";
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Film
    {
        $request = $this->requestStack->getCurrentRequest();

        // Récupération du genre à partir de l'ID passé dans la requête
        $genreId = $request->get('genre');
        $genre = $this->em->getRepository(Genre::class)->findOneBy(['id' => $genreId]);
        // Différencier création (POST) et mise à jour (PUT)
        if ($request && $request->isMethod('POST')) {
            $film = new Film();
            // Mise à jour des propriétés communes
            $film->setTitre($request->get('titre'));
            $film->setSynopsis($request->get('synopsis'));
            $film->setAgeMini((int) $request->get('age_mini'));
            $film->setGenre($genre);
            $film->setLabel($request->get('label'));

            // Gestion du fichier uploadé (si présent)
            $file = $request->files->get('afficheUrl');

            if ($file instanceof UploadedFile) {
                // Générer un nom de fichier unique (pour éviter les collisions)
                $newFilename = uniqid() . '-' . $file->getClientOriginalName();
                // Déplacer le fichier dans le répertoire de destination
                $file->move($this->uploadDir, $newFilename);
                // Mettre à jour l'attribut afficheUrl
                $film->setAfficheUrl($newFilename);
            }
            // Pour une requête PUT, si aucun fichier n'est envoyé, l'ancienne valeur est conservée

        } elseif ($request && $request->isMethod('PUT')) {
            $film = $data;
        } else {
            return $data;
        }


        $this->em->persist($film);
        $this->em->flush();

        return $film;
    }
}
