<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Task;
use App\Form\CustomerType;
use App\Helpers\PathHelper;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CustomerController extends AbstractController
{
    /**
     * @Route("/customer/{id}", name="customer_show")
     */
    public function show($id): Response
    {
        $customer = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->find($id);
        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findBy(['customerId' => $id]);

        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("/customer/{id}/edit", name="customer_edit")
     */
    public function edit($id, Request $request, EntityManagerInterface $entityManager, PathHelper $helper): Response
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
            $this->addFlash('success', 'Company updated');
            return $this->redirect('/customer/'.$id);
        }

        return $this->render('customer/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/customers/create", name="customer_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();
            $customer->setLogo($customer->logos[rand(0, 8)]);
            $entityManager = $this->getDoctrine()   
                ->getManager();
            $customer->setCreatedAt(new DateTimeImmutable('now'));
            $entityManager->persist($customer);
            $entityManager->flush();

            $this->addFlash('success', 'Company added');
            return $this->redirectToRoute('customers');
        }
         return $this->render('customer/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/customer/{id}/delete", name="customer_delete")
     */
    public function delete($id, Request $request, EntityManagerInterface $entityManager)
    {
        $entityManager = $this->getDoctrine()
            ->getManager();

        $customer = $entityManager
            ->getRepository(Customer::class)
            ->find($id);

        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findBy(['customerId' => $customer->getId()]);

        
        if (!empty($task)) {
            $this->addFlash('message', 'Can\'t delete client with open Tasks!');

            return $this->redirect("/customer/$id");
        }

        $entityManager->remove($customer);
        $entityManager->flush();
        $this->addFlash('success', 'Company deleted!');

        return $this->redirectToRoute('customers');
    }
}
