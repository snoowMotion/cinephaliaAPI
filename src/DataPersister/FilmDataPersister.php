<?php

namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Film;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FilmDataPersister implements ProcessorInterface
{
    private string $uploadDir;

    public function __construct()
    {
        $this->uploadDir = "upload/affiche";
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data instanceof Film && $data->getAfficheUrl() instanceof UploadedFile) {
            $file = $data->getAfficheUrl();
            /**
             * @var UploadedFile $file
             */
            $newFilename = uniqid() . '.' . $file->guessExtension();

            $file->move($this->uploadDir, $newFilename);

            // Mettre à jour la propriété persistée avec le chemin du fichier
            $data->setAfficheUrl($newFilename);
        }
    }
}