<?php

namespace App\Controller;

use DateTime;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    #[Route('/trick/{slug}', name: 'show_trick')]
    public function index(Trick $trick, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTime('now');
            $comment->setCreationDate($date);
            $comment->setUser($user);
            $comment->setTrick($trick);
            $entityManager->persist($comment);
            $entityManager->flush();
        };
        // $trick = $trickRepository->find($id);
        $author = $trick->getUser();
        $pictures = $trick->getPictures();
        $movies = $trick->getMovies();
        $comments = $trick->getComments();

        return $this->render('trick/index.html.twig', [
            'trick' => $trick,
            'author' => $author,
            'pictures' => $pictures,
            'movies' => $movies,
            'comments' => $comments,
            'commentForm' => $form->createView()
        ]);
    }
}
