<?php

namespace App\Controller;

use App\Entity\Biblio;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
  #[Route('/registration', name: 'app_registration')]
  public function index(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
  {
    $user = new User();
    $form = $this->createForm(RegistrationFormType::class, $user);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $hashedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());
      $user->setPassword($hashedPassword);
      $user->setRoles(['ROLE_USER']);
      $user->setBiblio(
        (new Biblio())
          ->setTitle("My Biblio")
          ->setUser($user)
      );

      $entityManager->persist($user);
      $entityManager->flush();

      return $this->redirectToRoute('app_login');
    }

    return $this->render('registration/index.html.twig', [
      'form' => $form->createView(),
    ]);
  }
}
