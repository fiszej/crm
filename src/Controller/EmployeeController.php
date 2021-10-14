<?php

namespace App\Controller;

use App\Form\EmployeeType;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EmployeeRepository;
use App\Repository\TaskRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class EmployeeController extends AbstractController
{
    private $repository;
    private $taskRepository;

    public function __construct(EmployeeRepository $repository, TaskRepository $taskRepository)
    {
        $this->repository = $repository;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @Route("/employees", name="employee")
     */
    public function employee(): Response
    {    
        return $this->render('general_panel/employee.html.twig', [
            'employees' => $this->repository->findAll()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
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

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/employee/{id}/delete", name="employee_delete")
     */
    public function delete($id): Response
    {
        $employee = $this->repository->find($id);

        $tasks = $this->taskRepository->findBy(['employee' => $id]);
  
        
        if (!empty($tasks)) {
            $this->addFlash('message', 'Can\'t delete employee with open Tasks!');
            return $this->redirect("/employee/$id");
        }

        $this->repository->delete($employee);
        return $this->redirectToRoute('employee');
    }

     /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/employee/{id}/edit", name="employee_edit")
     */
    public function edit($id, Request $request): Response
    {
        $employee = $this->repository->find($id);

        if ($employee === null) {
            return new Response($this->renderView('exception/404.html.twig', [],  404));
        }
        
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $employee = $form->getData();
            $this->repository->save($employee);
            $this->addFlash('success', 'Employee updated');

            return $this->redirect('/employee/'.$id);
        }

        return $this->render('employee/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

}
