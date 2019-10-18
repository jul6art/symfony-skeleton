<?php

namespace App\EventSubscriber;

use App\Entity\Setting;
use App\Event\UserEvent;
use App\Manager\SettingManagerTrait;
use App\Manager\UserManagerTrait;
use App\Traits\FlashBagTrait;
use App\Traits\TranslatorTrait;
use Doctrine\ORM\NonUniqueResultException;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserSubscriber.
 */
class UserSubscriber implements EventSubscriberInterface
{
    use FlashBagTrait;
    use SettingManagerTrait;
    use TranslatorTrait;
    use UserManagerTrait;

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            UserEvent::EDITED => 'onUserEdited',
        ];
    }

    /**
     * @param FormEvent $event
     *
     * @throws NonUniqueResultException
     */
    public function onRegistrationSuccess(FormEvent $event): void
    {
        $user = $event->getForm()->getData();
        $user->setLocale($event->getRequest()->getLocale());
        $user->setTheme($this->settingManager->findOneValueByName(Setting::SETTING_DEFAULT_THEME));
        $this->userManager
            ->updateGroups($user)
            ->save($user);
    }

    /**
     * @param UserEvent $event
     */
    public function onUserEdited(UserEvent $event)
    {
        $this->flashBag->add('success', $this->translator->trans('notification.user.edited', [], 'notification'));
    }
}
