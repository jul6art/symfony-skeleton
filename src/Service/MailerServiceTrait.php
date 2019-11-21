<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Service;

/**
 * Trait MailerServiceTrait.
 */
trait MailerServiceTrait
{
    /**
     * @var MailerService
     */
    private $mailerService;

    /**
     * @return MailerService
     */
    public function getMailerService(): MailerService
    {
        return $this->mailerService;
    }

    /**
     * @param MailerService $mailerService
     *
     * @required
     */
    public function setMailerService(MailerService $mailerService): void
    {
        $this->mailerService = $mailerService;
    }
}
