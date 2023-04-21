<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/', name: 'app_home')]
    public function index(TrickRepository $trickRepository, Request $request): Response
    {
        $nbFigures = $trickRepository->getNumberOfTricks();
        $figures = $trickRepository->findAll();

        return $this->render('home/index.html.twig', ['figures'=>$figures, 'nbFigures'=>$nbFigures]);
    }
}
