<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CustomerType;
use App\Entity\Customer;
use DateTimeImmutable;
use App\Entity\ApiData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
class ApiController extends AbstractController
{
 /**
     * @Route("/api/{id}/create", name="customer_create_from_api")
     */
    public function newFromApi($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $apiData = $this->getDoctrine()
            ->getRepository(ApiData::class)
            ->find($id);
        
        if ($apiData === null) {
            return new Response($this->renderView('exception/404.html.twig', [],  404));
        }

        $customer = new Customer();
        $customer->setName($apiData->getName());
        $customer->setEmail($apiData->getEmail());
        $customer->setPhone($apiData->getPhone());
        $customer->setCity($apiData->getCity());
        $customer->setAddress($apiData->getAddress());
        $customer->setZipcode($apiData->getZipcode());
        $customer->setLogo($customer->logos[rand(0, 7)]);
        $customer->setCreatedAt(new DateTimeImmutable('now'));

        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();
            $entityManager = $this->getDoctrine()
                ->getManager();
            $entityManager->persist($customer);
            $entityManager->remove($apiData);
            $entityManager->flush();
            $this->addFlash('success', 'Company updated');
            return $this->redirect('/customers');
        }

        return $this->render('customer/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    
}
