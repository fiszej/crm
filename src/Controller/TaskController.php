<?php

namespace App\Controller;

use App\Factory\TaskFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use DateTime;
use DateTimeImmutable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @IsGranted("ROLE_USER")
 */
class TaskController extends AbstractController
{
    private $repository;

    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

     /**
     * @Route("/tasks", name="tasks")
     */
    public function index(): Response
    {   
        return $this->render('general_panel/task.html.twig', [
            'tasks' => $this->repository->findAll()
        ]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager, TaskFactory $factory): Response
    {
        $form = $this->createForm(TaskType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $form = $form->getData();
            $data = $factory->createTask($form);

            foreach ($data as $key => $object) {
                $entityManager->persist($object);
            }
            $entityManager->flush();
     
            return $this->redirectToRoute('tasks');
        }
         return $this->render('task/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/task/{id}", name="task_show")
     */
    public function show($id): Response
    {
        $task = $this->repository
            ->find($id);
        if ($task === null) {
        
            return new Response($this->renderView('exception/404.html.twig', [],  404));
        }
 
        $deadline = $task->getDeadline();
        $deadline = new DateTime($deadline->format('Y-m-d H:i'));
        $now = new DateTimeImmutable('now');
        $interval = $now->diff($deadline);
        $timeToDeadline = $interval->format('%d days  %h');
        
        return $this->render('task/show.html.twig', [
            'task' => $task,
            'timeToDeadline' => $timeToDeadline
        ]);
    }

    /**
     * @Route("/task/{id}/delete", name="task_delete")
     */
    public function delete($id):Response
    {
        $task = $this->repository->find($id);
        if ($task === null) {
            
            return new Response($this->renderView('exception/404.html.twig', [],  404));
        }
        $this->repository->removeTask($task);
        $this->addFlash('success', 'Task deleted!');

        return $this->redirectToRoute('tasks');
    }

    /**
     * @Route("/task/{id}/edit", name="task_edit")
     */
    public function edit($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = $this->repository->find($id);
        if ($task === null) {
            return new Response($this->renderView('exception/404.html.twig', [],  404));
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->save($form->getData());
            $this->addFlash('success', 'Task updated');
            return $this->redirect('/task/'.$id);
        }

        return $this->render('task/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
