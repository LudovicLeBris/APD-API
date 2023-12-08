<?php

namespace App\SharedKernel\Service;

use App\Domain\AppUser\Entity\AppUser;
use Symfony\Component\Mailer\MailerInterface;

class ValidateRegisterMailer extends CoreMailer
{
    public function __construct(MailerInterface $mailer, AppUser $appUser)
    {
        parent::__construct($mailer);
        $this->from = 'apdcalculator@apd.fr';
        $this->subject = "apdcalculator - Account Activating - Do not reply";
        $this->contentType = 'html';
        
        
        $id = $appUser->getId();
        $email = $appUser->getEmail();
        $this->content = "
        <h1>Account validation</h1>
        <p>Your account need a validation to be enabled.</p>
        <p>Click on the link below to activate your account : $email</p>
        <form action='http://localhost:8080/api/V2/register/$id' method='POST' target='_blank'>
            <input type='submit' value='Account activation'>
        </form>
        ";
    }

    public function setTo($to)
    {
        $this->to = $to;
    }
}