<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use DateTime;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\CommentFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/trick/{slug}', name: 'show_trick')]
    public function index(Trick                  $trick, Request $request,
                          EntityManagerInterface $entityManager,
                          PaginatorInterface     $paginator,
                          CommentRepository      $commentRepository,
                          int                    $number_comment_by_page): Response
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
        
        $numberOfPage = ceil($commentRepository->countByTrick($trick->getId())/ $number_comment_by_page);
        $author = $trick->getUser();
        $pictures = $trick->getPictures();
        $movies = $trick->getMovies();
        $comment = $this->getCommentaires($trick, $request, $paginator, $commentRepository, $number_comment_by_page);

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick,
            'author' => $author,
            'pictures' => $pictures,
            'movies' => $movies,
            'firts_comments' => $comment,
            'number_of_pages' => $numberOfPage,
            'commentForm' => $form->createView()
        ]);
    }

    #[Route('/comment/{id}', name: 'comment')]
    public function getCommentaires(Trick              $trick,
                                    Request            $request,
                                    PaginatorInterface $paginator,
                                    CommentRepository  $commentRepository,
                                    int                $number_comment_by_page
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
            $number_comment_by_page
        );
        return new Response($this->renderView('trick/commentaires.html.twig',
            ['pagination' => $pagination]
        ));
    }
}
