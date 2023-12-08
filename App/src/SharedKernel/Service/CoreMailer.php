<?php

namespace App\SharedKernel\Service;

use Exception;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class CoreMailer
{
    protected MailerInterface $mailer;
    
    private Email $email;

    protected $from;
    protected $to;
    protected $subject;
    protected $contentType;
    protected $content;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function setEmail(
        string $from,
        string $to,
        string $subject,
    )
    {
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
    }

    public function sendEmail()
    {
        $this->email = (new Email())
        ->from($this->from)
        ->to($this->to)
        ->subject($this->subject);
        if ($this->contentType === 'html') {
            $this->email->html($this->content);
        } elseif ($this->contentType === 'text') {
            $this->email->text($this->content);
        }

        $this->mailer->send($this->email);
    }

    public function setContent(string $content, string $contentType = 'html' | 'text')
    {
        if ($contentType === 'html' || $contentType === 'text') {
            $this->contentType = $contentType;
        } else {
            throw new Exception('The content type must be "html" or "text"', 1);
        }

        $this->content = $content;
    }
}