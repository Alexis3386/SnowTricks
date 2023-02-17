<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    public function __construct(private MailerInterface $mailer, private string $sender_email)
    {
    }
    public function sendValidationEmail(
        User $user
    ): Void {
        $email = (new TemplatedEmail())
            ->from($this->sender_email)
            ->to($user->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Validation de votre compte.')
            ->htmlTemplate('emails/signup.html.twig')

        // pass variables (name => value) to the template
            ->context(['user' => $user]);

        $this->mailer->send($email);
    }

    //  $mail->send(
    //     'no-reply@domain.fr',
    //     $user->getEmail(),
    //     'Réinitialisation de mot de passe',
    //     'reset_password',
    //     $context
    // );

    public function send($to, $email, $subject, $template, $context) : Void
    {
        $email = (new TemplatedEmail())
        ->from($this->sender_email)
        ->to($email)
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject($subject)
        ->htmlTemplate('emails/' . $template . '.html.twig')

    // pass variables (name => value) to the template
        ->context($context);

    $this->mailer->send($email);
    }
}
