<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\EmployeType;
use App\Service\MailingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdminController extends AbstractController
{
    #[Route('/admin/employe/ajouter', name: 'admin_employe_ajouter')]
    public function ajouterEmploye(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        MailingService $mailer
    ): Response {
        $user = new User();
        $form = $this->createForm(EmployeType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $user->setPassword($hasher->hashPassword($user, $password));

            // Récupère ou assigne le rôle EMPLOYE
            $roleEmploye = $em->getRepository(Role::class)->findOneBy(['libelle' => 'ROLE_EMPLOYE']);
            if ($roleEmploye) {
                $user->addRole($roleEmploye);
            }
            // Génération du token de confirmation
            $user->setConfirmed(false);
            $user->setConfirmationToken(bin2hex(random_bytes(32)));
            $em->persist($user);
            $em->flush();
            // Envoi de l'email de confirmation
            // On envoie un mail de confirmation
            $mail = $mailer->sendConfirmationEmail($user, $this->generateUrl('app_confirm_user', [
                'token' => $user->getConfirmationToken()
            ], UrlGeneratorInterface::ABSOLUTE_URL));
            $this->addFlash('success', 'Employé ajouté avec succès !');
            return $this->redirectToRoute('admin_employe_ajouter');
        }

        return $this->render('admin/ajout_employe.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * Affiche la liste des employés
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/admin/employes', name: 'admin_employes')]
    public function listeEmployes(EntityManagerInterface $em): Response
    {
        $employes =$em->getRepository(User::class)->createQueryBuilder('u')
            ->join('u.roles', 'r')
            ->where('r.libelle = :role')
            ->setParameter('role', 'ROLE_EMPLOYE')
            ->getQuery()
            ->getResult();

        return $this->render('admin/liste_employe.html.twig', [
            'employes' => $employes
        ]);
    }

    #[Route('/admin/employe/modifier/{id}', name: 'admin_employe_modifier')]
    public function modifierEmploye(User $employe, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $employe->setPassword($hasher->hashPassword($employe, $plainPassword));
            }

            $em->flush();

            $this->addFlash('success', 'Compte employé modifié avec succès.');
            return $this->redirectToRoute('admin_employes');
        }

        return $this->render('admin/ajout_employe.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
