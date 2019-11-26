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
use App\Entity\User;
use App\Manager\UserManagerTrait;
use App\Message\NotifyOnAddedMessage;
use App\Service\MailerServiceTrait;
use App\Traits\LoggerTrait;
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
    use LoggerTrait;
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
        try {
            $this->mailerService->send($message->getEmail(), 'email/user/add/email.html.twig', [
                'password' => $message->getPassword(),
                'username' => $message->getUsername(),
                'fullname' => sprintf('%s %s', $message->getFirstname(), $message->getLastname()),
            ]);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }

        $admins = array_merge($this->userManager->findByGroup(
            $this->userManager->getGroupManager()->findOneByName(Group::GROUP_NAME_ADMIN)
        ), $this->userManager->findByGroup(
            $this->userManager->getGroupManager()->findOneByName(Group::GROUP_NAME_SUPER_ADMIN)
        ));

        array_walk($admins, function (User $admin) use ($message) {
            if (
                null !== $admin->getLastLogin() and !\in_array(strtolower($admin->getEmail()), [
                    strtolower($message->getEmail()),
                    strtolower($message->getCreatedBy()),
                ])
            ) {
                try {
                    $this->mailerService->send($admin->getEmail(), 'email/user/notifications/add.html.twig', [
                        'user' => $admin,
                        'firstname' => $message->getFirstname(),
                        'lastname' => $message->getLastname(),
                        'fullname' => sprintf('%s %s', $message->getFirstname(), $message->getLastname()),
                        'username' => $message->getUsername(),
                        'email' => $message->getEmail(),
                    ]);
                } catch (\Exception $e) {
                    $this->logger->critical($e->getMessage());
                }
            }
        });
    }
}
