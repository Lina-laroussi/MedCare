<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Attachment;

class MailerService
{
    public function __construct(private MailerInterface $mailer)
    {
            $this->mailer = $mailer;
    }

    public function sendEmail(
        $from , 
        $to ,
        $content,
        $subject,
        $fichier
    ): void
    {
        $email = (new Email())
            ->from($from)
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->text('Sending emails is fun again!')
            ->html($content)
            ->attach($fichier, 'document.pdf');

            //->addPart(new DataPart(new File('/path/to/documents/terms-of-use.pdf')))

             $this->mailer->send($email);

        // ...
    }

}
