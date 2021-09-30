<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    /**
     * @Route("/customer/{id}", name="show_customer")
     */
    public function show($id): Response
    {
        $customer = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->find($id);

        return $this->render('customer/customerOne.html.twig', [
            'customer' => $customer
        ]);
    }

    /**
     * @Route("/customer/{id}/edit", name="customer_edit")
     */
    public function edit($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $customer = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->find($id);
        
        if ($customer === null) {
            throw new Exception('Not found customer');
        }

        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();
            $entityManager = $this->getDoctrine()
                ->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();

            return $this->redirectToRoute('customers');
        }

        return $this->render('customer/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/customers/create", name="create_customer")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();
            $entityManager = $this->getDoctrine()   
                ->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();

            return $this->redirectToRoute('customers');
        }
         return $this->render('customer/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    

}
