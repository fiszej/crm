<?php

namespace App\Factory;

use App\Entity\Customer;
use App\Entity\Employee;
use App\Entity\Task;
use Symfony\Component\Form\Test\FormInterface;

class TaskFactory 
{
    public function createTask($task)
    {
        $data = [];
        $customer = new Customer();
        $employee = new Employee();

        $customer = $task->getCustomerId();
        $employee = $task->getEmployee();

        $task->setEmployee($employee);
        $task->setCustomerId($customer);
        $task->setLogo($task->logos[rand(0, 6)]);
        $task->setCreatedAt(new \DateTimeImmutable('now'));

        array_push($data, $task, $customer, $employee); 

        return $data;
    }
}