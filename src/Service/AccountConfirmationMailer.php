<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AccountConfirmationMailer
{
    private MailerInterface $mailer;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $urlGenerator)
    {
        $this->mailer = $mailer;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendConfirmationEmail(User $user): void
    {
        $confirmationUrl = $this->urlGenerator->generate(
            'app_confirm_user',
            ['token' => $user->getConfirmationToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $email = (new Email())
            ->from('no-reply@cinephoria.local')
            ->to($user->getEmail())
            ->subject('Confirmez votre compte Cinéphoria')
            ->html("
                <h2>Bienvenue sur Cinéphoria, {$user->getPrenom()} !</h2>
                <p>Veuillez confirmer votre compte en cliquant sur le lien suivant :</p>
                <p><a href=\"{$confirmationUrl}\">Confirmer mon compte</a></p>
            ");

        $this->mailer->send($email);
    }
}
