<?php


namespace App\Service\Mailer;


use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailerService
{

    private $mailer;
    private $twig;
    private $logger;
    private $binMjml;

    public function __construct(MailerInterface $mailer, Environment $twig, LoggerInterface $logger, string $binMjml)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->twig = $twig;
        $this->binMjml = $binMjml;
    }

    private function convertMjmlToHtml(string $templateMjml, array $context): string
    {
        $render = null;

        try {
            $render = (new BinaryRenderer($this->binMjml))
                ->render($this->twig->render($templateMjml, $context));
        } catch (LoaderError $exception) {
            $this->logger->error('Erreur de chargement du template', [
                'exception' => $exception,
            ]);
        } catch (RuntimeError $exception) {
            $this->logger->error('Erreur à l\'exécution', [
                'exception' => $exception,
            ]);
        } catch (SyntaxError $exception) {
            $this->logger->error('Erreur de syntaxe', [
                'exception' => $exception,
            ]);
        }
        return $render;
    }

    public function sendAdmin(string $from, array $toAddresses, string $subject, string $mjmlTemplate, string $txt, array $params)
    {
        $toAddresses = ['fermedesvernois@gmail.com', $from];
        $email = (new TemplatedEmail())
            ->from($from)
            ->to(...$toAddresses)
            ->subject($subject)
            ->html($this->convertMjmlToHtml($mjmlTemplate, $params))
            ->textTemplate($txt)
            ->context($params)
        ;
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception){
            $this->logger->error('Un problème est survenue lors de l\'envoie du mail', [
                'execption'=> $exception,
            ]);
        }
    }

    public function sendUser(string $from, array $toAddresses, string $subject, string $mjmlTemplate, string $txt, array $params) {
        $toAddresses = [$from];
        $email = (new TemplatedEmail())
            ->from('fermedesvernois@gmail.com')
            ->to(...$toAddresses)
            ->subject($subject)
            ->html($this->convertMjmlToHtml($mjmlTemplate, $params))
            ->textTemplate($txt)
            ->context($params)
        ;
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception){
            $this->logger->error('Un problème est survenue lors de l\'envoie du mail', [
                'execption'=> $exception,
            ]);
        }
    }

    public function sendContact(string $from, array $toAddresses, string $subject, string $mjmlTemplate, string $txt, array $params)
    {
        $toAddresses = ['romaricrapine25@gmail.com', $from];
        $email = (new TemplatedEmail())
            ->from($from)
            ->to(...$toAddresses)
            ->subject($subject)
            ->html($this->convertMjmlToHtml($mjmlTemplate, $params))
            ->textTemplate($txt)
            ->context($params)
        ;
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception){
            $this->logger->error('Un problème est survenue lors de l\'envoie du mail', [
                'execption'=> $exception,
            ]);
        }
    }

}