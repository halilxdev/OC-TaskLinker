<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
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

    #[Route('/project/{id}', name: 'app_project_detail', requirements: ['id' => '\d+'], methods: ['GET'])]
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
}
