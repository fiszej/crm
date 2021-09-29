<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeneralPanelController extends AbstractController
{
    /**
     * @Route("/", name="general_panel")
     */
    public function index(): Response
    {
        return $this->render('general_panel/index.html.twig', [
            'controller_name' => 'GeneralPanelController',
        ]);
    }

    public function customers(): Response
    {


        return $this->render('customers.html.twig');
    }
}
