<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function profilAll(EntityManagerInterface $entityManager)
    {

        $UserRepository = $entityManager->getRepository(User::class);
        $users = $UserRepository->findAll();

        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'users' => $users
        ]);
    }
}
