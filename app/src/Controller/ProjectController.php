<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final class ProjectController extends AbstractController
{


    public function __construct(
        private ProjectRepository $projectRepository,
        private EntityManagerInterface $entityManager,
    )
    {

    }

    #[Route('/', name: 'app_project_index')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findAll();
        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
            'projects' => $projects,
        ]);
    }

    #[Route('/project/{id}', name: 'app_project_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Project $project): Response
    {
        $employees = $project->getEmployees();
        $tasks = $project->getTask();
        return $this->render('/project/show.html.twig', [
            'project' => $project,
            'employees' => $employees,
            'tasks' => $tasks,
        ]);
    }

    #[Route('project/new', name: 'app_project_new', methods: ['GET', 'POST'])]
    public function new(?Project $project, Request $request, EntityManagerInterface $manager): Response
    {
        $project ??= new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($project);
            $manager->flush();
            return $this->redirectToRoute('app_project_show', ['id' => $project->getId()]);
        }
        return $this->render('/project/new.html.twig', [
            'project'   => $project,
            'form' => $form,
        ]);
    }

    #[Route('project/{id}/edit', name: 'app_project_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(?Project $project, Request $request, EntityManagerInterface $manager): Response
    {
        $project ??= new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($project);
            $manager->flush();
            return $this->redirectToRoute('app_project_index');
        }
        return $this->render('/project/edit.html.twig', [
            'project'   => $project,
            'form' => $form,
        ]);
    }

    #[Route('project/{id}/delete', name: 'app_project_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id): Response
    {
        $project = $this->projectRepository->find($id);
        $tasks = $project->getTask();
        if(!$project) {
            return $this->redirectToRoute('app_project_index');
        }
        foreach($tasks as $t){
            $this->entityManager->remove($t);
            $this->entityManager->flush();
        }
        $this->entityManager->remove($project);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_project_index');
    }
}
