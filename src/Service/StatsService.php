<?php

namespace App\Service;

use App\Repository\CustomerRepository;

use App\Repository\TaskRepository;
use App\Repository\ApiDataRepository;
use App\Repository\EmployeeRepository;
use App\Repository\MailRepository;

class StatsService 
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

    public function customer()
    {
        return count($this->customersRepository->findAll());
    }

    public function task()
    {
        return count($this->taskRepository->findAll());
    }

    public function mail()
    {
        return count($this->mailRepository->findAll());
    }

    public function employee()
    {
        return count($this->employeeRepository->findAll());
    }

    public function mailsToSent()
    {
        return count($this->mailRepository->findBy(['status' => 0]));
    }

    public function todayCustomers()
    {
        return count($this->customersRepository->getByDate(new \DateTime()));

    }

    public function todayEmployee()
    {
        return count($this->employeeRepository->getByDate(new \DateTime()));
    }

    public function todayMails()
    {
        return count($this->mailRepository->getByDate(new \DateTime()));
    }

    public function todayTasks()
    {
        return count($this->taskRepository->getByDate(new \DateTime()));
    }

}