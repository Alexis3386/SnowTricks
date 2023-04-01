<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use DateTime;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\CommentFormType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    #[Route('/trick/{slug}', name: 'show_trick')]
    public function index(Trick                  $trick, Request $request,
                          EntityManagerInterface $entityManager,
                          PaginatorInterface     $paginator,
                          CommentRepository      $commentRepository): Response
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
        }
        $author = $trick->getUser();
        $pictures = $trick->getPictures();
        $movies = $trick->getMovies();
        $comment = $this->getCommentaires($trick, $request, $paginator, $commentRepository);

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick,
            'author' => $author,
            'pictures' => $pictures,
            'movies' => $movies,
            'firts_comments' => $comment,
            'commentForm' => $form->createView()
        ]);
    }

    #[Route('/comment/{id}', name: 'comment')]
    public function getCommentaires(Trick              $trick,
                                    Request            $request,
                                    PaginatorInterface $paginator,
                                    CommentRepository  $commentRepository,
    ): Response
    {
        $query = $commentRepository->findByTrickQuery($trick->getId());
        $page = null;
        $data = json_decode($request->getContent(), true);
        if ($data !== null) {
            $page = $data['page'];
        }
        if ($page === null) {
            $page = 1;
        }
        $pagination = $paginator->paginate(
            $query,
            $page,
            4
        );
        return new Response($contents = $this->renderView('trick/commentaires.html.twig',
            ['pagination' => $pagination]
        ));
    }
}
