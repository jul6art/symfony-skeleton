<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Service;

use Swift_Attachment;
use Throwable;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class MailerService.
 */
class MailerService
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var string
     */
    private $mailer_from_address;

    /**
     * @var string
     */
    private $mailer_from_name;

    /**
     * MailerService constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param Environment   $twig
     * @param string        $mailer_from_address
     * @param string        $mailer_from_name
     */
    public function __construct(\Swift_Mailer $mailer, Environment $twig, string $mailer_from_address, string $mailer_from_name)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->mailer_from_address = $mailer_from_address;
        $this->mailer_from_name = $mailer_from_name;
    }

    /**
     * @param string     $to
     * @param string     $template
     * @param array      $context
     * @param array      $attachments
     * @param array|null $from
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Throwable
     */
    public function send(string $to, string $template, array $context = [], array $attachments = [], array $from = null)
    {
        $message = new \Swift_Message();

        foreach ($attachments as $key => $value) {
            $files = $message->embed(Swift_Attachment::fromPath($value));
            $context['files'][] = $files;
        }

        $message
            ->setFrom($from ?? [
                    $this->mailer_from_address => $this->mailer_from_name,
                ])
            ->setTo($to)
            ->setSubject($this->twig->load($template)->renderBlock('subject', $context))
            ->setBody($this->twig->render($template, $context), 'text/html');

        if (array_key_exists('replyto', $context)) {
            $message->setReplyTo($context['replyto']);
        }

        if (array_key_exists('cc', $context)) {
            $message->setCc($context['cc']);
        }

        if (array_key_exists('bcc', $context)) {
            $message->setBcc($context['bcc']);
        }

        $this->mailer->send($message);
    }
}
