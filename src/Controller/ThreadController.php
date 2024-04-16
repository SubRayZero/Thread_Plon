<?php

namespace App\Controller;

use App\Entity\Thread;
use App\Form\ThreadFormType;
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
}
