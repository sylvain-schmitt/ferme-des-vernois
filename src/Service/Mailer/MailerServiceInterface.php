<?php


namespace App\Service\Mailer;


interface MailerServiceInterface
{
    public function send(string $from, array $toAddresses, string $subject, string $mjmlTemplate, string $txt, array $params);
}