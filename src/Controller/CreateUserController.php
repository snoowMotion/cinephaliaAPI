<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Service\AccountConfirmationMailer;
use App\Service\MailingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CreateUserController extends AbstractController
{
    /**
     * Permet d'afficher le formulaire de création d'un utilisateur
     * @return Response
     */
    #[Route('/create/user', name: 'app_create_user')]
    public function index(): Response
    {
        return $this->render('create_user/index.html.twig', [
            'controller_name' => 'CreateUserController',
        ]);
    }

    /**
     * Permet l'enregistrement d'un nouvel utilisateur
     * @return Response
     */
    #[Route('/create/user/register', name: 'app_create_user_register')]
    public function register(Request $req, UserRepository $userRepo, RoleRepository $roleRepository, EntityManagerInterface $em, MailingService $mailer): Response
    {
        $data = json_decode($req->getContent(), true);
        // Récupération des différents attributs du formulaire
        $nom = $data['nom'] ?? null;
        $prenom = $data['prenom'] ?? null;
        $email = $data['login'] ?? null;
        $password = $data['password'] ?? null;

        // On vérifie que tout les attributs sont remplis
        if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
            return new JsonResponse([
                'status' => 400,
                'message' => 'Veuillez remplir tous les champs'
            ]);
        }
        // On vérifie que l'email est valide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse([
                'status' => 400,
                'message' => 'Veuillez entrer une adresse email valide'
            ]);
        }
        // On vérifie que le mot de passe fait au moins 8 caractères
        if (strlen($password) < 8) {
            return new JsonResponse([
                'status' => 400,
                'message' => 'Le mot de passe doit faire au moins 8 caractères'
            ]);
        }
        // On vérifie que le mot de passe contient au moins une majuscule, une minuscule et un chiffre
        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            return new JsonResponse([
                'status' => 400,
                'message' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre'
            ]);
        }
        // On vérifie que l'utilisateur n'existe pas déjà
        $user = $userRepo->findOneBy(['email' => $email]);
        if ($user) {
            return new JsonResponse([
                'status' => 400,
                'message' => 'Cet utilisateur existe déjà'
            ]);
        }
        // On crée un nouvel utilisateur
        $user = new User();
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $user->addRole($roleRepository->findOneBy(['libelle' => 'ROLE_USER']));
        $user->setConfirmed(false);
        $user->setConfirmationToken(bin2hex(random_bytes(32)));
        // On enregistre l'utilisateur
        $em->persist($user);
        $em->flush();
        // On envoie un mail de confirmation
        $mail = $mailer->sendConfirmationEmail($user, $this->generateUrl('app_confirm_user', [
            'token' => $user->getConfirmationToken()
        ], UrlGeneratorInterface::ABSOLUTE_URL));
        return new JsonResponse([
            'status' => 200,
            'message' => 'Utilisateur créé avec succès',
            'mail' => $mail
        ]);
    }

    /**
     * Permet de confirmer l'utilisateur
     * @return Response
     */
    #[Route('/create/user/confirm/{token}', name: 'app_confirm_user')]
    public function confirmUser(string $token, UserRepository $userRepo, EntityManagerInterface $em): Response
    {
        // On vérifie que le token est valide
        $user = $userRepo->findOneBy(['confirmation_token' => $token]);
        if (!$user) {
            return new JsonResponse([
                'status' => 400,
                'message' => 'Token invalide'
            ]);
        }
        // On confirme l'utilisateur
        $user->setConfirmed(true);
        $user->setConfirmationToken(null);
        // On enregistre l'utilisateur
        $em->persist($user);
        $em->flush();
        return new JsonResponse([
            'status' => 200,
            'message' => 'Utilisateur confirmé'
        ]);
    }
}
