<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Thread;
use App\Form\ThreadFormType;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ThreadController extends AbstractController
{
    #[Route('/thread/create', name: 'app_thread_create')]

    public function createThread(Request $request, EntityManagerInterface $entityManager): Response
    {
        $createThread = new Thread();
        $form = $this->createForm(ThreadFormType::class, $createThread);
        $form->handleRequest($request);

       /* $category_id = $entityManager->getRepository(Category::class)->findAll();

        $categoryChoices = [];
        foreach ($category_id as $category) {
            $categoryChoices[$category->getTitle()] = $category->getId();
        }

        $form->get('category_id');*/

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            if ($user) {
                $createThread->setUser($user);
            }

            $createThread->setCreatedAt(new DateTimeImmutable());
            $createThread->setUpdatedAt(new DateTime());
            $createThread->setStatus('active');
            $entityManager->persist($createThread);
            $entityManager->flush();
        }

        return $this->render('thread/create.html.twig', [
            'threadForm' => $form,
        ]);
    }

    #[Route('/thread/{id}/edit', name: 'app_thread_edit')]

    public function editThread($id, EntityManagerInterface $entityManager, Request $request): Response
    {

        $threadRepository = $entityManager->getRepository(Thread::class);
        $threadEdit = $threadRepository->find($id);

        $form = $this->createForm(ThreadFormType::class, $threadEdit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            if ($user) {
                $threadEdit->setUser($user);
            }

            $threadEdit->setCreatedAt(new DateTimeImmutable());
            $threadEdit->setUpdatedAt(new DateTime());
            $threadEdit->setStatus('active');

            $entityManager->persist($threadEdit);
            $entityManager->flush();
        }

        return $this->render('thread/edit.html.twig', [
            'category' => $threadEdit->getCategory(),
            'threadEdit' => $threadEdit,
        ]);
    }

    #[Route('/home', name: 'app_home')]

    public function threadAll(EntityManagerInterface $entityManager)
    {

        $threadRepository = $entityManager->getRepository(Thread::class);
        $threads = $threadRepository->findAll();


        return $this->render('home/index.html.twig', [
            //'controller_name'=> 'ThreadController',
            'threads' => $threads
        ]);
    }



    #[Route('/home/{id}', name: 'app_home_details')]

    public function threadHome($id, EntityManagerInterface $entityManager)
    {

        $threadRepository = $entityManager->getRepository(Thread::class);
        $thread = $threadRepository->find($id);


        return $this->render('thread/details.html.twig', [
            'controller_name' => 'ThreadController',
            'thread' => $thread
        ]);
    }

    #[Route('/profil', name: 'app_profil_thread')]
    public function threadAllByUser(EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $threadRepository = $entityManager->getRepository(Thread::class);
        $threadsUser = $threadRepository->findBy(['user' => $user]);

        return $this->render('profil/index.html.twig', [
            'threadsUser' => $threadsUser
        ]);
    }
}
