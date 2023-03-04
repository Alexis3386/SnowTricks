<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    #[Route('/trick/{slug}', name: 'show_trick')]
    public function index(Trick $trick): Response
    {
        // $trick = $trickRepository->find($id);
        $author = $trick->getUser();
        $images = $trick->getPictures();
        $movies = $trick->getMovies();
        $commentaires = $trick->getComments();

        return $this->render('trick/index.html.twig', [
            'trick' => $trick,
            'author' => $author,
            'images' => $images,
            'movies' => $movies,
            'commentaires' => $commentaires,
        ]);
    }
}
