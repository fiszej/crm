<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\MailType;

class MailController extends AbstractController
{
    /**
     * @Route("/mail/{id}", name="mails_show")
     */
    public function show($id): Response
    {
        $mail = $this->getDoctrine()
            ->getRepository(Mail::class)
            ->find($id);
        
        return $this->render('mail/show.html.twig', [
            'mail' => $mail,
        ]);
    }
    /**
     * @Route("/mails/create", name="mails_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mail = new Mail();
        $customer = new Customer();
        $employee = new Employee();

        $form = $this->createForm(MailType::class, $mail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form = $form->getData();

            $customer = $form->getCustomer();
            $employee = $form->getEmployee();

            $mail->setCustomer($customer);
            $mail->setEmployee($employee);

            $entityManager = $this->getDoctrine()
                ->getManager();
            $entityManager->persist($mail);
            $entityManager->persist($customer);
            $entityManager->persist($employee);
            $entityManager->flush();

            return $this->redirectToRoute('mails');
        }

        return $this->render('mail/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mail/{id}/sent", name="mails_sent")
     */
    public function sentMail($id, EntityManagerInterface $entityManager): Response
    {
        $mail = $this->getDoctrine()
            ->getRepository(Mail::class)
            ->find($id);
        
        $mail->setStatus(1);
        
        $entityManager = $this->getDoctrine()
            ->getManager();
        $entityManager->persist($mail);
        $entityManager->flush();

        $this->addFlash('successMail', 'Message sent');
        return $this->redirectToRoute('mails_show', [
            'id' => $id
        ]);
    }
}
