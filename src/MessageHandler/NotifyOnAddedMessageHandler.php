<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 24/11/2019
 * Time: 15:34.
 */

namespace App\MessageHandler;

use App\Entity\Group;
use App\Manager\UserManagerTrait;
use App\Message\NotifyOnAddedMessage;
use App\Service\MailerServiceTrait;
use Doctrine\ORM\NonUniqueResultException;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class NotifyUserOnAddedMessageHandler.
 */
class NotifyOnAddedMessageHandler
{
    use MailerServiceTrait;
    use UserManagerTrait;

    /**
     * @param NotifyOnAddedMessage $message
     *
     * @throws LoaderError
     * @throws NonUniqueResultException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Throwable
     */
    public function __invoke(NotifyOnAddedMessage $message)
    {
        /*
         * SEND CREDENTIALS TO ADDED USER
         */
        $this->mailerService->send($message->getEmail(), 'email/user/add/email.html.twig', [
            'password' => $message->getPassword(),
            'username' => $message->getUsername(),
            'fullname' => sprintf('%s %s', $message->getFirstname(), $message->getLastname()),
        ]);

        $admins = $this->userManager->findByGroup(
            $this->userManager->getGroupManager()->findOneByName(Group::GROUP_NAME_ADMIN)
        );

        /*
         * NOTIFY ADMINS EXCEPT CREATOR
         */
        foreach ($admins as $admin) {
            if (
                !\in_array(strtolower($admin->getEmail()), [
                    strtolower($message->getEmail()),
                    strtolower($message->getCreatedBy()),
                ])
            ) {
                $this->mailerService->send($admin->getEmail(), 'email/user/notifications/add.html.twig', [
                    'user' => $admin,
                    'firstname' => $message->getFirstname(),
                    'lastname' => $message->getLastname(),
                    'fullname' => sprintf('%s %s', $message->getFirstname(), $message->getLastname()),
                    'username' => $message->getUsername(),
                    'email' => $message->getEmail(),
                ]);
            }
        }
    }
}
