<?php

namespace App\SharedKernel\Service;

use App\Domain\AppUser\Entity\AppUser;
use App\Domain\AppUser\Entity\RecoveryPassword;
use Symfony\Component\Mailer\MailerInterface;

class RecoveryPasswordMailer extends CoreMailer
{
    public function __construct(MailerInterface $mailer, RecoveryPassword $recoveryPassword)
    {
        parent::__construct($mailer);
        $this->from = 'apdcalculator@apd.fr';
        $this->subject = "apdcalculator - Password recovery - Do not reply";
        $this->contentType = 'html';
        
        
        $guid = $recoveryPassword->getGuid();
        $this->content = "
        <h1>Password recovery</h1>
        <p>You have request an password recovery.</p>
        <p>Click on the link below to choose a new password for your account</p>
        <form action='http://localhost:8080/api/V2/updatepassword/$guid' method='POST' target='_blank'>
            <input type='submit' value='Password recovery'>
        </form>
        ";
    }

    public function setTo($to)
    {
        $this->to = $to;
    }
}