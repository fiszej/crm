<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Task;
use App\Form\CustomerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use DateTime;
use DateTimeImmutable;
use Exception;
use App\Service\TaskService;

class TaskController extends AbstractController
{
   /**
     * @Route("/tasks/create", name="task_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $customer = new Customer();        
        $task = new Task();
        
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $task->setCustomerId($customer);
            $task->setLogo($task->logos[rand(0, 7)]);
            $task->setCreatedAt(new DateTimeImmutable('now'));

            $entityManager = $this->getDoctrine()   
                ->getManager();
            $entityManager->persist($customer);
            $entityManager->persist($task);
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
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);
 
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
        $entityManager = $this->getDoctrine()
            ->getManager();
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);
        $entityManager->remove($task);
        $entityManager->flush();

        $this->addFlash('success', 'Task deleted!');

        return $this->redirectToRoute('tasks');
    }

    /**
     * @Route("/task/{id}/edit", name="task_edit")
     */
    public function edit($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);
        
        if ($task === null) {
            throw new Exception('Not found customer');
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $entityManager = $this->getDoctrine()
                ->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            $this->addFlash('success', 'Task updated');
            return $this->redirect('/task/'.$id);
        }

        return $this->render('task/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
