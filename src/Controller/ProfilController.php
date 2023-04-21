<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil')]
    public function index(Request $request, string $public_directory, EntityManagerInterface $entityManager): Response
    {
        /* @var User $user */
        $user = $this->getUser();
        $comments = $user->getUserComment();

        if (!empty($request->files->get('avatar'))) {
            $avatar = $request->files->get('avatar');

            $file = md5(uniqid()) . '.' . $avatar->guessExtension();
            $avatar->move(
                $public_directory . '/uploads',
                $file
            );

            $user->setPathPhoto('/uploads/' . $file);
            $entityManager->flush();
        }

        return $this->render('profil/profil.html.twig',
            ['comments' => $comments,
                'user' => $user]
        );
    }

    #[Route('/profil/delete/comment/{id}', name: 'del_comment')]
    public function deleteCommentaire(Comment $comment, Request $request, CommentRepository $commentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->query->get('_token'))) {
            $commentRepository->remove($comment, true);
        }

        return $this->redirectToRoute('profil');
    }
}
