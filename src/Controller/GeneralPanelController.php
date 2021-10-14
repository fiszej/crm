<?php

namespace App\Controller;

use App\Repository\ApiDataRepository;
use App\Repository\CustomerRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

// 3 edit employee


/**
 * @Security("is_granted('ROLE_USER')")
 */
class GeneralPanelController extends AbstractController
{
    private $customersRepository;
    private $taskRepository;
    private $apiDataRepository;

    public function __construct(
        CustomerRepository $customerRepository,
        TaskRepository $taskRepository,
        ApiDataRepository $apiDataRepository
        )   {
        $this->customersRepository = $customerRepository;
        $this->taskRepository = $taskRepository;
        $this->apiDataRepository = $apiDataRepository;
    }

    /**
     * @Route("/", name="general_panel")
     */
    public function index( ): Response
    {  
        $customers = count($this->customersRepository
            ->findAll());

        $tasks = count($this->taskRepository
            ->findAll());

        $usersFromApi = $this->apiDataRepository
            ->findAll();

        return $this->render('general_panel/index.html.twig', [
            'customers' => $customers,
            'tasks' => $tasks,
            'users' => $usersFromApi
        ]);
    }

    

   




}
