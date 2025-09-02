<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{


    public function __construct(
        private TaskRepository $taskRepository,
        private EntityManagerInterface $entityManager,
    )
    {

    }

    #[Route('/project/{id}/task/new', name: 'app_task_new')]
    public function new(int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $project = $manager->getRepository(Project::class)->find($id);
        if (!$project) {
            return $this->redirectToRoute('app_project_index');
        }
        $task = new Task();
        $task->setProject($project);
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($task);
            $manager->flush();
            return $this->redirectToRoute('app_project_show', ['id' => $project->getId()]);
        }
        return $this->render('/task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('task/{id}', name: 'app_task_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(?Task $task, Request $request, EntityManagerInterface $manager): Response
    {
        $project = $task->getProject();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($task);
            $manager->flush();
            return $this->redirectToRoute('app_project_show', ['id' => $project->getId()]);
        }
        return $this->render('/task/edit.html.twig', [
            'task'  => $task,
            'form' => $form,
        ]);
    }
    
    #[Route('task/{id}/delete', name: 'app_task_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id): Response
    {
        $task = $this->taskRepository->find($id);
        $project = $task->getProject();
        if(!$task) {
            return $this->redirectToRoute('app_project_detail', ['id' => $project->getId()]);
        }
        $this->entityManager->remove($task);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_project_index');
    }
}