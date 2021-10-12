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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\MailRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_ADMIN') and is_granted('ROLE_USER')")
 */
class MailController extends AbstractController
{
    private $mailRepository;

    public function __construct(MailRepository $mailRepository)
    {
        $this->mailRepository = $mailRepository;
    }

    /**
     * @Route("/mail/{id}", name="mails_show")
     */
    public function show($id): Response
    {
        $mail = $this->mailRepository->find($id);
        
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
    public function sentMail($id, EntityManagerInterface $entityManagerm, MailerInterface $mailer): Response
    {
        $mail = $this->mailRepository->find($id);

        if ($mail === null) {
            return new Response($this->renderView('exception/404.html.twig', [],  404));
        }
        
        if ($mail->getStatus() == 1) {
            $this->addFlash('failSend', 'Message is already sent');
            return $this->redirectToRoute('mails_show', [
            'id' => $id
            ]);
        }

        $email = (new Email())
            ->from('crm@mbryla89.webd.pro')
            ->to('brylaaaa@gmail.com')
            ->subject('Test CRM')
            ->text('This is test message');

        $mailer->send($email);

        $this->mailRepository->saveAfterSent($mail);
        $this->addFlash('successMail', 'Message sent');

        return $this->redirectToRoute('mails_show', [
            'id' => $id
        ]);
    }
}
