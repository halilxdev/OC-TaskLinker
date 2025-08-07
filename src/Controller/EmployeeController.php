<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeeController extends AbstractController
{
    #[Route('/employee', name: 'app_employee_index')]
    public function index(EmployeeRepository $repository): Response
    {
        $employees = $repository->findAll();
        return $this->render('employee/index.html.twig', [
            'controller_name'   =>  'EmployeeController',
            'employees'         =>  $employees,
        ]);
    }
}
