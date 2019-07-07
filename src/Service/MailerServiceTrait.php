<?php
/**
 * Created by PhpStorm.
 * Audit: gkratz
 * Date: 23/05/2019
 * Time: 09:41.
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
