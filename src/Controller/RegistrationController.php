<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{

    #[Route('/register', name: 'user_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        MailerService $mailer,
        ManagerRegistry $doctrine,
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->add('Submit', SubmitType::class, [
            'attr' => ['class' => 'save'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
            $em = $doctrine->getManager();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('rawPassword')->getData()
            );
            $user->setPassword($hashedPassword);
            $user->setToken(bin2hex(random_bytes(32)));
            $em->persist($user);
            $em->flush();
            $mailer->sendValidationEmail($user);

            //todo return message de validation et redirect
        }

        return $this->render('user/register.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/valide/{user}/{token}', name: 'user_validate')]
    public function valide(User $user, string $token, ManagerRegistry $doctrine): Response
    {
        if ($user->getToken() === $token) {
            $em = $doctrine->getManager();
            $user->validate();
            $user->setToken(null);
            $em->flush();

            //todo return message de validation et redirect
        }

        return $this->redirectToRoute('user_register');
    }
}
