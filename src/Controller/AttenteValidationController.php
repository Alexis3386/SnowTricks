<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AttenteValidationController extends AbstractController
{
    #[Route('/attente/validation', name: 'attente_validation')]
    public function index(): Response
    {
        return $this->render('attente_validation/index.html.twig', [
            'controller_name' => 'AttenteValidationController',
        ]);
    }
}
