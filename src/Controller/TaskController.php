<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TaskType;

class TaskController extends AbstractController
{
   /**
     * @Route("/tasks/create", name="create_task")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        $customer = new Customer();
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find(1);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $task = $form->getData();
            $task->setCustomerId($customer);
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
}
