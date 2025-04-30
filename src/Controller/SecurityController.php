<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MailingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {

    }

    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        MailingService $mailer
    ): Response {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('_username');

            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                // Générer un nouveau mot de passe
                $newPasswordPlain = bin2hex(random_bytes(6)); // Exemple : 12 caractères

                // Hasher le nouveau mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $newPasswordPlain);
                $user->setPassword($hashedPassword);

                // Ici il faudra ajouter un champ "mustChangePassword" dans l'entité User
                $user->setMustChangePassword(true);

                $entityManager->flush();

                // Envoyer le nouveau mot de passe par email
                $mailer->sendForgetPassword($user,$newPasswordPlain);

                $this->addFlash('success', 'Un nouveau mot de passe a été envoyé à votre adresse email.');
            } else {
                $this->addFlash('danger', 'Aucun compte n\'existe avec cet email.');
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/forget_password.html.twig');
    }
    #[Route('/change-password', name: 'app_change_password')]
    public function changePassword(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        if ($request->isMethod('POST')) {
            $newPassword = $request->request->get('new_password');
            $confirmPassword = $request->request->get('confirm_password');

            if ($newPassword !== $confirmPassword) {
                $this->addFlash('danger', 'Les mots de passe ne correspondent pas.');
            } elseif (strlen($newPassword) < 8) {
                $this->addFlash('danger', 'Le mot de passe doit contenir au moins 8 caractères.');
            } else {
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
                $user->setMustChangePassword(false);

                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe modifié avec succès !');
                return $this->redirectToRoute('app_home'); // ou où tu veux
            }
        }

        return $this->render('security/change_password.html.twig');
    }

}
