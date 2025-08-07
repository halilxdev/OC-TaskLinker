<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }
    #[Route('/task/{id}', name: 'app_task_detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Task $task): Response
    {
        return $this->render('task/edit.html.twig', [
            'task' => $task,
        ]);
    }
}
