<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class GeneralPanelController extends AbstractController
{
    /**
     * @Route("/", name="general_panel")
     */
    public function index(): Response
    {
        return $this->render('general_panel/index.html.twig', [
            'controller_name' => 'GeneralPanelController',
        ]);
    }

    /**
     * @Route("/customer", name="customers")
     */
    public function customers(): Response
    {
        $customers = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->findAll();
        
        return $this->render('general_panel/customer.html.twig', [
            'customers' => $customers
        ]);
    }

    /**
     * @Route("/tasks", name="tasks")
     */
    public function tasks(): Response
    {
        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findAll();
        
        return $this->render('general_panel/task.html.twig', [
            'tasks' => $tasks
        ]);
    }
}
