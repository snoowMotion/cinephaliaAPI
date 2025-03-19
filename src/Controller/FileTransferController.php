<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FileTransferController extends AbstractController
{
    /**
     * Méthode pour uploader un fichier via AJAX
     * @param Request $request Requête HTTP
     * @return JsonResponse Réponse JSON
     */
    #[Route('/upload', name: 'upload_fichier', methods: ['POST'])]
    public function uploadFichier(Request $request): JsonResponse
    {
        $fichier = $request->files->get('file');

        if (!$fichier instanceof UploadedFile) {
            return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        // On récupère le nom original du fichier
        $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
        // On remplace les caractères spéciaux par des tirets
        $nomFichier = preg_replace('/[^a-z0-9]/i', '-', $nomFichier);
        // On ajoute un identifiant unique pour éviter les doublons
        $nomFichier .= '-' . uniqid();
        // On ajoute l'extension du fichier
        $nomFichier .= '.' . $fichier->guessExtension();
        // On déplace le fichier dans le répertoire de destination
        $fichier->move($this->getParameter('kernel.project_dir') . '/public/upload/affiche', $nomFichier);

        // On retourne le nom du fichier dans une réponse JSON
        return new JsonResponse(['fileName' => $nomFichier], Response::HTTP_OK);
    }
}