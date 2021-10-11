<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EmployeeRepository;

class EmployeeController extends AbstractController
{
    private $repository;

    public function __construct(EmployeeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/employee/{id}", name="employee_show")
     */
    public function show($id): Response
    {
        $employee = $this->repository->find($id);

        if ($employee === null) {
            return new Response($this->renderView('exception/404.html.twig', [],  404));
        }

        return $this->render('employee/show.html.twig', [
            'employee' => $employee
        ]);
    }
}
