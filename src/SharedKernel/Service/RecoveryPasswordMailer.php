<?php

namespace App\SharedKernel\Service;

use App\Domain\AppUser\Entity\RecoveryPassword;
use Symfony\Component\Mailer\MailerInterface;

class RecoveryPasswordMailer extends CoreMailer
{
    public function __construct(MailerInterface $mailer, RecoveryPassword $recoveryPassword)
    {
        parent::__construct($mailer);
        $this->from = 'api@aeraulic.com';
        $this->subject = "apdcalculator - Password recovery - Do not reply";
        $this->contentType = 'html';
        $frontend_url = $_ENV['FRONTEND_URL'];
        $frontendRecoverPasswordUri = $_ENV['FRONTEND_RECOVER_PASSWORD_URI'];
        
        $guid = $recoveryPassword->getGuid();
        $this->content = "
        <h1>Password recovery</h1>
        <p>You have request an password recovery.</p>
        <p>Click on the link below to choose a new password for your account</p>
        <form action='$frontend_url/$frontendRecoverPasswordUri/$guid' method='GET' target='_blank'>
            <input type='submit' value='Password recovery'>
        </form>
        ";
    }

    public function setTo($to)
    {
        $this->to = $to;
    }
}