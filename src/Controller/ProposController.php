<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProposController extends AbstractController
{
    /**
     * @Route("/propos", name="propos")
     */
    public function index()
    {
        return $this->render('propos/index.html.twig', [
            'controller_name' => 'ProposController',
        ]);
    }
}
