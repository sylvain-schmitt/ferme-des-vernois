<?php


namespace App\Service\Mailer;


interface MailerServiceInterface
{
    public function sendAdmin(string $from, array $toAddresses, string $subject, string $mjmlTemplate, string $txt, array $params);

    public function sendUser(string $from, array $toAddresses, string $subject, string $mjmlTemplate, string $txt, array $params);

    public function sendContact(string $from, array $toAddresses, string $subject, string $mjmlTemplate, string $txt, array $params);
}