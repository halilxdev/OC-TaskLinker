<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeeController extends AbstractController
{

    public function __construct(
        private EmployeeRepository $employeeRepository,
        private EntityManagerInterface $entityManager,
    )
    {

    }


    #[Route('/employee', name: 'app_employee_index')]
    public function index(EmployeeRepository $repository): Response
    {
        $employees = $repository->findAll();
        return $this->render('employee/index.html.twig', [
            'controller_name'   =>  'EmployeeController',
            'employees'         =>  $employees,
        ]);
    }

    #[Route('employee/{id}', name: 'app_employee_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function new(int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $employee = $this->employeeRepository->find($id);
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($employee);
            $manager->flush();
            return $this->redirectToRoute('app_employee_index');
        }
        return $this->render('/employee/edit.html.twig', [
            'employee'  => $employee,
            'form' => $form,
        ]);
    }
    
    #[Route('employee/{id}/delete', name: 'app_employee_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id): Response
    {
        $employee = $this->employeeRepository->find($id);
        if(!$employee) {
            return $this->redirectToRoute('app_employee_index');
        }
        $this->entityManager->remove($employee);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_employee_index');
    }

}
