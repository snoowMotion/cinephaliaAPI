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
        $genre = $this->em->getRepository(Genre::class)->find($request->get('genre'));
        if ($request && $request->isMethod('POST')) {
            $film = new Film();

            // Récupérer les données depuis FormData
            $film->setTitre($request->get('titre'));
            $film->setSynopsis($request->get('synopsis'));
            $film->setAgeMini((int) $request->get('age_mini'));

            // Gérer le fichier
            $file = $request->files->get('afficheUrl');

            if ($file) {
                $film->setAfficheUrl($file->getClientOriginalName());
            }

            /**
             * @var UploadedFile $file
             */

            $file->move($this->uploadDir, $file->getClientOriginalName());

            $film->setGenre($genre);
            $film->setLabel($request->get('label'));
            $this->em->persist($film);
            $this->em->flush();
            return $film;
        }

        return $data;
    }
}
