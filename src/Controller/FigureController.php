<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FigureController extends AbstractController
{
    #[Route('/figure', name: 'figure')]
    public function index(TrickRepository $trickRepository, SerializerInterface $serializer): Response
    {
        return $this->render('home/indexTricks.html.twig');
    }
}
