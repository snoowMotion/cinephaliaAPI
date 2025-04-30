<?php

namespace App\Service;

use App\Entity\User;

class AccountConfirmationMailer
{
    private MailingService $mailService;

    public function __construct(MailingService $mailService)
    {
        $this->mailService = $mailService;
    }


}
