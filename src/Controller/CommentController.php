<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CommentsRepository;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CommentController extends AbstractController
{
    private $commentRepository;
    private $customerRepository;

    public function __construct(CommentsRepository $repository, CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->commentRepository = $repository;
    }
    /**
     * @Route("/comment/create/{id}")
     */
    public function create($id, Request $request): Response
    {   
        $comment = new Comments();
        $customer = $this->customerRepository->find($id);

        $comment->setCustomer($customer);
        $comment->setText($request->request->get('comment'));
        
        $this->commentRepository->save($comment);
        $this->addFlash('comment', 'Comment has been added');

        return $this->redirect('/customer/'.$id);
    }

     /**
     * @Route("/comment/{id}/delete")
     */
    public function delete($id): Response
    {   
        $comment = $this->commentRepository->find($id);
        $customer = $comment->getCustomer();

        $this->commentRepository->removeComment($comment);
        $this->addFlash('comment', 'Comment deleted!');

        return $this->redirect('/customer/'.$customer->getId());
    }
}
