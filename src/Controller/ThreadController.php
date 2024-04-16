<?php

namespace App\Controller;

use App\Entity\Thread;
use App\Form\ThreadFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ThreadController extends AbstractController
{
    #[Route('/thread', name: 'app_thread')]

    public function thread(Request $request): Response
    {
        $thread = new Thread();
        $form = $this->createForm(ThreadFormType::class, $thread);
        $form->handleRequest($request);

        return $this->render('thread/index.html.twig', [
            'threadForm' => $form,
        ]);
    }

    #[Route('/home', name: 'app_home')]

    public function threadAll(EntityManagerInterface $entityManager)
    {

        $threadRepository = $entityManager->getRepository(Thread::class);
        $threads = $threadRepository->findAll();


        return $this->render('home/index.html.twig', [
            //'controller_name'=> 'ThreadController',
            'threads'=> $threads
        ]);
    }



    #[Route('/home/{id}', name: 'app_home_details')]

    public function threadHome($id, EntityManagerInterface $entityManager)
    {

        $threadRepository = $entityManager->getRepository(Thread::class);
        $thread = $threadRepository->find($id);


        return $this->render('thread/details.html.twig', [
            'controller_name'=> 'ThreadController',
            'thread'=> $thread
        ]);
    }
}
