<?php

namespace App\Controller;

use App\Entity\ApiData;
use App\Entity\Customer;
use App\Entity\Task;
use App\Repository\ApiDataRepository;
use App\Repository\CustomerRepository;
use App\Repository\EmployeeRepository;
use App\Repository\MailRepository;
use App\Repository\TaskRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



/**
 * @Security("is_granted('ROLE_USER')")
 */
class GeneralPanelController extends AbstractController
{
    private $customersRepository;
    private $taskRepository;
    private $apiDataRepository;
    private $employeeRepository;
    private $mailRepository;

    public function __construct(
        CustomerRepository $customerRepository,
        TaskRepository $taskRepository,
        ApiDataRepository $apiDataRepository,
        EmployeeRepository $employeeRepository,
        MailRepository $mailRepository
        )   {
        $this->customersRepository = $customerRepository;
        $this->taskRepository = $taskRepository;
        $this->apiDataRepository = $apiDataRepository;
        $this->employeeRepository = $employeeRepository;
        $this->mailRepository = $mailRepository;
    }
    /**
     * @Route("/", name="general_panel")
     */
    public function index( ): Response
    {  
        $customers = count($this->getDoctrine()
            ->getRepository(Customer::class)
            ->findAll());

        $tasks = count($this->getDoctrine()
        ->getRepository(Task::class)
        ->findAll());

        $usersFromApi = $this->getDoctrine()
            ->getRepository(ApiData::class)
            ->findAll();

        return $this->render('general_panel/index.html.twig', [
            'customers' => $customers,
            'tasks' => $tasks,
            'users' => $usersFromApi
        ]);
    }

    /**
     * @Route("/customers", name="customers")
     */
    public function customers(): Response
    {
        return $this->render('general_panel/customer.html.twig', [
            'customers' => $this->customersRepository->findAll()
        ]);
    }

    /**
     * @Route("/tasks", name="tasks")
     */
    public function tasks(): Response
    {   
        return $this->render('general_panel/task.html.twig', [
            'tasks' => $this->taskRepository->findAll()
        ]);
    }

    /**
     * @Route("/employees", name="employee")
     */
    public function employee(): Response
    {    
        return $this->render('general_panel/employee.html.twig', [
            'employees' => $this->employeeRepository->findAll()
        ]);
    }

    /**
     * 
     * @Route("/mails", name="mails")
     */
    public function mails(Request $request, PaginatorInterface $paginator): Response
    {  
        $mails = $this->mailRepository->findAll();
        $pagination = $paginator->paginate(
            $mails,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('general_panel/mail.html.twig', [
            'mails' =>  $pagination
        ]);
    }
}
