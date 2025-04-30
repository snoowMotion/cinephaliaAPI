<?php

namespace App\Service;

use App\Entity\User;
use Mailjet\Client;
use Mailjet\Resources;

class MailingService
{
    private $mailjet;

    private $fromEmail;
    private $fromName;

    public function __construct($apiKey, $apiSecret, $fromEmail, $fromName)
    {
        $this->mailjet = new Client($apiKey, $apiSecret, true, ['version' => 'v3.1']);
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    public function sendEmail(string $toEmail, string $toName, string $subject, string $content)
    {
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $this->fromEmail,
                        'Name' => $this->fromName,
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName,
                        ]
                    ],
                    'Subject' => $subject,
                    'HTMLPart' => $content,
                ]
            ]
        ];

        $response = $this->mailjet->post(Resources::$Email, ['body' => $body]);
        // 👉 Ici on dumpe toute la réponse Mailjet !

        return $response->success();
    }

    public function sendConfirmationEmail(User $user, string $confirmationUrl)
    {
        $subject = 'Confirmez votre compte Cinéphoria';

        $content = "
            <h2>Bienvenue sur Cinéphoria, {$user->getPrenom()} !</h2>
            <p>Veuillez confirmer votre compte en cliquant sur le lien suivant :</p>
            <p><a href=\"{$confirmationUrl}\">Confirmer mon compte</a></p>
        ";

       return $this->sendEmail(
            $user->getEmail(),
            $user->getPrenom(),
            $subject,
            $content
        );
    }

    public function sendForgetPassword(User $user, string $newPassword)
    {
        $subject = 'Réinitialisation de votre mot de passe';

        $content = "
            <h2>Bonjour {$user->getPrenom()},</h2>
            <p>Votre mot de passe a été réinitialisé. Voici votre nouveau mot de passe :</p>
            <p><strong>{$newPassword}</strong></p>
            <p>Merci de le changer dès votre prochaine connexion.</p>
        ";

        return $this->sendEmail(
            $user->getEmail(),
            $user->getPrenom(),
            $subject,
            $content
        );
    }
}