<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Factory\MailFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\MailType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\MailRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
class MailController extends AbstractController
{
    private $mailRepository;

    const BASE_EMAIL = 'crm@mbryla89.webd.pro';

    public function __construct(MailRepository $mailRepository)
    {
        $this->mailRepository = $mailRepository;
    }

    /**
     * 
     * @Route("/mails", name="mails")
     */
    public function mails(Request $request, PaginatorInterface $paginator): Response
    {  
        $mails = $this->mailRepository->findAll();
        $pagination = $paginator->paginate(
            $mails,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('general_panel/mail.html.twig', [
            'mails' =>  $pagination
        ]);
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
    public function create(Request $request, EntityManagerInterface $entityManager, MailFactory $factory): Response
    {
        $form = $this->createForm(MailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form = $form->getData();
            $data = $factory->createMail($form);

            foreach ($data as $key => $object) {
                $entityManager->persist($object);
            }

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
            ->from(self::BASE_EMAIL)
            ->to($mail->getCustomer()->getEmail())
            ->subject($mail->getSubject())
            ->text($mail->getMessage());

        $mailer->send($email);

        $this->mailRepository->saveAfterSent($mail);
        $this->addFlash('successMail', 'Message sent');

        return $this->redirectToRoute('mails_show', [
            'id' => $id
        ]);
    }

    /**
     * @Route("/mail/{id}/edit", name="mails_edit")
     */
    public function edit($id, Request $request): Response
    {
        $mail = $this->mailRepository->find($id);

        if ($mail === null) {
            return new Response($this->renderView('exception/404.html.twig', [],  404));
        }

        $form = $this->createForm(MailType::class, $mail);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $this->mailRepository->save($form->getData());
            $this->addFlash('success', 'Mail updated');
            return $this->redirect('/mail/'.$id);
        }

        return $this->render('mail/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/mail/{id}/delete", name="mails_delete")
     */
    public function delete($id): Response
    {
        $mail = $this->mailRepository->find($id);

        if ($mail === null) {
            
            return new Response($this->renderView('exception/404.html.twig', [],  404));
        }
        $this->mailRepository->removeMail($mail);
        $this->addFlash('success', 'Mail deleted!');

        return $this->redirectToRoute('mails');
    }
}
