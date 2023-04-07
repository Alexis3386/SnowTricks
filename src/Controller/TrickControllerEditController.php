<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Repository\PictureRepository;
use DateTime;
use App\Entity\Trick;
use App\Entity\Picture;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            $this->processImage($form, $public_directory, $trick);
            $this->processMovie($form, $trick);

            $trick->setSlug($slugger->slug($form->get('name')->getData()));
            $trick->setCreationDate(new DateTime('now'));
            $trick->setModificationDate($trick->getCreationDate());
            $trick->setUser($user);
            $trickRepository->save($trick, true);

            return $this->redirectToRoute('app_trick_controller_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trick_controller_edit/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_trick_controller_edit', methods: ['GET', 'POST'])]
    public function edit(Request                $request,
                         Trick                  $trick,
                         int                    $id,
                         string                 $public_directory,
                         EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        $movies = $trick->getMovies();
        $pictures = $trick->getPictures();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->processImage($form, $public_directory, $trick);
            $this->processMovie($form, $trick);
            $em->flush();

            return $this->redirectToRoute('app_trick_controller_edit', ["id" => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trick_controller_edit/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'movies' => $movies,
            'pictures' => $pictures,
        ]);
    }

    #[Route('/{id}/delete/', name: 'app_trick_controller_delete', methods: ['POST', 'GET'])]
    public function delete(Request $request, Trick $trick, TrickRepository $trickRepository): Response
    {

        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->query->get('_token'))) {
            $trickRepository->remove($trick, true);
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/delete_picture/', name: 'app_trick_controller_delete_picture', methods: ['DELETE'])]
    public function delete_picture(Request $request, Picture $picture, PictureRepository $pictureRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete' . $picture->getId(), $data['_token'])) {
            $pictureRepository->remove($picture, true);

            return new JsonResponse(['success' => true], 200);
        }

        return new JsonResponse(['error' => 'Token ivalide'], 400);
    }

    #[Route('/{id}/delete_movie/', name: 'app_trick_controller_delete_movie', methods: ['DELETE'])]
    public function delete_movie(Request $request, Movie $movie, MovieRepository $pictureRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete' . $movie->getId(), $data['_token'])) {
            $pictureRepository->remove($movie, true);

            return new JsonResponse(['success' => true], 200);
        }

        return new JsonResponse(['error' => 'Token ivalide'], 400);
    }

    private function processImage(FormInterface $form, string $public_directory, Trick $trick): void
    {
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
    }

    private function processMovie(FormInterface $form, Trick $trick): void
    {
        $movies = $form->get('movies')->getData();

        foreach ($movies as $movie) {
            $trick->addMovie($movie);
        }
    }
}
