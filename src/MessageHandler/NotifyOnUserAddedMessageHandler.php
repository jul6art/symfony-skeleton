<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 24/11/2019
 * Time: 15:34.
 */

namespace App\MessageHandler;

use App\Entity\Constants\GroupName;
use App\Entity\User;
use App\Manager\Traits\UserManagerAwareTrait;
use App\Message\NotifyOnUserAddedMessage;
use App\Service\Traits\MailerServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class NotifyUserOnAddedMessageHandler.
 */
class NotifyOnUserAddedMessageHandler extends AbstractMessageHandler
{
    use MailerServiceAwareTrait;
    use UserManagerAwareTrait;

    /**
     * @param NotifyOnUserAddedMessage $message
     *
     * @throws LoaderError
     * @throws NonUniqueResultException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Throwable
     */
    public function __invoke(NotifyOnUserAddedMessage $message)
    {
        $admins = $this->userManager->findByGroupListExcepted(
            [
                $this->userManager->getGroupManager()->findOneByName(GroupName::GROUP_NAME_ADMIN),
                $this->userManager->getGroupManager()->findOneByName(GroupName::GROUP_NAME_SUPER_ADMIN),
            ],
            [$message->getId(), $message->getCreatedBy()]
        );

        /*
         * PUSH NOTIFICATIONS
         */
        try {
            $this->publisherService->publish('admin_user_add', [], [
                'id' => $message->getId(),
            ], $admins);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }

        /*
         * EMAILS
         */
        try {
            $this->mailerService->send($message->getEmail(), 'email/user/add/email.html.twig', [
                'password' => $message->getPassword(),
                'username' => $message->getUsername(),
                'fullname' => sprintf('%s %s', $message->getFirstname(), $message->getLastname()),
            ]);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }

        array_walk($admins, function (User $admin) use ($message) {
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
        });
    }
}
