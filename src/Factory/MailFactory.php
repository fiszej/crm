<?php

namespace App\Factory;

use App\Entity\Customer;
use App\Entity\Employee;


class MailFactory 
{
    public function createMail($mail)
    {
        $data = [];
        $customer = new Customer();
        $employee = new Employee();

        $customer = $mail->getCustomer();
        $employee = $mail->getEMployee();

        $mail->setEmployee($employee);
        $mail->setCustomer($customer);

        array_push($data, $mail, $customer, $employee); 

        return $data;
    }
}