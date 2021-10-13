<?php

namespace App\Controller;

use App\Form\CustomerType;
use App\Repository\CommentsRepository;
use App\Repository\CustomerRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @IsGranted("ROLE_USER")
 */
class CustomerController extends AbstractController
{
    private $repository;
    private $taskRepository;
    private $commentsRepository;

    public function __construct(CustomerRepository $repository, TaskRepository $taskRepository, CommentsRepository $commentsRepository)
    {
        $this->repository = $repository;
        $this->taskRepository = $taskRepository;
        $this->commentsRepository = $commentsRepository;
    }
    /**
     * @Route("/customer/{id}", name="customer_show")
     */
    public function show($id): Response
    {
        $customer = $this->repository->find($id); 
        $comments = $this->commentsRepository->findBy(['customer' => $id]);

        if ($customer === null) {
            return new Response($this->renderView('exception/404.html.twig', [],  404));
        }

        $tasks = $this->taskRepository->findBy(['customerId' => $id]);
        
        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
            'tasks' => $tasks,
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/customer/{id}/edit", name="customer_edit")
     */
    public function edit($id, Request $request): Response
    {
        $customer = $this->repository->find($id);

        if ($customer === null) {
            return new Response($this->renderView('exception/404.html.twig', [],  404));
        }
        
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();
            $this->repository->save($customer);
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
    public function create(Request $request): Response
    {
        $form = $this->createForm(CustomerType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();
            $this->repository->save($customer);
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
    public function delete($id): Response
    {
        $customer = $this->repository->find($id);

        if ($customer === null) {
            return new Response($this->renderView('exception/404.html.twig', [],  404));
        }
        
        $tasks = $this->taskRepository->findBy(['customerId' => $id]);
  
        if (!empty($task)) {
            $this->addFlash('message', 'Can\'t delete client with open Tasks!');
            return $this->redirect("/customer/$id");
        }

        $this->repository->removeCustomer($customer);
        $this->addFlash('success', 'Company deleted!');

        return $this->redirectToRoute('customers');
    }

}
