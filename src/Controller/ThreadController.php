<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Thread;
use App\Entity\Vote;
use App\Entity\ResponseEntity;
use App\Entity\User;
use App\Form\ResponseType;
use App\Form\ThreadFormType;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Role\Role;

class ThreadController extends AbstractController
{
    #[Route('/thread/create', name: 'app_thread_create')]

    public function createThread(Request $request, EntityManagerInterface $entityManager): Response
    {
        $createThread = new Thread();
        $form = $this->createForm(ThreadFormType::class, $createThread);
        $form->handleRequest($request);

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

            return $this->redirectToRoute('app_profil_thread');
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

            return $this->redirectToRoute('app_profil_thread');
        }

        return $this->render('thread/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/home', name: 'app_home')]

    public function threadAll(EntityManagerInterface $entityManager)
    {

        $threadRepository = $entityManager->getRepository(Thread::class);
        $threads = $threadRepository->findAll();


        return $this->render('home/index.html.twig', [
            'controller_name' => 'ThreadController',
            'threads' => $threads
        ]);
    }

    #[Route('/home/{id}', name: 'app_home_details')]

    public function threadHome($id, EntityManagerInterface $entityManager, Request $request, Security $security)
    {

        $threadRepository = $entityManager->getRepository(Thread::class);
        $thread = $threadRepository->find($id);

        $response = new ResponseEntity();
        $form = $this->createForm(ResponseType::class, $response);

        $form->handleRequest($request);

        $user = $security->getUser();


        if ($form->isSubmitted() && $form->isValid()) {
            $response->setThread($thread);

            if (!$user) {
                return $this->redirectToRoute('app_login');
            }


            $response->setCreatedAt(new DateTimeImmutable());
            $response->setUpdateAt(new DateTime());

            $entityManager->persist($response);
            $entityManager->flush();

            return $this->redirectToRoute('app_home_details', ['id' => $id]);
        }

        return $this->render('thread/details.html.twig', [
            'controller_name' => 'ThreadController',
            'thread' => $thread,
            'form' => $form,
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

    #[Route('/thread/{id}/like', name: 'app_thread_like', methods: ['POST'])]
    public function like($id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $threadRepository = $entityManager->getRepository(Thread::class);
        $thread = $threadRepository->find($id);

        $existingVote = $entityManager->getRepository(Vote::class)->findOneBy([
            'user' => $user,
            'thread' => $thread
        ]);

        if ($existingVote) {
            return new Response(Response::HTTP_BAD_REQUEST);
        }

        $vote = new Vote();
        $vote->setUser($user);
        $vote->setThread($thread);
        $vote->setVote(1);
        $entityManager->persist($vote);
        $entityManager->flush();

        $totalVotes = 0;
        foreach ($thread->getVoteId() as $vote) {
            $totalVotes += $vote->getVote();
        }
        return $this->redirectToRoute('app_home_details', ['id' => $id]);
        return new JsonResponse(['totalVotes' => $totalVotes]);
    }


    #[Route('/thread/{id}/delete', name: 'app_thread_delete', methods: ['POST'])]
    public function deleteThread($id, EntityManagerInterface $entityManager): Response
    {
        $threadRepository = $entityManager->getRepository(Thread::class);
        $thread = $threadRepository->find($id);

        $votes = $thread->getVoteId();
        foreach ($votes as $vote) {
            $entityManager->remove($vote);
        }

        $entityManager->remove($thread);
        $entityManager->flush();

        return $this->redirectToRoute('app_profil_thread');
    }

    #[Route('/user/{id}/threads', name: 'app_user_threads')]
    public function userThreads($id, EntityManagerInterface $entityManager): Response
    {
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($id);

        $threadRepository = $entityManager->getRepository(Thread::class);
        $threads = $threadRepository->findBy(['user' => $user]);

        return $this->render('profil/user.html.twig', [
            'user' => $user,
            'threads' => $threads
        ]);
    }
}
