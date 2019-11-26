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
use App\Message\NotifyOnRegistrationMessage;
use App\Service\MailerServiceTrait;
use App\Traits\LoggerTrait;
use Doctrine\ORM\NonUniqueResultException;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class NotifyOnRegistrationMessageHandler.
 */
class NotifyOnRegistrationMessageHandler
{
    use LoggerTrait;
    use MailerServiceTrait;
    use UserManagerTrait;

    /**
     * @param NotifyOnRegistrationMessage $message
     *
     * @throws NonUniqueResultException
     * @throws Throwable
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(NotifyOnRegistrationMessage $message)
    {
        $admins = array_merge($this->userManager->findByGroup(
            $this->userManager->getGroupManager()->findOneByName(Group::GROUP_NAME_ADMIN)
        ), $this->userManager->findByGroup(
            $this->userManager->getGroupManager()->findOneByName(Group::GROUP_NAME_SUPER_ADMIN)
        ));

        array_walk($admins, function (User $admin) use ($message) {
            if (null !== $admin->getLastLogin() and strtolower($admin->getEmail()) !== strtolower($message->getEmail())) {
                try {
                    $this->mailerService->send($admin->getEmail(), 'email/user/notifications/register.html.twig', [
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
