<?php

namespace App\Controller;

use DateTime;
use App\Entity\Trick;
use App\Entity\Picture;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/trick')]
class TrickControllerEditController extends AbstractController
{
    #[Route('/', name: 'app_trick_controller_index', methods: ['GET'])]
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('trick_controller_edit/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_trick_controller_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TrickRepository $trickRepository, string $public_directory): Response
    {
        
        $slugger = new AsciiSlugger();
        $trick = new Trick();
        $user = $this->getUser();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictures = $form->get('pictures')->getData();

            foreach ($pictures as $picture) {
                $file = md5(uniqid()) . '.' . $picture->guessExtension();

                $picture->move(
                    $public_directory . '/uploads',
                    $file
                );

                $pic = new Picture();
                $pic->setPath('/uploads/' . $file);
                $trick->addPicture($pic);
            }

            $trick->setSlug($slugger->slug($form->get('name')->getData()));
            $trick->setCreationDate(new DateTime('now'));
            $trick->setModificationDate($trick->getCreationDate());
            $trick->setUser($user);
            $trickRepository->save($trick, true);

            return $this->redirectToRoute('app_trick_controller_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trick_controller_edit/new.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_trick_controller_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trick $trick, TrickRepository $trickRepository): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        $movies = $trick->getMovies();

        if ($form->isSubmitted() && $form->isValid()) {
            $trickRepository->save($trick, true);

            return $this->redirectToRoute('app_trick_controller_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trick_controller_edit/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
            'movies'=> $movies,
            'edit' => true
        ]);
    }

    #[Route('/{id}/delete/', name: 'app_trick_controller_delete', methods: ['POST'])]
    public function delete(Request $request, Trick $trick, TrickRepository $trickRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {
            $trickRepository->remove($trick, true);
        }

        return $this->redirectToRoute('app_trick_controller_index', [], Response::HTTP_SEE_OTHER);
    }
}
