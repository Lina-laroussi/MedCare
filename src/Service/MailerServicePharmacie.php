<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MailerServicePharmacie
{
    public function __construct(private MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(
        $from ,
        $to ,
        $htmltemplate ,
        $subject,
        $tmpFile,
        $context
    ): void
    {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->htmltemplate('facture/template.html.twig')
            ->subject($subject)
            ->text('Sending emails is fun again!')
            ->attach($tmpFile, 'ab.pdf', 'application/pdf')
            ->context($context);

        //->addPart(new DataPart(new File('/path/to/documents/terms-of-use.pdf')))

        $this->mailer->send($email);

        // ...
    }

}