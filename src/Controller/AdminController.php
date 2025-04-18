<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\EmployeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/employe/ajouter', name: 'admin_employe_ajouter')]
    public function ajouterEmploye(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): Response {
        $user = new User();
        $form = $this->createForm(EmployeType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $user->setPassword($hasher->hashPassword($user, $password));

            // Récupère ou assigne le rôle EMPLOYE
            $roleEmploye = $em->getRepository(Role::class)->findOneBy(['label' => 'employé']);
            if ($roleEmploye) {
                $user->addRole($roleEmploye);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Employé ajouté avec succès !');
            return $this->redirectToRoute('admin_employe_ajouter');
        }

        return $this->render('admin/ajout_employe.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
