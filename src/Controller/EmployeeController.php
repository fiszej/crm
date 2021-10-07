<?php

namespace App\Controller;

use App\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    /**
     * @Route("/employee", name="employee")
     */
    public function index(): Response
    {
        return $this->render('employee/index.html.twig', [
            'controller_name' => 'EmployeeController',
        ]);
    }
    /**
     * @Route("/employee/{id}", name="employee_show")
     */
    public function show($id): Response
    {
        $employee = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->find($id);
      


        return $this->render('employee/show.html.twig', [
            'employee' => $employee
        ]);
    }
}
